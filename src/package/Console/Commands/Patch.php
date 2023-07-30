<?php

namespace Pinixel\Version\Package\Console\Commands;

class Patch extends Base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:patch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment app patch version';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->checkIfCanIncrement('current', 'version')) {
            $number = app('pinixel.version')->incrementPatch();

            $this->info("New patch: {$number}");

            $this->displayAppVersion();
        }
    }
}
