<?php

namespace MySilex;

use Silex\Application;
use Silex\Provider\ExceptionHandlerServiceProvider;
use Silex\Provider\RoutingServiceProvider;
//use Silex\Provider\HttpKernelServiceProvider;

/**
 * Created by PhpStorm.
 * User: kekst
 * Date: 12.11.2018
 * Time: 22:38
 */
class MyApplication extends Application
{

    /**
     * Instantiate a new Application.
     *
     * Objects and parameters can be passed as argument to the constructor.
     *
     * @param array $values the parameters or objects
     */
    public function __construct(array $values = [])
    {
        parent::__construct();

        $this['request.http_port'] = 80;
        $this['request.https_port'] = 443;
        $this['debug'] = false;
        $this['charset'] = 'UTF-8';
        $this['logger'] = null;

        $this->register(new MyHttpKernelServiceProvider());
        $this->register(new RoutingServiceProvider());
        $this->register(new ExceptionHandlerServiceProvider());

        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }
    }

}