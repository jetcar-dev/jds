<?php

namespace Jetcar\Jds\Components\Label;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Label extends Component
{
    public function __construct(
        public bool $isRequired = false,
        public bool $isDisabled = false,
        public bool $isInvalid = false,
    ) {}

    public function render(): View
    {
        return view('jds::components.label.label');
    }
}
