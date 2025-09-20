#!/usr/bin/env php
<?php
// Conditionally run Laravel Pail only when the pcntl extension is available.
// On Windows, pcntl is not available, so we skip running Pail but keep the dev stack alive.

if (!function_exists('pcntl_fork')) {
    fwrite(STDERR, "[logs] Skipping 'php artisan pail' because the pcntl extension is unavailable on this platform.\n");
    exit(0);
}

$cmd = 'php artisan pail --timeout=0';

// Run the command and forward output; propagate exit code.
passthru($cmd, $code);
exit($code);
