<?php

namespace Jetcar\Jds;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jetcar\Jds\Http\Middleware\InjectJdsAssets;

class JdsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/jds.php', 'jds');
    }

    /**
     * JDS 컴포넌트 경로와 정적 자산 배포 대상을 등록
     *
     * Composer auto-discovery가 이 Provider를 자동으로 실행하므로
     * ERP의 AppServiceProvider를 수정하지 않아도 x-button처럼 사용할 수 있음
     */
    public function boot(): void
    {
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components');

        $assets = [
            __DIR__.'/../public/dist' => public_path('vendor/jds'),
        ];

        $this->publishes($assets, 'jds-assets');
        $this->publishes($assets, 'laravel-assets');
        $this->publishes([
            __DIR__.'/../config/jds.php' => config_path('jds.php'),
        ], 'jds-config');

        $this->app['router']->pushMiddlewareToGroup('web', InjectJdsAssets::class);
    }
}
