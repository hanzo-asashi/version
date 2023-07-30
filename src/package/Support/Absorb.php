<?php

namespace Pinixel\Version\Package\Support;

use Carbon\Carbon;
use Pinixel\Version\Package\Version;
use PragmaRX\Version\Package\Exceptions\GitTagNotFound;

class Absorb
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var Git
     */
    protected Git $git;

    /**
     * @var Timestamp
     */
    protected Timestamp $timestamp;

    /**
     * @var Version
     */
    protected Version $version;

    /**
     * Absorb constructor.
     *
     * @param  Config
     * @param  Git
     * @param  Timestamp  $timestamp
     * @param  Version  $version
     */
    public function __construct(Config $config, Git $git, Timestamp $timestamp, Version $version)
    {
        $this->config = $config;

        $this->git = $git;

        $this->timestamp = $timestamp;

        $this->version = $version;
    }

    /**
     * Get a properly formatted version.
     *
     * @return bool
     * @throws GitTagNotFound
     */
    public function absorb(): bool
    {
        $this->absorbVersion();

        $this->absorbCommit();

        $this->absorbTimestamp();

        $this->fireEvent();

        return true;
    }

    /**
     * Absorb the version number from git.
     * @throws GitTagNotFound
     * @throws \Exception
     */
    protected function absorbVersion(): void
    {
        if (!$this->version->isVersionInAbsorbMode()) {
            return;
        }

        $version = $this->git->extractVersion(
            $this->git->getVersion()
        );

        $config = $this->config->getRoot();

        $config['current']['label'] = $version['label'][0];

        $config['current']['major'] = (int) $version['major'][0];

        $config['current']['minor'] = (int) $version['minor'][0];

        $config['current']['patch'] = (int) $version['patch'][0];

        $config['current']['prerelease'] = $version['prerelease'][0];

        $config['current']['buildmetadata'] = $version['buildmetadata'][0];

        $this->config->update($config);
    }

    /**
     * Absorb the commit from git.
     */
    protected function absorbCommit(): void
    {
        if (!$this->version->isBuildInAbsorbMode()) {
            return;
        }

        $config = $this->config->getRoot();

        $config['current']['commit'] = $this->git->getCommit() ?? null;

        $this->config->update($config);
    }

    /**
     * Absorb the commit from git.
     */
    protected function absorbTimestamp(): void
    {
        if (!$this->version->isTimestampInAbsorbMode()) {
            return;
        }

        $config = $this->config->getRoot();

        $date = Carbon::parse($this->git->getTimestamp()) ?? Carbon::now();

        $config['current']['timestamp'] = $this->timestamp->explode($date);

        $this->config->update($config);
    }

    /**
     * Fire absorbed event.
     */
    public function fireEvent(): void
    {
        event(Constants::EVENT_VERSION_ABSORBED);
    }
}
