<?php

namespace Pinixel\Version\Package\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Base extends Command
{
    /**
     * Display the current app version.
     *
     * @param  string  $format
     */
    public function displayAppVersion(string $format = 'full'): void
    {
        $this->info(
            config('app.name').' '.app('pinixel.version')->format($format)
        );
    }

    /**
     * Display the current app version.
     *
     * @param  string  $type
     * @param $section
     * @return bool
     */
    public function checkIfCanIncrement(string $type, $section): bool
    {
        $method = sprintf('is%sInAbsorbMode', $section = Str::studly($section));

        if (app('pinixel.version')->$method($type)) {
            $this->error(
                "{$section} is in git absorb mode, cannot be incremented"
            );

            return false;
        }

        return true;
    }
}
