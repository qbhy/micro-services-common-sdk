<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 上午17:04
 */

namespace Qbhy\MicroServicesCommonSdk;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application;
use Qbhy\MicroServicesCommonSdk\Exceptions\UndefinedAppException;
use Qbhy\MicroServicesCommonSdk\JwtParser\AuthHeaders;
use Qbhy\MicroServicesCommonSdk\JwtParser\InputSource;
use Qbhy\MicroServicesCommonSdk\JwtParser\LumenRouteParams;
use Qbhy\MicroServicesCommonSdk\JwtParser\Parser;
use Qbhy\MicroServicesCommonSdk\JwtParser\QueryString;
use Qbhy\MicroServicesCommonSdk\Services\ExampleService;
use Qbhy\MicroServicesCommonSdk\Services\PaymentService\PaymentService;
use Qbhy\MicroServicesCommonSdk\Services\UserService\Auth\UserServiceGuard;
use Qbhy\MicroServicesCommonSdk\Services\UserService\Auth\UserServiceUserProvider;
use Qbhy\MicroServicesCommonSdk\Services\UserService\UserService;
use Qbhy\MicroServicesCommonSdk\Services\WechatService\WechatService;
use Qbhy\SimpleJwt\Encoders\Base64UrlSafeEncoder;
use Qbhy\SimpleJwt\EncryptAdapters\PasswordHashEncrypter;
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

    /**
     * @return Config
     * @throws Exceptions\UndefinedAppException
     */
    protected function getConfig()
    {
        $config = new Config(config('micro-services'));
        $request = $this->app->make(Request::class);
        $use = $request->header($config->get('app_header', 'App'),
            $request->header('aid', $config->get('use', 'default')));
        $this->config = $config->use($use);

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
            /** @var Config $config */
            $config = $this->getConfig();
            return new JWTManager($config->getAppConfig());
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
            UserService::class,
            PaymentService::class,
            WechatService::class,
            ExampleService::class,
        ];

        foreach ($services as $service) {
            $this->app->singleton($service, function () use ($service) {
                return new $service($this->app->make(Client::class));
            });
        }

        $this->app->make('auth')->provider('user_service', function ($app, $config) {
            return new UserServiceUserProvider($config['model']);
        });

        $this->app->make('auth')->extend('user_service', function ($app, $name, $config) {
            return new  UserServiceGuard(
                $this->app->make(JWTManager::class),
                $this->app->make('auth')->createUserProvider($config['provider']),
                $this->app->make(Request::class)
            );
        });
    }

}