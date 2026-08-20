<?php

namespace Jetcar\Jds;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jetcar\Jds\Console\InstallCommand;

final class JdsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::anonymousComponentPath($this->packagePath('resources/views/components'));
        Blade::directive('jdsStyles', static fn (): string => '<?php echo \\Jetcar\\Jds\\Support\\JdsAssets::styles(); ?>');
        Blade::directive('jdsScripts', static fn (): string => '<?php echo \\Jetcar\\Jds\\Support\\JdsAssets::scripts(); ?>');

        $this->loadRoutesFrom($this->packagePath('routes/web.php'));

        $this->publishes([
            $this->packagePath('public/dist') => public_path('vendor/jds'),
        ], 'jds-assets');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    private function packagePath(string $path): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
