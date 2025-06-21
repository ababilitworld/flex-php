<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Switch;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected string $onText = 'ON';
    protected string $offText = 'OFF';
    protected bool $checked = false;
    protected string $size = 'medium'; // small, medium, large

    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('checkbox');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? '1');
        
        // Switch-specific properties
        $this->onText = $data['onText'] ?? $this->onText;
        $this->offText = $data['offText'] ?? $this->offText;
        $this->checked = $data['checked'] ?? false;
        $this->size = $data['size'] ?? $this->size;
    }

    public function render(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $checkedAttr = $this->checked ? 'checked' : '';
        $value = htmlspecialchars($this->value ?? '1');
        
        echo <<<HTML
            <div class="form-group switch-container">
                <label class="switch-label">{$this->label}</label>
                <div class="switch-wrapper switch-{$this->size}">
                    <label class="switch">
                        <input type="checkbox" 
                            id="{$this->id}" 
                            name="{$this->name}" 
                            class="switch-input {$this->class}"
                            value="{$value}"
                            {$checkedAttr}
                            {$requiredAttr}>
                        <span class="switch-slider">
                            <span class="switch-on">{$this->onText}</span>
                            <span class="switch-off">{$this->offText}</span>
                        </span>
                    </label>
                </div>
            </div>
        HTML;
    }

    public function validate(): bool
    {
        // For a switch, validation is simple - just check if required
        if ($this->required && !$this->checked) {
            return false;
        }
        return true;
    }
}