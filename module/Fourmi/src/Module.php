<?php
namespace Fourmi;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    // Add this method:
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\Jwt_TokenTable::class => function($container) {
                    $tableGateway = $container->get(Model\Jwt_TokenTableGateway::class);
                    return new Model\Jwt_TokenTable($tableGateway);
                },
                Model\Jwt_TokenTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Jwt_Token());
                    return new TableGateway('jwt_token', $dbAdapter, null, $resultSetPrototype);
                },
                Tools\CicadaTable::class => function($container) {
                    $tableGateway = $container->get(Tools\CicadaTableGateway::class);
                    return new Tools\CicadaTable($tableGateway);
                },
                Tools\CicadaTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tools\CicadaMemory());
                    return new TableGateway('cicada_memory', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\FourmiController::class => function($container) {
                    return new Controller\FourmiController(
                        $container->get(Model\Jwt_TokenTable::class),
                        $container->get(Tools\CicadaTable::class)
                    );
                },
            ],
        ];
    }
}