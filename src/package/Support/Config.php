<?php

namespace Pinixel\Version\Package\Support;

use Illuminate\Support\Collection;
use PragmaRX\Yaml\Package\Yaml;

class Config
{
    /**
     * The config loader.
     *
     * @var \PragmaRX\Yaml\Package\Yaml
     */
    protected ?Yaml $yaml;

    /**
     * The config file stub.
     *
     * @var string
     */
    protected string $configFileStub;

    /**
     * The config file.
     *
     * @var string
     */
    protected string $configFile;

    /**
     * Cache constructor.
     *
     * @param Yaml|null $yaml
     */
    public function __construct(Yaml $yaml)
    {
        $this->yaml = $yaml;
    }

    /**
     * Get config value.
     *
     * @param $string
     * @param  mixed|null  $default
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function get($string, mixed $default = null): mixed
    {
        return config("version.{$string}", $default);
    }

    /**
     * Get config root.
     *
     * @return array
     *
     * @internal param $string
     */
    public function getRoot(): array
    {
        return collect(config('version'))->toArray();
    }

    /**
     * Checkf it has a config value.
     *
     * @param $string
     * @param mixed|null $default
     *
     * @return bool
     */
    public function has($string): bool
    {
        return config()->has("version.{$string}");
    }

    /**
     * Set the config file stub.
     *
     * @return string
     */
    public function getConfigFileStub(): string
    {
        return $this->configFileStub;
    }

    /**
     * Load YAML file to Laravel config.
     *
     * @param $path
     *
     * @return Collection
     */
    protected function loadToLaravelConfig($path): Collection
    {
        return $this->yaml->loadToConfig($path, 'version');
    }

    /**
     * Set the config file stub.
     *
     * @param  string  $configFileStub
     */
    public function setConfigFileStub(string $configFileStub): void
    {
        $this->configFileStub = $configFileStub;
    }

    /**
     * Load package YAML configuration.
     *
     * @param null $config
     *
     * @return Collection
     */
    public function loadConfig($config = null): Collection
    {
        $config =
            !is_null($config) || !file_exists($this->configFile)
                ? $this->setConfigFile($this->getConfigFile($config))
                : $this->configFile;

        return $this->loadToLaravelConfig($config);
    }

    /**
     * Get the config file path.
     *
     * @param  string|null  $file
     *
     * @return string
     */
    public function getConfigFile(string $file = null): string
    {
        $file = $file ?: $this->configFile;

        return file_exists($file) ? $file : $this->getConfigFileStub();
    }

    /**
     * Update the config file.
     *
     * @param $config
     */
    public function update($config): void
    {
        config(['version' => $config]);

        $this->yaml->saveAsYaml($config, $this->configFile, 6, 2);
    }

    /**
     * Set the current config file.
     *
     * @param $file
     *
     * @return mixed
     */
    public function setConfigFile($file): mixed
    {
        return $this->configFile = $file;
    }
}
