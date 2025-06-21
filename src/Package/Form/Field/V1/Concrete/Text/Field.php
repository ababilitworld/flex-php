<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Text;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
    FlexPhp\Package\Form\Field\V1\Contract\Field as FieldContract,
    
};

class Field extends BaseField
{
    protected array $items = [];
    protected ?int $minLength = null;
    protected ?int $maxLength = null;
    protected ?string $pattern = null;
    public function init(array $data = []): void
    {
        // Set basic properties from data
        $this->set_name($data['name'] ?? '');
        $this->set_type('text');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        
        // Set text-specific properties
        $this->minLength = $data['minLength'] ?? null;
        $this->maxLength = $data['maxLength'] ?? null;
        $this->pattern = $data['pattern'] ?? null;
    }

    public function render(): void 
    {
        $requiredAttr = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value ?? '');
        
        echo <<<HTML
                <div class="form-group">
                    <label for="{$this->id}">{$this->label}</label>
                    <input type="{$this->type}" 
                        id="{$this->id}" 
                        name="{$this->name}" 
                        class="form-control {$this->class}"
                        value="{$value}"
                        {$requiredAttr}
                        minlength="{$this->minLength}"
                        maxlength="{$this->maxLength}"
                        pattern="{$this->pattern}">
                </div>
            HTML;
    }

    public function validate(): bool
    {
        $value = $this->value;
        
        if ($this->required && empty($value)) {
            return false;
        }

        if (!empty($value)) {
            if ($this->minLength !== null && strlen($value) < $this->minLength) {
                return false;
            }

            if ($this->maxLength !== null && strlen($value) > $this->maxLength) {
                return false;
            }

            if ($this->pattern !== null && !preg_match("/{$this->pattern}/", $value)) {
                return false;
            }
        }

        return true;
    }
}