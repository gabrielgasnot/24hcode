<?php

namespace Graph;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'graph' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/graph[/:action]',
                    'defaults' => [
                        'controller' => Controller\GraphController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\GraphController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'graph' => __DIR__ . '/../view',
        ],
    ],
];