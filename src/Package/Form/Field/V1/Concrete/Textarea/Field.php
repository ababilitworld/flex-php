<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Textarea;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected ?int $rows = 3;
    protected ?int $cols = null;
    protected ?int $maxLength = null;

    public function init(array $data = []): static
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('textarea');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->rows = $data['rows'] ?? 3;
        $this->cols = $data['cols'] ?? null;
        $this->maxLength = $data['maxLength'] ?? null;

        return $this;
    }

    public function render(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $value = htmlspecialchars($this->value ?? '');
        $colsAttr = $this->cols ? "cols=\"{$this->cols}\"" : '';
        $maxLengthAttr = $this->maxLength ? "maxlength=\"{$this->maxLength}\"" : '';
        
        echo <<<HTML
            <div class="form-group">
                <label for="{$this->id}">{$this->label}</label>
                <textarea id="{$this->id}" 
                        name="{$this->name}" 
                        class="form-control {$this->class}"
                        rows="{$this->rows}"
                        {$colsAttr}
                        {$maxLengthAttr}
                        {$requiredAttr}>{$value}</textarea>
            </div>
        HTML;
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        if (!empty($this->value) && $this->maxLength && strlen($this->value) > $this->maxLength) {
            return false;
        }
        
        return true;
    }
}