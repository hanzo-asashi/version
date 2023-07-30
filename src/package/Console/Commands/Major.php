<?php

namespace Pinixel\Version\Package\Console\Commands;

class Major extends Base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:major';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment app major version';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->checkIfCanIncrement('current', 'version')) {
            $number = app('pinixel.version')->incrementMajor();

            $this->info("New major version: {$number}");

            $this->displayAppVersion();
        }
    }
}
