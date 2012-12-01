<?php

namespace Galerie\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Contact implements InputFilterAwareInterface
{

    public $id_user_owner;
    public $id_user_contact;

    protected $inputFilter;


    public function setInputFilter(InputFilterInterface $inputfilter)
    {
        throw \Exception('Cette entité ne permet pas à des objets externes de modifier ses filtres');
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $this->inputFilter = include __DIR__ . '/contact.defaultinputfilter.config.php';
        }
        return $this->inputFilter;
    }

}



use Zend\Stdlib\Hydrator;
$hydrator = new Hydrator\ArraySerializable();

$object = new ArrayObject(array());

$hydrator->hydrate($someData, $object);

// or, if the object has data we want as an array:
$data = $hydrator->extract($object);
