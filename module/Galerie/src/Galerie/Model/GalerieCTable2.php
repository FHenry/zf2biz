<?php
namespace Galerie\Model;

use Zend\Db\Adapter\Adapter;
use Custom\Model\Manager;
use Custom\Model\Entity;

class GalerieCTable2 extends Manager
{
    public $table = 'gallery';

    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new GalerieC());
    }

    public function fetchAll()
    {
        return $this->all();
    }

    public function getGalerie($id)
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

    public function saveGalerie(Galerie $galerie)
    {
        $this->save($galerie);
    }

    public function deleteGalerie($id)
    {
        $this->delete(array(
            'id' => (int) $id
        ));
    }

}
