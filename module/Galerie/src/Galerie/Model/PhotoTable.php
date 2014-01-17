<?php
namespace Galerie\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

use Custom\Model\Entity;
use Custom\Model\EntityManager;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;

class PhotoTable extends EntityManager
{

    public $table = 'photo';
    
    public function __construct(Adapter $adapter) {
                parent::__construct($adapter, new Photo());
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

    /* Specific */

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
}
