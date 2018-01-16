<?php
namespace Hightgraph;

use Zend\Router\Http\Segment;

use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'controllers' => [
        'factories' => [
            Controller\HightgraphController::class => InvokableFactory::class,
        ],
    ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'hightgraph' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/hightgraph[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\HightgraphController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
/*
     'controllers' => [
        'factories' => [
            Controller\HightgraphController::class => InvokableFactory::class,
        ],
    ],
    */
    'view_manager' => [
        'template_path_stack' => [
            'hightgraph' => __DIR__ . '/../view',
        ],
    ],
    
];