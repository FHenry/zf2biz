<?php
namespace Galerie\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;


class GalerieBTable extends AbstractTableGateway
{
    public $table = 'gallery';

    public function __construct(Adapter $adapter)
    {
        // Composition avec l'adaptateur
        $this->adapter = $adapter;

        // Utilisation du patron de conception Prototype
        // pour la création des objets ResultSet
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(
            new GalerieB()
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
                'id' => (int) $id,
            ))->current();
        }
        if (!$row) {
            throw new \Exception("cannot get row $id in table 'galerie'");
        }
        return $row;
    }

    public function saveGalerie(GalerieA $galerie)
    {
        if ($galerie->getId() === null) {
            $this->insert(
                array(
                    'id_user' => $galerie->getName(),
                    'name' => $galerie->getName(),
                    'description' => $galerie->getDescription(),
                )
            );
        } elseif ($this->getGalerie($galerie->getId())) {
            $this->update(
                array(
                    'name' => $galerie->getName(),
                    'description' => $galerie->getDescription(),
                ),
                array(
                    'id' => $galerie->getId(),
                )
            );
        } else {
            throw new \Exception("cannot update row {$galerie->getId()} in table 'galerie'");
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
