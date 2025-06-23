<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Radio;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected array $options = [];

    public function init(array $data = []): static
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('radio');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->options = $data['options'] ?? [];

        return $this;
    }

    public function render(): void
    {
        $optionsHtml = '';
        foreach ($this->options as $value => $label) {
            $checked = $this->value == $value ? 'checked' : '';
            $optionId = "{$this->id}_{$value}";
            
            $optionsHtml .= <<<HTML
                <div class="form-check">
                    <input type="radio" 
                        id="{$optionId}" 
                        name="{$this->name}" 
                        value="{$value}"
                        class="form-check-input {$this->class}"
                        {$checked}>
                    <label class="form-check-label" for="{$optionId}">{$label}</label>
                </div>
            HTML;
        }
        
        echo <<<HTML
            <div class="form-group">
                <label>{$this->label}</label>
                {$optionsHtml}
            </div>
        HTML;
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        if (!empty($this->value) && !array_key_exists($this->value, $this->options)) {
            return false;
        }
        
        return true;
    }
}