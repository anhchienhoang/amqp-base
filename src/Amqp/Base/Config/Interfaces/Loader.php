<?php
namespace Amqp\Base\Config\Interfaces;

use Amqp\Base\Config\Exception;

interface Loader
{
    /**
     * Load configuration
     *
     * @return array
     *
     * @throws Exception
     */
    public function load();
}