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
use Zend\Log\Writer\FirePhp as LogFirePhp;
use \Zend\Log\Writer\FirePhp\FirePhpBridge;

use Galerie\Model\GalerieTable;
use Galerie\Model\GalerieInfoTable;
use Galerie\Model\GalerieInfoRssTable;
use Galerie\Model\PhotoTable;
use Galerie\Model\Contact;

use Galerie\Form\GalerieForm;
use Galerie\Export\GalerieWorkbook;
use Galerie\Mail\MailSender;
use Galerie\Graph\MyPie;


use Custom\View\Helper\Format;
use Custom\Model\PairManager;


require_once '/usr/share/php/FirePHPCore/FirePHP.class.php';

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
        
        \Locale::setDefault('fr_FR');
        $translatorValidator = new \Zend\Mvc\I18n\Translator();
        $translatorValidator->addTranslationFile(
        		'phpArray',
        		'/home/zendformation/workspace/galerie/module/Galerie/language/val/Zend_Validate_fr_FR.php'
        );
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translatorValidator);
       // \Zend\Validator\AbstractValidator::setDefaultTranslator($translator, 'val');
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
                'Galerie\Model\GalerieInfoRssTable' => function($sm) {
                    return new GalerieInfoRssTable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
                'Galerie\Form\GalerieForm' => function($sm) {
                    $result = new GalerieForm;
                    $result->setTranslator($sm->get('translator'), 'galerie');
                    $result->initialize();
                    return $result;
                },
                'Galerie\Model\PhotoTable' => function($sm) {
                    return new PhotoTable(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
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
                    $stream_writer = new LogStream('/home/zendformation/workspace/galerie/data/logs/info.log');
                    $log->addWriter($stream_writer);
                    $fire_writer = new LogFirePhp(new FirePhpBridge(new \FirePHP()));
                    $log->addWriter($fire_writer);
                    return $log;
                },
                'Galerie\Graph\MyPie' => function($sm) {
                    $result = new MyPie;
                    $result->setTranslator($sm->get('translator'), 'galerie');
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
