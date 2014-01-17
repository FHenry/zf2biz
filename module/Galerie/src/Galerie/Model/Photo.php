<?php

namespace Galerie\Model;

use Custom\Model\Entity;

class Photo extends Entity
{
    public $id;
    public $id_gallery;
    public $name;
    public $filename;
    public $description;
    public $extension;
    public $created;
    public $updated;


    protected $columns = array(
        'id',
        'id_gallery',
        'name',
    	'filename',
        'description',
    	'extension',
        'created',
        'updated',
    );

    protected $updatable_columns = array(
        'id_gallery',
        'name',
        'description',
    );

    protected $primary_columns = array(
        'id',
    );


    public function getDefaultInputFilterArrays()
    {
        return include __DIR__ . '/photo.defaultinputfilter.config.php';
    }

}
