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
            ),
        );
    }
}
