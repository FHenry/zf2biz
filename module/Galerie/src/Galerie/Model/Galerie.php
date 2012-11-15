<?php

namespace Galerie\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

use Custom\Model\Entity;

class Galerie extends Entity implements InputFilterAwareInterface
{
    public $id;
    public $id_user;
    public $name;
    public $description;
    public $created;
    public $updated;

    protected $inputFilter;


    protected $columns = array(
        'id',
        'id_user',
        'name',
        'description',
        'created',
        'updated',
    );

    protected $updatable_columns = array(
        'id_user',
        'name',
        'description',
    );

    protected $primary_columns = array(
        'id',
    );

    public function getArrayCopy()
    {
        return $this->toArray();
    }

    public function setInputFilter(InputFilterInterface $inputfilter)
    {
        throw new \Exception("This entity does not allow to set Input Filter");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter;
            $factory = new InputFactory;

            $inputFilter->add($factory->createInput(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 32,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'description',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;

    }

}
