<?php

namespace Galerie;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;

use Zend\Stdlib\Hydrator\ClassMethods as HydratorClassMethods;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream as LogStream;

use Galerie\Model\GalerieTable;
use Galerie\Model\GalerieInfoTable;
use Galerie\Model\Contact;

use Galerie\Form\GalerieForm;
use Galerie\Export\GalerieWorkbook;
use Galerie\Mail\MailSender;

use Custom\View\Helper\Format;
use Custom\Model\PairManager;


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
                'Galerie\Model\GaleriePairTable' => function($sm) {
                    return new PairManager(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        'gallery'
                    );
                },
                'Galerie\Model\ContactTable' => function($sm) {
                    return new TableGateway(
                        'contact',
                        Zend\Db\TableGateway\TableGateway,
                        null,
                        new HydratingResultSet(
                            new HydratorClassMethods,
                            new Contact
                        )
                    );
                },
                'Zend\Log' => function ($sm) {
                    $log = new Logger();
                    $writer = new LogStream('/var/git/zf2biz/galerie/data/logs/info.log');
                    $log->addWriter($writer);
                    return $log;
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
