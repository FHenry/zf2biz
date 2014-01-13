<?php
namespace Galerie\Model;

use Zend\Db\Adapter\Adapter;
use Custom\Model\Manager;
use Custom\Model\Entity;

class GalerieTable extends Manager
{
    public $table = 'gallery';

    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new Galerie());
    }

    public function get($id)
    {
        return $this->one(array(
            'id' => (int) $id
        ));
    }

    protected function is_new(Entity $entity)
    {
        return empty($entity->id);
    }

    protected function extract_primary(Entity $entity)
    {
        return array(
            'id' => (int) $entity->id
        );
    }

    // La méthode save remplace saveGalerie

    public function delete($id)
    {
        parent::delete(array(
            'id' => (int) $id
        ));
    }


    // Fonctionnalités supplémentaires

    public function get_by_owner($id_user)
    {
        return $this->select(array(
            'id_user' => (int) $id_user,
        ));
    }

    public function delete_by_owner($id_user)
    {
        $this->delete(array(
            'id_user' => (int) $id_user,
        ));
    }

    public function get_by_name($name)
    {
        // la colonne 'name' est UNIQUE
        return $this->one(array(
            'name' => name,
        ));
    }

    public function find_by_name($name)
    {
        // la colonne 'name' est UNIQUE
        return $this->any(array(
            'name' => name,
        ));
    }

}
