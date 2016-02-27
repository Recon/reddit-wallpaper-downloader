<?php

namespace Commands;

use \Pimple\Container;
use \Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{

    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
    }

}
