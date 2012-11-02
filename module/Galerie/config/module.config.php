<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Galerie\Controller\Index' =>
                'Galerie\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'galerie' => __DIR__ . '/../view',
        ),
    ),
);
