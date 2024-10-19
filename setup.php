<?php

if (! defined('LARAVEL_START')) {
    exit(0);
}

use App\Services\Core\Plugin\Bases\BasePluginSetup;

class PluginSetup extends BasePluginSetup
{
    public function install(): void
    {}

    public function activate(): void
    {}

    public function deactivate(): void
    {}

    public function uninstall(): void
    {}
};
