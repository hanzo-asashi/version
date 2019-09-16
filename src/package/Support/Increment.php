<?php

namespace PragmaRX\Version\Package\Support;

use Illuminate\Support\Arr;

class Increment
{
    protected $config;

    /**
     * Cache constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get a properly formatted version.
     *
     * @param \Closure $incrementer
     * @param $returnKey
     *
     * @return string
     */
    public function increment(\Closure $incrementer, $returnKey)
    {
        $config = $incrementer($this->config->getRoot());

        $this->config->update($config);

        return Arr::get($config, $returnKey);
    }

    /**
     * Increment the commit number.
     *
     * @param null $by
     *
     * @return int
     *
     * @internal param null $increment
     */
    public function incrementCommit($by = null)
    {
        return $this->increment(function ($config) use ($by) {
            $increment_by = $by ?: $config['commit']['increment-by'];

            $config['current']['commit'] = $this->incrementHex($config['current']['commit'], $increment_by);

            return $config;
        }, 'commit.number');
    }

    /**
     * Increment major version.
     *
     * @return int
     */
    public function incrementMajor()
    {
        return $this->increment(function ($config) {
            $config['current']['major']++;

            $config['current']['minor'] = 0;

            $config['current']['patch'] = 0;

            return $config;
        }, 'current.major');
    }

    /**
     * Increment minor version.
     *
     * @return int
     */
    public function incrementMinor()
    {
        return $this->increment(function ($config) {
            $config['current']['minor']++;

            $config['current']['patch'] = 0;

            return $config;
        }, 'current.minor');
    }

    /**
     * Increment patch.
     *
     * @return int
     */
    public function incrementPatch()
    {
        return $this->increment(function ($config) {
            $config['current']['patch']++;

            return $config;
        }, 'current.patch');
    }

    public function incrementHex($hex, $by = 1)
    {
        return dechex(hexdec($hex) + $by);
    }
}
