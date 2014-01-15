<?php

namespace Galerie\Form;

use Zend\Form\Form;

use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\Translator;


class GalerieForm extends Form implements TranslatorAwareInterface
{

    private $_translator;
    private $_textDomain = 'galerie';
    private $_translator_enabled = true; 


    public function initialize()
    {
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
            	'maxlength' => 32,
            ),
            'options' => array(
                'label' => $this->translate('Galerie_form_label_name'),
            )
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => $this->translate('Galerie_form_label_description'),
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Valider',
                'id' => 'submit_galerie_form',
            ),
        ));
    }




    public function translate($k)
    {
        if ($this->_translator && $this->_translator_enabled) {
            return $this->_translator->translate($k, $this->_textDomain);
        }
        return $k . '(Non traduit)';
    }


    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        $this->_translator = $translator;
		$this->_textDomain = $textDomain;
    }

    public function getTranslator()
    {
        return $this->_translator;
    }

    public function hasTranslator()
    {
        return $this->_translator !== null;
    }

    public function setTranslatorEnabled($enabled = true)
    {
        $this->_translator_enabled = $enabed;
    }

    public function isTranslatorEnabled()
    {
        return $this->_translator_enabled;
    }

    public function setTranslatorTextDomain($textDomain = 'default')
    {
		$this->_textDomain = $textDomain;
    }

    public function getTranslatorTextDomain()
    {
        return $this->_textDomain;
    }

}
