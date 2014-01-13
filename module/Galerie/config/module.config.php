<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Galerie\Controller\Index' =>
                'Galerie\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_map' => array( 
            'galerie/index/index' =>
			__DIR__ . '/../view/galerie/index/index.phtml', 
            'galerie/index/edit' =>
			__DIR__ . '/../view/galerie/index/edit.phtml', 
            'galerie/index/del' =>
			__DIR__ . '/../view/galerie/index/del.phtml', 
            'galerie/index/view' =>
			__DIR__ . '/../view/galerie/index/view.phtml', 
        ), 
        'template_path_stack' => array(
            'galerie' => __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'galerie' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/galeries',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Galerie\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'verb' => 'get',
                'may_terminate' => true,
                'child_routes' => array(
                    'add' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/ajout',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                        'verb' => 'get,post',
                    ),
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/editer/:id',
                            'constraints' => array(
                                'id' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                        'verb' => 'get,post',
                    ),
                    'del' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/supprimer/:id',
                            'constraints' => array(
                                'id' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'del',
                            ),
                        ),
                        'verb' => 'get,post',
                    ),
                    'view' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/voir/:id',
                            'constraints' => array(
                                'id' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'view',
                            ),
                        ),
                    ),
                    'add_or_edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/ajouter_editer/[:id]',
                            'constraints' => array(
                                'id' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'edit',
                                'id' => null,
                            ),
                        ),
                        'verb' => 'get,post',
                    ),
                   /* 'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),*/
                ),
            ),
        ),
    ),
    'service_manager' => array( 
        'factories' => array( 
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory', 
        ), 
    ), 
    'translator' => array( 
        'locale' => 'fr_FR', 
        'translation_file_patterns' => array( 
            array( 
                'type'     => 'gettext', 
                'base_dir' => __DIR__ . '/../language', 
                'pattern'  => '%s.mo', 
                'text_domain'  => 'galerie', 
            ), 
        ), 
    ), 
);
