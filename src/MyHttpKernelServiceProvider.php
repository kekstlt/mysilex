<?php
/**
 * Created by PhpStorm.
 * User: kekst
 * Date: 12.11.2018
 * Time: 22:43
 */

namespace MySilex;

use Silex\Provider\HttpKernelServiceProvider;
use Pimple\Container;
use Silex\AppArgumentValueResolver;
use Silex\CallbackResolver;
use Silex\ControllerResolver;
//use MySilex\MyControllerResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as SfControllerResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
//use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel;


class MyHttpKernelServiceProvider extends HttpKernelServiceProvider
{

    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['resolver'] = function ($app) {
            if (Kernel::VERSION_ID >= 30100) {
                return new SfControllerResolver($app['logger']);
            }

            return new ControllerResolver($app, $app['logger']);
        };

        if (Kernel::VERSION_ID >= 30100) {
            $app['argument_metadata_factory'] = function ($app) {
                return new ArgumentMetadataFactory();
            };
            $app['argument_value_resolvers'] = function ($app) {
                if (Kernel::VERSION_ID < 30200) {
                    return [
                        new AppArgumentValueResolver($app),
                        new RequestAttributeValueResolver(),
                        new RequestValueResolver(),
                        new DefaultValueResolver(),
                        new VariadicValueResolver(),
                    ];
                }

                return array_merge([new AppArgumentValueResolver($app)], ArgumentResolver::getDefaultArgumentValueResolvers());
            };
        }

        $app['argument_resolver'] = function ($app) {
            if (Kernel::VERSION_ID >= 30100) {
                return new ArgumentResolver($app['argument_metadata_factory'], $app['argument_value_resolvers']);
            }
        };

        $app['kernel'] = function ($app) {
            return new MyHttpKernel($app);
        };

        $app['request_stack'] = function () {
            return new RequestStack();
        };

        $app['dispatcher'] = function () {
            return new EventDispatcher();
        };

        $app['callback_resolver'] = function ($app) {
            return new CallbackResolver($app);
        };
    }

}