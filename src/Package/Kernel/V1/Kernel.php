<?Php
namespace MyPlugin\CentralApp;

use MyPlugin\Apps\ApiRouteApp\App as ApiApp;
use MyPlugin\Apps\WebRouteApp\App as WebApp;

class Kernel {
    public static function handle(): void {
        $central = App::getInstance();

        // Register all modular apps
        $central->registerApp(new ApiApp());
        $central->registerApp(new WebApp());

        // Hook into WordPress to boot them
        add_action('plugins_loaded', [$central, 'bootApps']);
    }
}
