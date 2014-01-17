<?php
namespace Galerie\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

use Custom\Model\Entity;

class PhotoInfoTable implements TableGatewayInterface
{

    protected $adapter;
    protected $resultSetPrototype;
    protected $sql;

    public function __construct(Adapter $adapter) {
        // Gestion de l'adaptateur
        if (!$adapter instanceof Adapter) {
            throw new Exception\RuntimeException('PhotoInfoTable does not have an valid Adapter parameter');
        }
        $this->adapter = $adapter;

        // Utilisation du patron de conception Prototype
        // pour la création des objets ResultSet
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(
            new Photo()
        );

        // Initialisation de l'outil de création de requête
        $this->sql = new Sql($this->adapter, $this->getTable());
    }


    public function getTable()
    {
        return 'photo'; // Table centrale de la requête
    }


    

    public function insert($set) {
        throw new \Exception('insert is not allowed');
    }

    public function update($set, $where = null) {
        throw new \Exception('update is not allowed');
    }

    public function delete($where) {
        throw new \Exception('delete is not allowed');
    }

    public function all()
    {
        return $this->select();
    }

    public function one($id)
    {
        if ($id === null) {
            $row = null;
        } else {
            $row = $this->select(array('photo.id' => (int) $id))->current();
        }
        if (!$row) {
            throw new \Exception("cannot get row {id: {$id}} in table 'photo'");
        }
        return $row;
    }

    public function any($id)
    {
        if ($id === null) {
            $row = null;
        } else {
            $row = $this->select(array('photo.id' => (int) $id))->current();
        }
        return $row;
    }

    public function all_by_gallery($id_gallery)
    {
        return $this->select(array('photo.id_gallery' => (int) $id_gallery));
    }




    public function count_all()
    {
        $select = $this->sql->select()->columns(array(
            'nb' => new \Zend\Db\Sql\Expression('count(photo.id)')
        ));

        // prepare and execute
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute()->current();

        return $result['nb'];
    }

    public function getPartial($start, $length, $tri, $senstri, $filtre)
    {
        $where = new Where;
        $where->like('photo.name', "%{$filtre}%");
	$where->or;
        $where->like('photo.description', "%{$filtre}%"); 

        // return $this->select($where, "{$tri} {$senstri}", $length, $start);
        return $this->select($where);
    }
}
