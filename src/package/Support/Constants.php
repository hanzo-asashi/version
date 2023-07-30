<?php

namespace Pinixel\Version\Package\Support;

class Constants
{
    const MODE_INCREMENT = 'increment';

    const MODE_ABSORB = 'absorb';

    const VERSION_SOURCE_CONFIG = 'config';

    const VERSION_CACHE_KEY = 'version';

    const COMMIT_CACHE_KEY = 'commit';

    const DEFAULT_FORMAT = 'full';

    const VERSION_SOURCE_GIT_LOCAL = 'git-local';

    const VERSION_SOURCE_GIT_REMOTE = 'git-remote';

    const EVENT_VERSION_ABSORBED = 'pinixel:version:events:version-absorbed';

    const EVENT_COMMIT_INCREMENTED = 'pinixel:version:events:commit-incremented';

    const EVENT_MAJOR_INCREMENTED = 'pinixel:version:events:major-incremented';

    const EVENT_MINOR_INCREMENTED = 'pinixel:version:events:minor-incremented';

    const EVENT_PATCH_INCREMENTED = 'pinixel:version:events:patch-incremented';

    const EVENT_TIMESTAMP_UPDATED = 'pinixel:version:events:timestamp-updated';
    const BUILD_CACHE_KEY = 'build';
}
