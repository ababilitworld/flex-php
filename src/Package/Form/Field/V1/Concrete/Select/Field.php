<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Select;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
    FlexPhp\Package\Form\Field\V1\Contract\Field as FieldContract,
};

class Field extends BaseField
{
    protected array $options = [];
    protected bool $multiple = false;
    protected bool $searchable = false;
    protected bool $allowClear = false;
    protected ?string $placeholder = null;
    protected array $selected = [];

    public function init(array $data = []): static
    {
        // Set basic properties from data
        $this->set_name($data['name'] ?? '');
        $this->set_type('select');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_help_text($data['help_text'] ?? '');
        
        // Set select-specific properties
        $this->options = $data['options'] ?? [];
        $this->multiple = $data['multiple'] ?? false;
        $this->searchable = $data['searchable'] ?? false;
        $this->allowClear = $data['allowClear'] ?? false;
        $this->placeholder = $data['placeholder'] ?? ($this->required ? null : '-- Select --');
        $this->selected = $this->normalizeSelectedValues($data['selected'] ?? $data['value'] ?? []);

        return $this;
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
        $required_attr = $this->required ? ' required' : '';
        $multiple_attr = $this->multiple ? ' multiple' : '';
        $name_attr = $this->multiple ? 'name="'.esc_attr($this->name).'[]"' : 'name="'.esc_attr($this->name).'"';
        $select2_class = ($this->searchable || $this->multiple) ? ' select2-enabled' : '';
        ?>
        
        <div class="form-field">
            <?php if (!empty($this->label)): ?>
                <label class="form-label" for="<?php echo esc_attr($this->id); ?>">
                    <?php echo esc_html($this->label); ?>
                    <?php if($this->required): ?>
                        <span class="required">*</span>
                    <?php endif; ?>
                </label>
            <?php endif; ?>

            <select 
                id="<?php echo esc_attr($this->id); ?>"
                <?php echo $name_attr; ?>
                class="form-control <?php echo esc_attr($this->class.$select2_class); ?>"
                <?php echo $multiple_attr; ?>
                <?php echo $required_attr; ?>
                data-placeholder="<?php echo esc_attr($this->placeholder ?? ''); ?>"
                data-allow-clear="<?php echo $this->allowClear ? 'true' : 'false'; ?>"
            >
                <?php if ($this->placeholder && !$this->multiple): ?>
                    <option value=""><?php echo esc_html($this->placeholder); ?></option>
                <?php endif; ?>

                <?php foreach ($this->options as $value => $label): ?>
                    <option 
                        value="<?php echo esc_attr($value); ?>"
                        <?php echo in_array($value, $this->selected) ? ' selected' : ''; ?>
                    >
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (!empty($this->help_text)): ?>
                <span class="help-text"><?php echo esc_html($this->help_text); ?></span>
            <?php endif; ?>

            <?php if ($this->searchable || $this->multiple): ?>
                <?php $this->renderSelect2Init(); ?>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function renderSelect2Init(): void
    {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('<?php echo esc_js($this->id); ?>');
                if (select) {
                    $(select).select2({
                        placeholder: '<?php echo esc_js($this->placeholder ?? ''); ?>',
                        allowClear: <?php echo $this->allowClear ? 'true' : 'false'; ?>,
                        width: '100%'
                    });
                }
            });
        </script>
        <?php
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->selected)) {
            return false;
        }
        
        foreach ($this->selected as $value) {
            if (!array_key_exists($value, $this->options)) {
                return false;
            }
        }
        
        return true;
    }
}