<?php

namespace Galerie;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;


use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;


use Galerie\Model\GalerieTable;
use Galerie\Model\GalerieInfoTable;
use Galerie\Form\GalerieForm;


class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface,
    ServiceProviderInterface
{

    public function getAutoloaderConfig() 
    { 
        return array( 
            'Zend\Loader\ClassMapAutoloader' => array( 
                __DIR__ . '/autoload_classmap.php', 
            ), 
            'Zend\Loader\StandardAutoloader' => array( 
                'namespaces' => array( 
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__, 
                ), 
            ), 
        ); 
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        
        \Locale::setDefault('fr_FR');
        $translatorValidator = new \Zend\Mvc\I18n\Translator();
        $translatorValidator->addTranslationFile(
        		'phpArray',
        		'/home/zendformation/workspace/galerie/module/Galerie/language/val/Zend_Validate_fr_FR.php'
        );
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translatorValidator);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Galerie\Model\GalerieTable' => function($sm) {
                    return new GalerieTable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
                'Galerie\Model\GalerieInfoTable' => function($sm) {
                    return new GalerieInfoTable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
                'Galerie\Form\GalerieForm' => function($sm) {
                    $result = new GalerieForm;
                    $result->setTranslator($sm->get('translator'), 'galerie');
                    $result->initialize();
                    return $result;
                },
            ),
        );
    }
}
