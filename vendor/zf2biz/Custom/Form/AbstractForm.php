<?php

namespace Custom\Form;

use Zend\Form\Form;

use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\Translator;


abstract class AbstractForm extends Form implements TranslatorAwareInterface
{

    private $_translator = null;
    private $_textDomain = 'default';
    private $_translator_enabled = false; 


    abstract public function initialize();




    public function translate($k)
    {
        if ($this->_translator && $this->_translator_enabled) {
            return $this->_translator->translate($k, $this->_textDomain);
        }
        return $k;
    }





    protected function addElements(array $paramsArray)
    {
        foreach($paramsArray as $params) {
            $this->add($params);
        }
    }


    protected function addElement($name, $type='text', $label=null, array $attributes=array(), array $options=array())
    {
        if ($type) {
            $attributes['type'] = $type;
        }
        if ($label) {
            $options['label'] = $label;
        }
        $params = array('name' => $name);
        if ($attributes) {
            $params['attributes'] = $attributes;
        }
        if ($options) {
            $params['options'] = $options;
        }
        $this->add($params);
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


    protected function setMethod($method='post')
    {
        $this->setAttribute('method', $method);
    }

}
