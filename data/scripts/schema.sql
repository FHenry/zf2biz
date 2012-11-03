PRAGMA foreign_keys = ON;

/*
 * Utilisateur ayant un compte sur l'application
 */
CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
	lastname VARCHAR(32) NOT NULL COLLATE NOCASE,
	firstname VARCHAR(32) NOT NULL COLLATE NOCASE,
	birth DATE NOT NULL,
	email VARCHAR(32) NOT NULL UNIQUE COLLATE NOCASE,
	UNIQUE (lastname, firstname, birth)
);

CREATE INDEX idx_user_id ON user(id);
CREATE INDEX idx_user_unique_triptyque ON user(lastname, firstname, birth);
CREATE INDEX idx_user_email ON user(email);
CREATE INDEX idx_user_all ON user(id, lastname, firstname, birth, email);

/*
 * Gestion du carnet de contacts entre utilisateurs
 * Non réciproque
 */
CREATE TABLE contact (
    id_user_owner INTEGER ASC REFERENCES user(id),
    id_user_contact INTEGER ASC REFERENCES user(id),
    PRIMARY KEY (id_user_owner, id_user_contact)
);

CREATE INDEX idx_contact ON contact(id_user_owner, id_user_contact);

/*
 * Gallerie de photos (pas de workflow associé)
 */
