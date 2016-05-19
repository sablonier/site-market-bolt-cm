<?php

namespace Bolt\Extension\Bolt\MarketPlace\Provider;

use Bolt\Extension\Bolt\MarketPlace\Action;
use Bolt\Extension\Bolt\MarketPlace\Controller;
use Bolt\Extension\Bolt\MarketPlace\Service;
use Bolt\Extension\Bolt\MarketPlace\Twig;
use Pimple as Container;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Market Place Service Provider.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class MarketPlaceServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function (\Twig_Environment $twig) {
                    /** @var \Twig_Environment $twig */
                    $twig->addExtension(new Twig\Extension());

                    return $twig;
                }
            )
        );

        $app['marketplace.controller.frontend'] = $app->share(
            function () {
                return new Controller\Frontend();
            }
        );

        $app['marketplace.actions'] = $app->share(
            function ($app) {
                $container = new Container([
                    'admin'             => $app->share(function () use ($app) { return new Action\Admin($app); }),
                    'feed'              => $app->share(function () use ($app) { return new Action\Feed($app); }),
                    'home'              => $app->share(function () use ($app) { return new Action\Home($app); }),
                    'hook'              => $app->share(function () use ($app) { return new Action\Hook($app); }),
                    'json_search'       => $app->share(function () use ($app) { return new Action\JsonSearch($app); }),
                    'listing'           => $app->share(function () use ($app) { return new Action\Listing($app); }),
                    'list_packages'     => $app->share(function () use ($app) { return new Action\ListPackages($app); }),
                    'package_disable'   => $app->share(function () use ($app) { return new Action\DisablePackage($app); }),
                    'package_edit'      => $app->share(function () use ($app) { return new Action\EditPackage($app); }),
                    'package_info'      => $app->share(function () use ($app) { return new Action\PackageInfo($app); }),
                    'package_stats'     => $app->share(function () use ($app) { return new Action\PackageStats($app); }),
                    'package_stats_api' => $app->share(function () use ($app) { return new Action\PackageStatsApiDownloads($app); }),
                    'package_star'      => $app->share(function () use ($app) { return new Action\StarPackage($app); }),
                    'package_update'    => $app->share(function () use ($app) { return new Action\UpdatePackage($app); }),
                    'package_view'      => $app->share(function () use ($app) { return new Action\ViewPackage($app); }),
                    'ping'              => $app->share(function () use ($app) { return new Action\Ping($app); }),
                    'profile'           => $app->share(function () use ($app) { return new Action\Profile($app); }),
                    'releases'          => $app->share(function () use ($app) { return new Action\Releases($app); }),
                    'search'            => $app->share(function () use ($app) { return new Action\Search($app); }),
                    'stat'              => $app->share(function () use ($app) { return new Action\Stat($app); }),
                    'submit'            => $app->share(function () use ($app) { return new Action\Submit($app); }),
                    'submitted'         => $app->share(function () use ($app) { return new Action\Submitted($app); }),
                    'test_build_check'  => $app->share(function () use ($app) { return new Action\TestBuildCheck($app); }),
                    'test_extension'    => $app->share(function () use ($app) { return new Action\TestExtension($app); }),
                    'tests'             => $app->share(function () use ($app) { return new Action\Tests($app); }),
                    'v3_ready'          => $app->share(function () use ($app) { return new Action\V3Ready($app); }),
                ]);

                return $container;
            }
        );

        $app['marketplace.services'] = $app->share(
            function ($app) {
                $container = new Container([
                    'bolt_themes'     => $app->share(function () use ($app) { return new Service\BoltThemes(); }),
                    'email'           => $app->share(function () use ($app) { return new Service\Email(); }),
                    'mail'            => $app->share(function () use ($app) { return new Service\MailService(); }),
                    'package_manager' => $app->share(function () use ($app) { return new Service\PackageManager(); }),
                ]);

                return $container;
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function boot(Application $app)
    {
    }
}
