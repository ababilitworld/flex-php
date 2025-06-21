<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Color;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('color');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? '#000000');
    }

    public function render(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value ?? '#000000');
        
        echo <<<HTML
            <div class="form-group">
                <label for="{$this->id}">{$this->label}</label>
                <input type="color" 
                    id="{$this->id}" 
                    name="{$this->name}" 
                    class="form-control {$this->class}"
                    value="{$value}"
                    {$requiredAttr}>
            </div>
        HTML;
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        return empty($this->value) || preg_match('/^#[0-9a-fA-F]{6}$/', $this->value);
    }
}