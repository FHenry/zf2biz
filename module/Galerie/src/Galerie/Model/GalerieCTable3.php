<?php
namespace Galerie\Model;

use Zend\Db\Adapter\Adapter;
use Custom\Model\Manager;
use Custom\Model\Entity;

class GalerieCTable3 extends Manager
{
    public $table = 'gallery';

    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new GalerieC());
    }

    public function get($id)
    {
        return $this->one(array(
            'id' => (int) $id
        ));
    }

    protected function is_new(Entity $entity)
    {
        return $entity->id === null;
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

}
