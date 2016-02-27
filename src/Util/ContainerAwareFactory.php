<?php

namespace Util;

use \Pimple\Container;

class ContainerAwareFactory
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

}
