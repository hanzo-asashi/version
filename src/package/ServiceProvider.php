<?php

namespace Pinixel\Version\Package;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Pinixel\Version\Package\Console\Commands\Absorb;
use Pinixel\Version\Package\Console\Commands\Commit;
use Pinixel\Version\Package\Console\Commands\Major;
use Pinixel\Version\Package\Console\Commands\Minor;
use Pinixel\Version\Package\Console\Commands\Patch;
use Pinixel\Version\Package\Console\Commands\Show;
use Pinixel\Version\Package\Console\Commands\Timestamp;
use Pinixel\Version\Package\Console\Commands\Version as VersionCommand;
use Pinixel\Version\Package\Support\Config;
use Pinixel\Version\Package\Support\Constants;
use PragmaRX\Yaml\Package\Yaml;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = false;

    /**
     * The package config.
     *
     * @var Config
     */
    protected Config $config;

    /**
     * Console commands to be instantiated.
     *
     * @var array
     */
    protected array $commandList = [
        'pinixel.version.command' => VersionCommand::class,

        'pinixel.version.commit.command' => Commit::class,

        'pinixel.version.show.command' => Show::class,

        'pinixel.version.major.command' => Major::class,

        'pinixel.version.minor.command' => Minor::class,

        'pinixel.version.patch.command' => Patch::class,

        'pinixel.version.absorb.command' => Absorb::class,

        'pinixel.version.absorb.timestamp' => Timestamp::class,
    ];

    /**
     * Boot Service Provider.
     */
    public function boot(): void
    {
        $this->publishConfiguration();

        $this->registerBlade();
    }

    /**
     * Get the config file path.
     *
     * @return string
     */
    protected function getConfigFile(): string
    {
        return config_path('version.yml');
    }

    /**
     * Get the original config file.
     *
     * @return string
     */
    protected function getConfigFileStub(): string
    {
        return __DIR__.'/../config/version.yml';
    }

    /**
     * Load config.
     */
    protected function loadConfig(): void
    {
        $this->config = new Config(new Yaml());

        $this->config->setConfigFile($this->getConfigFile());

        $this->config->loadConfig();
    }

    /**
     * Configure config path.
     */
    protected function publishConfiguration(): void
    {
        $this->publishes([
            $this->getConfigFileStub() => $this->getConfigFile(),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerService();

        $this->loadConfig();

        $this->registerCommands();
    }

    /**
     * Register Blade directives.
     */
    protected function registerBlade(): void
    {
        Blade::directive(
            $this->config->get('blade-directive', 'version'),
            function ($format = Constants::DEFAULT_FORMAT) {
                return "<?php echo app('pinixel.version')->format($format); ?>";
            }
        );
    }

    /**
     * Register command.
     *
     * @param $name
     * @param $commandClass string
     */
    protected function registerCommand($name, string $commandClass): void
    {
        $this->app->singleton($name, function () use ($commandClass) {
            return new $commandClass();
        });

        $this->commands($name);
    }

    /**
     * Register Artisan commands.
     */
    protected function registerCommands(): void
    {
        collect($this->commandList)->each(function ($commandClass, $key) {
            $this->registerCommand($key, $commandClass);
        });
    }

    /**
     * Register service service.
     */
    protected function registerService(): void
    {
        $this->app->singleton('pinixel.version', function () {
            $version = new Version($this->config);

            $version->setConfigFileStub($this->getConfigFileStub());

            return $version;
        });
    }
}
