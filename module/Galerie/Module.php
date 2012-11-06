<?php

namespace Galerie;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;

use Galerie\Model\GalerieArrayTable;
use Galerie\Model\GalerieATable;
use Galerie\Model\GalerieBTable;
use Galerie\Model\GalerieCTable;

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
        $e->getApplication()->getServiceManager()->get('translator'); 
    } 

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Galerie\Model\GalerieArrayTable' => function($sm) {
                    return new GalerieArrayTable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
                'Galerie\Model\GalerieATable' => function($sm) {
                    return new GalerieATable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
                'Galerie\Model\GalerieBTable' => function($sm) {
                    return new GalerieBTable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
                'Galerie\Model\GalerieCTable' => function($sm) {
                    return new GalerieCTable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
            ),
        );
    }
}
