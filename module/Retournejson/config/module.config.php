<?php
namespace Retournejson;

use Zend\Router\Http\Segment;

use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'controllers' => [
        'factories' => [
            Controller\RetournejsonController::class => InvokableFactory::class,
        ],
    ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'retournejson' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/retournejson[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\RetournejsonController::class,
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