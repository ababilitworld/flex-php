<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Number;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};
class Field extends BaseField
{
    protected ?int $min = null;
    protected ?int $max = null;
    protected ?int $step = null;

    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('number');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->min = $data['min'] ?? null;
        $this->max = $data['max'] ?? null;
        $this->step = $data['step'] ?? null;
    }

    public function render(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value ?? '');
        $minAttr = $this->min !== null ? "min=\"{$this->min}\"" : '';
        $maxAttr = $this->max !== null ? "max=\"{$this->max}\"" : '';
        $stepAttr = $this->step !== null ? "step=\"{$this->step}\"" : '';
        
        echo <<<HTML
            <div class="form-group">
                <label for="{$this->id}">{$this->label}</label>
                <input type="number" 
                    id="{$this->id}" 
                    name="{$this->name}" 
                    class="form-control {$this->class}"
                    value="{$value}"
                    {$minAttr}
                    {$maxAttr}
                    {$stepAttr}
                    {$requiredAttr}>
            </div>
        HTML;
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        if (!empty($this->value)) {
            if (!is_numeric($this->value)) {
                return false;
            }
            
            if ($this->min !== null && $this->value < $this->min) {
                return false;
            }
            
            if ($this->max !== null && $this->value > $this->max) {
                return false;
            }
        }
        
        return true;
    }
}