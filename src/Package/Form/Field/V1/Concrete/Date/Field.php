<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Date;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected ?string $minDate = null;
    protected ?string $maxDate = null;

    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('date');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->minDate = $data['minDate'] ?? null;
        $this->maxDate = $data['maxDate'] ?? null;
    }

    public function render(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value ?? '');
        $minAttr = $this->minDate ? "min=\"{$this->minDate}\"" : '';
        $maxAttr = $this->maxDate ? "max=\"{$this->maxDate}\"" : '';
        
        echo <<<HTML
            <div class="form-group">
                <label for="{$this->id}">{$this->label}</label>
                <input type="date" 
                    id="{$this->id}" 
                    name="{$this->name}" 
                    class="form-control {$this->class}"
                    value="{$value}"
                    {$minAttr}
                    {$maxAttr}
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
            try {
                $date = new \DateTime($this->value);
                
                if ($this->minDate && $date < new \DateTime($this->minDate)) {
                    return false;
                }
                
                if ($this->maxDate && $date > new \DateTime($this->maxDate)) {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        
        return true;
    }
}