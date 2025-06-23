<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Select;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected array $options = [];
    protected bool $multiple = false;
    protected bool $searchable = false;
    protected bool $allowClear = false;
    protected ?string $placeholder = null;
    protected array $selected = [];

    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('select');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        
        // Select-specific properties
        $this->options = $data['options'] ?? [];
        $this->multiple = $data['multiple'] ?? false;
        $this->searchable = $data['searchable'] ?? false;
        $this->allowClear = $data['allowClear'] ?? false;
        $this->placeholder = $data['placeholder'] ?? ($this->required ? null : '-- Select --');
        
        // Handle selected values
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
        $requiredAttr = $this->required ? 'required' : '';
        $multipleAttr = $this->multiple ? 'multiple' : '';
        $nameAttr = $this->multiple ? "name=\"{$this->name}[]\"" : "name=\"{$this->name}\"";
        
        $searchClass = $this->searchable ? 'searchable-select' : '';
        $select2Class = $this->searchable || $this->multiple ? 'select2-enabled' : '';
        
        echo <<<HTML
            <div class="form-group select-container">
                <label for="{$this->id}">{$this->label}</label>
                <select id="{$this->id}" 
                        {$nameAttr}
                        class="form-control {$this->class} {$searchClass} {$select2Class}"
                        {$multipleAttr}
                        {$requiredAttr}
                        data-placeholder="{$this->placeholder}"
                        data-allow-clear="{$this->allowClear}">
        HTML;

        if ($this->placeholder && !$this->multiple) {
            echo '<option value="">' . htmlspecialchars($this->placeholder) . '</option>';
        }

        foreach ($this->options as $value => $label) {
            $selectedAttr = in_array($value, $this->selected) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($value) . '" ' . $selectedAttr . '>' 
            . htmlspecialchars($label) . '</option>';
        }

        echo <<<HTML
                </select>
            </div>
        HTML;

        // Add JavaScript initialization if needed
        if ($this->searchable || $this->multiple) 
        {
            $this->renderSelect2Init();
        }
    }

    protected function renderSelect2Init(): void
    {
        echo <<<HTML
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const select = document.getElementById('{$this->id}');
                    if (select) {
                        $(select).select2({
                            placeholder: '{$this->placeholder}',
                            allowClear: {$this->allowClear ? 'true' : 'false'},
                            width: '100%'
                        });
                    }
                });
            </script>
        HTML;
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->selected)) {
            return false;
        }
        
        // Validate all selected values exist in options
        foreach ($this->selected as $value) {
            if (!array_key_exists($value, $this->options)) {
                return false;
            }
        }
        
        return true;
    }
}