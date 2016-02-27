<?php

$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'sqlite');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array(
    'classname' => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
    'dsn' => 'sqlite:storage/db.sqlite',
    'user' => NULL,
    'password' => '',
    'attributes' =>
    array(
        'ATTR_EMULATE_PREPARES' => false,
    ),
));
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');
