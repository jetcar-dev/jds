<?php

namespace Jetcar\Jds\Components\Checkbox;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use InvalidArgumentException;
use Jetcar\Jds\Support\FieldAttributes;

final class Checkbox extends Component
{
    public function __construct(
        public string $variant = 'primary',
        public bool $checked = false,
        public bool $disabled = false,
        public bool $required = false,
        public bool $isIndeterminate = false,
        public bool $isInvalid = false,
        public bool $isReadOnly = false,
    ) {
        if (! in_array($this->variant, ['primary', 'secondary'], true)) {
            throw new InvalidArgumentException("Invalid JDS checkbox variant: {$this->variant}.");
        }
    }

    public function stateAttributes(bool $invalid): array
    {
        return [
            'data-selected' => $this->checked ? 'true' : null,
            'data-indeterminate' => $this->isIndeterminate ? 'true' : null,
            'data-disabled' => $this->disabled ? 'true' : null,
            'data-required' => $this->required ? 'true' : null,
            'data-invalid' => $invalid ? 'true' : null,
            'data-readonly' => $this->isReadOnly ? 'true' : null,
        ];
    }

    public function fieldAttributes(ComponentAttributeBag $attributes): ComponentAttributeBag
    {
        return $attributes->filter(fn ($value, $name) => ! $this->isInputAttribute($name))
            ->class(['jds-checkbox', "jds-checkbox--{$this->variant}"])
            ->merge(['data-slot' => 'checkbox']);
    }

    public function inputAttributes(ComponentAttributeBag $attributes, ?string $descriptionId, ?string $errorId): ComponentAttributeBag
    {
        $input = FieldAttributes::normalizeAriaStates(
            $attributes->filter(fn ($value, $name) => $this->isInputAttribute($name))->getAttributes()
        );

        if ($this->isInvalid) {
            $input['aria-invalid'] = 'true';
        }
        if ($this->isReadOnly) {
            $input['aria-readonly'] = 'true';
        }

        $invalid = isset($input['aria-invalid']) && ! in_array($input['aria-invalid'], ['', 'false'], true);
        $description = trim(implode(' ', array_filter([
            $input['aria-describedby'] ?? null,
            $descriptionId,
            $invalid ? $errorId : null,
        ])));
        $input['aria-describedby'] = $description !== '' ? $description : null;

        return (new ComponentAttributeBag($input))->merge([
            'class' => 'jds-checkbox__input',
            'type' => 'checkbox',
            'checked' => $this->checked,
            'disabled' => $this->disabled,
            'required' => $this->required,
            'data-jds-checkbox-input' => '',
            'data-jds-indeterminate' => $this->isIndeterminate ? 'true' : null,
        ]);
    }

    public function render(): View
    {
        return view('jds::components.checkbox.checkbox');
    }

    private function isInputAttribute(string $name): bool
    {
        return in_array($name, ['id', 'name', 'value', 'form', 'autofocus', 'tabindex'], true)
            || str_starts_with($name, 'aria-')
            || str_starts_with($name, 'on')
            || str_starts_with($name, 'wire:model')
            || str_starts_with($name, 'x-model');
    }
}
