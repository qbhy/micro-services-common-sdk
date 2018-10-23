<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 上午17:04
 */

namespace Qbhy\MicroServicesCommonSdk;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Container\Container;
use Laravel\Lumen\Application;
use Qbhy\MicroServicesCommonSdk\JwtParser\AuthHeaders;
use Qbhy\MicroServicesCommonSdk\JwtParser\InputSource;
use Qbhy\MicroServicesCommonSdk\JwtParser\LumenRouteParams;
use Qbhy\MicroServicesCommonSdk\JwtParser\Parser;
use Qbhy\MicroServicesCommonSdk\JwtParser\QueryString;
use Qbhy\MicroServicesCommonSdk\Services\Auth\AuthService;
use Qbhy\MicroServicesCommonSdk\Services\Auth\ExampleService;
use Qbhy\SimpleJwt\Encoders\Base64UrlSafeEncoder;
use Qbhy\SimpleJwt\Interfaces\Encoder;
use Qbhy\SimpleJwt\JWTManager;

/**
 * Class ServiceProvider
 *
 * @property-read Application $app
 * @package Qbhy\MicroServicesCommonSdk
 */
class ServiceProvider extends BaseServiceProvider
{

    /**
     * @var Config
     */
    protected $config;

    protected function getConfig()
    {
        if (null === $this->config) {
            $config       = config('micro-services');
            $this->config = new Config($config);
        }

        return $this->config;
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/micro-services.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([$source => base_path('config/micro-services.php')], 'micro-services');
        }

        $this->mergeConfigFrom($source, 'micro-services');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();

        $this->app->singleton(Config::class, function () {
            return $this->getConfig();
        });

        $this->registerCommonModule();

        $this->registerServices();
    }

    protected function registerCommonModule()
    {
        $this->app->singleton(Encoder::class, function () {
            return new Base64UrlSafeEncoder();
        });

        $this->app->singleton(JWTManager::class, function () {
            /** @var Base64UrlSafeEncoder $encoder */
            $encoder = $this->app->make(Encoder::class);
            /** @var Config $config */
            $config = $this->app->make(Config::class);
            return new JWTManager(new ClientEncrypt($config), $encoder);
        });

        $this->app->singleton(Parser::class, function () {
            return new Parser(
                $this->app->make(Request::class),
                [
                    new LumenRouteParams(),
                    new AuthHeaders(),
                    new InputSource(),
                    new QueryString(),
                ]
            );
        });

    }


    protected function registerServices()
    {
        $this->app->singleton(Client::class, function () {
            return new Client($this->app->make(Config::class));
        });

        $services = [
            AuthService::class,
            ExampleService::class,
        ];

        foreach ($services as $service) {
            $this->app->singleton($service, function () use ($service) {
                return new $service($this->app->make(Client::class));
            });
        }
    }

}