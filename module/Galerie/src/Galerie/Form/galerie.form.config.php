<?php
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
        'name' => 'submit',
        'attributes' => array(
            'type' => 'submit',
            'value' => 'Valider',
            'id' => 'submit_galerie_form',
        ),
    ),
		array(
				'name' => 'exemple',
				'type' => 'Zend\Form\Element\Select',
				'attributes' => array(
						'type' => 'select',
						'value' => '0'
				),
				'options' => array(
						'value_options' => array( 0=>$this->translate('Galerie_form_label_name'),
												  1=>$this->translate('Galerie_form_label_description')	),
						'label' => $this->translate('exemple Select'),
				),
		),
);
