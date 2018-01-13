<?php
namespace Listes;

use Zend\Router\Http\Segment;
// Remove this:
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    // And remove the entire "controllers" section here:
    'controllers' => [
        'factories' => [
            Controller\ListesController::class => InvokableFactory::class,
        ],
    ],
    
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'listes' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/listes[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ListesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            'listes' => __DIR__ . '/../view',
        ],
    ],
];