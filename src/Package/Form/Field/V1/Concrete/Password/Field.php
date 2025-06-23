<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Password;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected bool $showStrengthMeter = false;
    protected int $minLength = 8;

    public function init(array $data = []): static
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('password');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->showStrengthMeter = $data['showStrengthMeter'] ?? false;
        $this->minLength = $data['minLength'] ?? 8;

        return $this;
    }

    public function render(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $strengthMeter = $this->showStrengthMeter ? '<div class="password-strength-meter"></div>' : '';
        
        echo <<<HTML
            <div class="form-group">
                <label for="{$this->id}">{$this->label}</label>
                <input type="password" 
                    id="{$this->id}" 
                    name="{$this->name}" 
                    class="form-control {$this->class}"
                    minlength="{$this->minLength}"
                    {$requiredAttr}>
                {$strengthMeter}
            </div>
        HTML;
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        return empty($this->value) || strlen($this->value) >= $this->minLength;
    }
}