PRAGMA foreign_keys = ON;

INSERT INTO user (lastname, firstname, birth, email)
      SELECT 'Finy', 'Alain', '1962-09-17', 'alain@example.com'
UNION SELECT 'Mulder', 'Fox', '1980-12-20', 'fox@ailleurs.com'
UNION SELECT 'Cah', 'Paul', '1972-10-19', 'paul.c@example.com'
UNION SELECT 'Satch', 'Joe', '1956-07-15', 'john.d@example.com';


INSERT INTO contact
      SELECT 1, 2
UNION SELECT 1, 3
UNION SELECT 1, 4
UNION SELECT 2, 1
UNION SELECT 2, 3
UNION SELECT 3, 1;


INSERT INTO gallery (id_user, name, description)
      SELECT 2, 'Pérou', 'Photos des vacances au Pérou, 2011'
UNION SELECT 1, 'Himalaya', 'Entrainement requis'
UNION SELECT 2, 'Parapente', 'Photos de vol en parapente';


INSERT INTO photo (id_gallery, name, description, filename, extension)
      SELECT 1, 'Machu Picchu 1', 'Vue d''ensemble du Machu Picchu', 'P9110931', 'jpg'
UNION SELECT 1, 'Machu Picchu 2', 'Vue depuis la ville', 'P9110957', 'jpg' 
UNION SELECT 1, 'Paysages 1', 'Un peu désertique', 'P9090395', 'jpg'
UNION SELECT 1, 'Paysages 2', 'Un peu de verdure', 'P9090577', 'jpg'
UNION SELECT 2, 'Ama Dablam', 'Sommet à 6856m. Situé au pied de l''Everest et du Lhotse. Signifie "reliquaire de la mère" en référence au pendentif que portent les Sherpanis', 'ama_dablam', 'jpg'
UNION SELECT 2, 'Nupse', 'Sommet à 7864m', 'nupse', 'jpg'
UNION SELECT 2, 'Panorama', 'Vue vers l''est depuis le camp d''altitude', 'panorama', 'jpg'
UNION SELECT 1, 'Lac Titicaca', 'Lac Titicaca', 'lt', 'jpg'
UNION SELECT 3, 'Pêle-mêle', 'Pêle-mêle de photographies', 'pelemele', 'jpg';


INSERT INTO order_form (id_gallery)
      SELECT 1
UNION SELECT 2
UNION SELECT 2;


INSERT INTO order_element (id_order_form, id_user, id_photo, quantity)
      SELECT 1, 1, 1, 1
UNION SELECT 1, 1, 2, 1
UNION SELECT 1, 1, 3, 1
UNION SELECT 1, 1, 4, 1
UNION SELECT 1, 2, 1, 2
UNION SELECT 1, 2, 2, 4
UNION SELECT 1, 1, 2, 1
UNION SELECT 2, 1, 1, 1
UNION SELECT 2, 1, 2, 1;

UPDATE order_form SET status='wait' WHERE id IN (1, 2);
UPDATE order_form SET status='end' WHERE id=1;

