<?php

namespace Niden\Providers;

use function register_shutdown_function;
use function set_error_handler;
use Niden\ErrorHandler;
use Niden\Logger;
use Phalcon\Config;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use function set_exception_handler;

class ErrorHandlerProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @param DiInterface $container
     */
    public function register(DiInterface $container)
    {
        /** @var Logger $logger */
        $logger  = $container->getShared('logger');
        /** @var Config $registry */
        $config  = $container->getShared('config');

        date_default_timezone_set($config->path('app.timezone'));
        ini_set('display_errors', 'Off');
        error_reporting(E_ALL);

        $handler = new ErrorHandler($logger, $config);
        set_error_handler([$handler, 'handle']);
        register_shutdown_function([$handler, 'shutdown']);
    }
}
