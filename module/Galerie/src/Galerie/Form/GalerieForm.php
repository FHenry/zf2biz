<?php

namespace Galerie\Form;

use Zend\Form\Form;

class GalerieForm extends Form
{

    public function __construct($name=null)
    {
        parent::__construct('galerie');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Name',
            )
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Description',
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Valider',
                'id' => 'submit_galerie_form',
            ),
            'options' => array(
                'label' => 'Description',
            )
        ));
    }

}
