<?php
use Doctrine\DBAL\Types\Type;
return array(
    array(
        'name' => 'id',
        'attributes' => array(
            'type' => 'hidden',
        ),
    ),
    array(
        'name' => 'name',
        'attributes' => array(
            'type' => 'text',
        ),
        'options' => array(
            'label' => $this->translate('Galerie_form_label_name'),
        ),
    ),
    array(
        'name' => 'description',
        'attributes' => array(
            'type' => 'textarea',
        ),
        'options' => array(
            'label' => $this->translate('Galerie_form_label_description'),
        ),
    ),
    array(
        'name' => 'fileUpload',
        'type' => 'Zend\Form\Element\File',
        'attributes' => array(
            'multiple' => true,
        ),
        'options' => array(
            'label' => $this->translate('Galerie_form_image_sender'),
        ),
    ),
    array(
        'name' => 'submit',
        'attributes' => array(
            'type' => 'submit',
            'value' => 'Valider',
            'id' => 'submit_galerie_form',
        ),
    ),
);
