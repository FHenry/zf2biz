<?php

namespace Galerie\Model;

use Custom\Model\Entity;

class GalerieInfo extends Entity
{
    public $id;
    public $name;
    public $description;
    public $username;
    public $nb;


    protected $columns = array(
        'id',
        'name',
        'description',
        'username',
        'nb',
    );

    protected $updatable_columns = array(
    );

    protected $primary_columns = array(
    );

}
