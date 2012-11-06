<?php
namespace Galerie\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;


class GalerieATable extends AbstractTableGateway
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
            new GalerieA()
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

/*    public saveGalerie(GalerieA $galerie)
    {
        if ($galerie->id === null) {
            $this->insert(
                array(
                    'name' => $galerie->name,
                    'description' => $galerie->description,
                )
            );
        } elseif ($this->getGalerie($galerie->id)) {
            $this->update(
                array(
                    'name' => $galerie->name,
                    'description' => $galerie->description,
                ),
                array(
                    'id' => $galerie->id,
                )
            );
        } else {
            throw new \Exception("cannot update row $galerie->id in table 'galerie'");
        }
    }
*/
    public function deleteGalerie($id)
    {
        $this->delete(array(
                'id' => (int) $id
            )
        );
    }

}
