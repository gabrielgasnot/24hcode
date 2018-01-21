<?php
namespace Fourmi;

use Zend\Router\Http\Segment;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    /*
    'controllers' => [
        'factories' => [
            Controller\FourmiController::class => InvokableFactory::class,
        ],
    ],
    */
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'fourmi' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/fourmi[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\FourmiController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'cicada' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/fourmi/cicada',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\FourmiController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
/*
        'template_path_stack' => [
            'retournejson' => __DIR__ . '/../view',
        ],
*/
        'strategies' => [
            'ViewJsonStrategy',
        ],           
    ],
    
];