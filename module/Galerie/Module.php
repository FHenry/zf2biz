<?php

namespace Galerie;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;


use Galerie\Model\GalerieTable;
use Galerie\Model\GalerieInfoTable;
use Galerie\Form\GalerieForm;
use Galerie\Export\GalerieWorkbook;
use Galerie\Mail\MailSender;

use Custom\View\Helper\Format;


class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface,
    ServiceProviderInterface,
    ViewHelperProviderInterface
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
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translator, 'val');
    }

    public function getServiceConfig()
    {
        $config = $this->getConfig();
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
                'Galerie\Export\GalerieWorkbook' => function ($sm) {
                    return new GalerieWorkbook;
                },
                'Galerie\Mail\MailSender' => function($sm) use ($config) {
                    $result = new MailSender;
                    $result::initialize($config['mail']);
                    return $result;
                },
            ),
        );
    }

    public function getViewHelperConfig() 
    { 
        return array( 
            'factories' => array( 
                'format' => function($sm) { 
                    return new Format;
                }, 
            ), 
        ); 
    } 
    
    
    
    
}
