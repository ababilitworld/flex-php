<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Range;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected ?int $min = 0;
    protected ?int $max = 100;
    protected ?int $step = 1;

    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('range');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? ($this->min + $this->max) / 2);
        $this->min = $data['min'] ?? $this->min;
        $this->max = $data['max'] ?? $this->max;
        $this->step = $data['step'] ?? $this->step;
    }

    public function render(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value ?? '');
        
        echo <<<HTML
            <div class="form-group">
                <label for="{$this->id}">{$this->label}</label>
                <input type="range" 
                    id="{$this->id}" 
                    name="{$this->name}" 
                    class="form-control {$this->class}"
                    value="{$value}"
                    min="{$this->min}"
                    max="{$this->max}"
                    step="{$this->step}"
                    {$requiredAttr}>
                <output for="{$this->id}">{$value}</output>
            </div>
        HTML;
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        if (!empty($this->value)) {
            $value = (int)$this->value;
            if ($value < $this->min || $value > $this->max) {
                return false;
            }
        }
        
        return true;
    }
}