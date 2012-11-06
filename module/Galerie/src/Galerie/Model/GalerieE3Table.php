<?php
namespace Galerie\Model

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class GalerieTableE extends AbstractTableGateway
{
    public $table = 'galerie';

    public function __construct(Adapter $adapter)
    {
        // Composition avec l'adaptateur
        $this->adapter = $adapter;

        // Utilisation du patron de conception Prototype
        // pour la création des objets ResultSet
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(
            new Galerie()
        );

        // Initialisation du gestionnaire
        $this->initialize();
    }

    public function fetchAll()
    {
        return $this->select();
    }

    public function getGalerie($id)
    {
        if ($id === null) {
            $row = null;
        } else {
            $row = $this->select(array(
                'id' => (int) $id
            ))->current();
        }
        if (!$row) {
            throw new \Exception("cannot get row $id in table 'galerie'");
        }
        return $row;
    }

    public saveGalerie(Galerie $galerie)
    {
        if ($galerie->id === null) {
            $this->insert(
                $galerie->toUpdatableArray()
            );
        } elseif ($this->getGalerie($galerie->id)) {
            $this->update(
                $galerie->toUpdatableArray(),
                $galerie->toPrimaryArray()
            );
        } else {
            throw new \Exception("cannot update row $galerie->id in table 'galerie'");
        }
    }

    public function deleteGalerie($id)
    {
        $this->delete(array(
                'id' => (int) $id
            )
        );
    }

}