CREATE TABLE gallery (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user INTEGER REFERENCES user(id),
    name VARCHAR(32) NOT NULL UNIQUE COLLATE NOCASE,
    description TEXT NOT NULL,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_gallery_id ON gallery(id);
CREATE INDEX idx_gallery_name ON gallery(name);
CREATE INDEX idx_gallery_user_fk ON gallery(id_user);
CREATE INDEX idx_gallery_all ON gallery(id, id_user, name, description, created, updated);

/*
 * Element de gallerie : photographie
 * Plusieurs photographies sont stockées sur le disque dur, aux formats:
 * - original   (format de l'appareil photo)
 * - écran      (format d'affichage de la photo à l'écran)
 * - miniature  (format d'affichage pour les miniatures de la gallerie)
 */
CREATE TABLE photo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_gallery INTEGER REFERENCES gallery(id),
    name VARCHAR(16) NOT NULL UNIQUE COLLATE RTRIM,
    description TEXT NOT NULL,
    filename VARCHAR(12) NOT NULL COLLATE NOCASE,
    extension VARCHAR(4) NOT NULL COLLATE NOCASE CHECK (extension IN ('jpg', 'png')),
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_photo_id ON photo(id);
CREATE INDEX idx_photo_name ON photo(name);
CREATE INDEX idx_photo_gallery_fk ON photo(id_gallery);
CREATE INDEX idx_photo_all ON photo(id, id_gallery, name, description, filename, extension, created, updated);

/*
 * Commande qui dispose d'un workflow:
 * - le propriétaire de la gallerie ouvre une commande
 * - tant que la commande est ouverte, les contacts du propriétaire peuvent demander des exemplaire de chaque photo
 * - après une certaine période, la commande passé au statut en attente; les photos sont en cours de développement
 * - lorsque la commande est reçue, elle est alors fermée
 * - tous les contacts du propriétaires passent donc commande en même temps
 * - le propriétaire peut réouvrir au besoin une nouvelle commande
 */
CREATE TABLE order_form (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_gallery UNSIGNED SMALLINT REFERENCES gallery (id),
    status VARCHAR(4) NOT NULL COLLATE NOCASE CHECK (status IN ('open', 'wait', 'end')) DEFAULT 'open',
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    closed TIMESTAMP NOT NULL DEFAULT (datetime('now', '+1 month')),
    ended TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX idx_order_form_id ON order_form(id);
CREATE INDEX idx_order_form_gallery_fk ON order_form(id_gallery);
CREATE INDEX idx_order_form_all ON order_form(id, id_gallery, status, created, closed, ended);

/*
 * élément de commande :
 * - il n'est pas possible de créer un tel élément lorsque la commande n'est pas ouverte
 * - il n'est pas possible de passer commande si l'on ne fait pas parti des contacts du propriétaire
 */
CREATE TABLE order_element (
    id_order_form INTEGER REFERENCES order_form(id),
    id_user INTEGER REFERENCES user(id),
    id_photo INTEGER REFERENCES photo(id),
    quantity UNSIGNED SMALLINT DEFAULT 1,
    PRIMARY KEY (id_user, id_order_form, id_photo)
);

CREATE INDEX idx_order_element_primary ON order_element(id_order_form, id_user, id_photo);
CREATE INDEX idx_order_element_all ON order_element(id_order_form, id_user, id_photo, quantity);

/*
 * à chaque fois que les propriétés d'une gallerie sont modifiées, le champs contenant la date de mise à jour l'est également
 */
CREATE TRIGGER gallery_update_management AFTER UPDATE ON gallery
BEGIN
    UPDATE gallery SET updated = datetime('now') WHERE id = old.id;
END;

/*
 * à chaque fois que les propriétés d'une photographie sont modifiées, le champs contenant la date de mise à jour l'est également ainsi que celui de la gallerie liée
 */
CREATE TRIGGER photo_update_management AFTER UPDATE ON photo
BEGIN
    UPDATE gallery SET updated = datetime('now') WHERE id = old.id_gallery;
    UPDATE photo SET updated = datetime('now') WHERE id = old.id;
END;

/*
 * Gestion du workflow sur une commande: mise à jour de la date de fermeture au changement de statut open vers wait
 */
CREATE TRIGGER order_form_update_management_closed AFTER UPDATE OF status ON order_form WHEN old.status='open' AND new.status='wait'
BEGIN
    UPDATE order_form SET closed = datetime('now') WHERE id = old.id;
END;

/*
 * Gestion du workflow sur une commande: mise à jour de la date de fermeture au changement de statut wait vers end
 */
CREATE TRIGGER order_form_update_management_ended AFTER UPDATE OF status ON order_form WHEN old.status='wait' AND new.status='end'
BEGIN
    UPDATE order_form SET ended = datetime('now') WHERE id = old.id;
END;

/*
 * Gestion du workflow sur une commande: il doit être impossible d'aller en arrière
 */
CREATE TRIGGER order_form_update_management_workflow BEFORE UPDATE OF status ON order_form 
BEGIN
    SELECT CASE
        WHEN (old.status='wait' AND new.status='open')
            OR (old.status='end' AND new.status<>'end')
        THEN RAISE(ABORT, 'new status do not respect workflow')
    END;
END;

/*
 * Gestion d'un élément de commande: il doit être impossible de modifier uns des clés primaires
 */
CREATE TRIGGER order_element_update_management_workflow BEFORE UPDATE OF status ON order_element
BEGIN
    SELECT CASE
        WHEN old.id_order_form <> new.id_order_form
            OR old.id_user <> new.id_user
            OR old.id_photo <> new.id_photo
        THEN RAISE(ABORT, 'bad usage of order element')
    END;
END;

/*
 * Gestion du workflow sur un élément de commande: il doit être impossible de modifier un élément de commande si la commande n'est pas ouverte
 */
CREATE TRIGGER order_element_update_management_when_closed BEFORE UPDATE ON order_element
BEGIN
    SELECT CASE
        WHEN ((SELECT order_form.id FROM order_form WHERE order_form.id=old.id_order_form AND order_form.status='open') IS NULL)
        THEN RAISE(ABORT, 'try to update element in not opened order form')
    END;
END;

/*
 * Gestion du workflow sur un élément de commande: il doit être impossible de rajouter un élément de commande si la commande n'est pas ouverte
 */
CREATE TRIGGER order_element_insert_management_when_closed BEFORE INSERT ON order_element
BEGIN
    SELECT CASE
        WHEN ((SELECT order_form.id FROM order_form WHERE order_form.id=new.id_order_form AND order_form.status='open') IS NULL)
        THEN RAISE(ABORT, 'try to insert element in not opened order form')
    END;
END;

/*
 * Gestion du contraintes sur un élément de commande: un utilisateur ne faisant pas partie des contacts du propriétaire de la gallerie concernée par la commande ne doit pas pouvoir créer un élément de commande.
 */
CREATE TRIGGER order_element_insert_management_check_user_is_authored BEFORE INSERT ON order_element
BEGIN
    SELECT CASE
        WHEN (
            (
                SELECT contact.id_user_contact
                FROM contact
                JOIN gallery ON gallery.id_user=contact.id_user_owner
                JOIN order_form ON order_form.id_gallery=gallery.id
                WHERE (contact.id_user_contact=new.id_user OR contact.id_user_owner=new.id_user)
                AND order_form.id=new.id_order_form
            ) IS NULL
        )
        THEN RAISE(ABORT, 'unauthorized user is ordering')
    END;
END;

