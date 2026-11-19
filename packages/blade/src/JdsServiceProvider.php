<?php

namespace Jetcar\Jds;

use Illuminate\Support\ServiceProvider;
use Jetcar\Jds\Components\Button\Button;
use Jetcar\Jds\Components\Card\Card;
use Jetcar\Jds\Components\Checkbox\Checkbox;
use Jetcar\Jds\Components\Input\Input;
use Jetcar\Jds\Components\Label\Label;
use Jetcar\Jds\Components\Separator\Separator;
use Jetcar\Jds\Components\Surface\Surface;
use Jetcar\Jds\Components\TextArea\TextArea;

final class JdsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'jds');
        $this->app->make('blade.compiler')->components([
            Button::class => 'jds::button',
            Card::class => 'jds::card',
            'jds::components.card.header' => 'jds::card.header',
            'jds::components.card.title' => 'jds::card.title',
            'jds::components.card.description' => 'jds::card.description',
            'jds::components.card.content' => 'jds::card.content',
            'jds::components.card.footer' => 'jds::card.footer',
            Checkbox::class => 'jds::checkbox',
            'jds::components.checkbox.control' => 'jds::checkbox.control',
            'jds::components.checkbox.indicator' => 'jds::checkbox.indicator',
            Input::class => 'jds::input',
            Label::class => 'jds::label',
            Separator::class => 'jds::separator',
            Surface::class => 'jds::surface',
            TextArea::class => 'jds::textarea',
        ]);
    }
}
