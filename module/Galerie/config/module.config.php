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
);
