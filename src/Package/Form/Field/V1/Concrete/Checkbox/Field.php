<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Checkbox;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected bool $checked = false;
    protected bool $inline = false;
    protected ?string $helpText = null;
    protected ?string $toggleBehavior = null; // 'toggle', 'radio'
    protected array $options = []; // For checkbox group
    protected array $selected = []; // For checkbox group

    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('checkbox');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? '1');
        
        // Checkbox-specific properties
        $this->checked = $data['checked'] ?? false;
        $this->inline = $data['inline'] ?? false;
        $this->helpText = $data['helpText'] ?? null;
        $this->toggleBehavior = $data['toggleBehavior'] ?? null;
        
        // For checkbox groups
        $this->options = $data['options'] ?? [];
        $this->selected = $this->normalizeSelectedValues($data['selected'] ?? $data['value'] ?? []);
    }

    protected function normalizeSelectedValues($selected): array
    {
        if ($selected === null) {
            return [];
        }
        
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        
        return array_filter($selected, function($value) {
            return isset($this->options[$value]);
        });
    }

    public function render(): void
    {
        if (!empty($this->options)) {
            $this->renderCheckboxGroup();
        } else {
            $this->renderSingleCheckbox();
        }
    }

    protected function renderSingleCheckbox(): void
    {
        $requiredAttr = $this->required ? 'required' : '';
        $checkedAttr = $this->checked ? 'checked' : '';
        $value = htmlspecialchars($this->value ?? '1');
        $toggleClass = $this->toggleBehavior ? 'toggle-behavior-' . $this->toggleBehavior : '';
        $inlineClass = $this->inline ? 'form-check-inline' : '';
        
        $helpTextHtml = $this->helpText ? 
            '<small class="form-text text-muted">' . htmlspecialchars($this->helpText) . '</small>' : '';
        
        echo <<<HTML
            <div class="form-group checkbox-container">
                <div class="form-check {$inlineClass} {$toggleClass}">
                    <input type="checkbox" 
                        id="{$this->id}" 
                        name="{$this->name}" 
                        class="form-check-input {$this->class}"
                        value="{$value}"
                        {$checkedAttr}
                        {$requiredAttr}>
                    <label class="form-check-label" for="{$this->id}">{$this->label}</label>
                    {$helpTextHtml}
                </div>
            </div>  
        HTML;
    }

    protected function renderCheckboxGroup(): void
    {
        $groupClass = $this->inline ? 'checkbox-group-inline' : 'checkbox-group-stacked';
        $requiredAttr = $this->required ? 'data-required="true"' : '';
        
        echo <<<HTML
            <div class="form-group checkbox-group-container {$groupClass}" {$requiredAttr}>
                <label class="group-label">{$this->label}</label>
        HTML;

        foreach ($this->options as $value => $label) {
            $checkedAttr = in_array($value, $this->selected) ? 'checked' : '';
            $id = "{$this->id}_{$value}";
            $inlineClass = $this->inline ? 'form-check-inline' : '';
            
            echo <<<HTML
                <div class="form-check {$inlineClass}">
                    <input type="checkbox" 
                        id="{$id}" 
                        name="{$this->name}[]" 
                        class="form-check-input {$this->class}"
                        value="{$value}"
                        {$checkedAttr}>
                    <label class="form-check-label" for="{$id}">{$label}</label>
                </div>
            HTML;
        }

        if ($this->helpText) {
            echo '<small class="form-text text-muted">' . htmlspecialchars($this->helpText) . '</small>';
        }

        echo '</div>';
    }

    public function validate(): bool
    {
        if ($this->required) {
            if (!empty($this->options)) {
                // For checkbox group
                if (empty($this->selected)) {
                    return false;
                }
            } else {
                // For single checkbox
                if (!$this->checked) {
                    return false;
                }
            }
        }
        
        // Validate all selected values exist in options (for groups)
        foreach ($this->selected as $value) {
            if (!array_key_exists($value, $this->options)) {
                return false;
            }
        }
        
        return true;
    }
}