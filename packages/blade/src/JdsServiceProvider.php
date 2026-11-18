<?php

namespace Jetcar\Jds;

use Illuminate\Support\ServiceProvider;
use Jetcar\Jds\Components\Button\Button;
use Jetcar\Jds\Components\Separator\Separator;

final class JdsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'jds');
        $this->app->make('blade.compiler')->component(Button::class, 'jds::button');
        $this->app->make('blade.compiler')->component(Separator::class, 'jds::separator');
    }
}
