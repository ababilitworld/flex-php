<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Color;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    public function init(array $data = []): static
    {
        // Set basic properties from data
        $this->set_name($data['name'] ?? '');
        $this->set_type('color');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? '#000000');
        $this->set_help_text($data['help_text'] ?? '');

        return $this;
    }

    public function render(): void
    {
        $required_attr = $this->required ? ' required' : '';
        $value = $this->value ?? '#000000';
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

            <input 
                type="<?php echo esc_attr($this->type); ?>" 
                id="<?php echo esc_attr($this->id); ?>" 
                name="<?php echo esc_attr($this->name); ?>" 
                class="form-control <?php echo esc_attr($this->class); ?>"
                value="<?php echo esc_attr($value); ?>"
                <?php echo $required_attr; ?>
            >

            <?php if (!empty($this->help_text)): ?>
                <span class="help-text"><?php echo esc_html($this->help_text); ?></span>            
            <?php endif; ?>
        </div>
        <?php
    }

    public function validate(): bool
    {
        $value = $this->value;
        
        if ($this->required && empty($value)) {
            return false;
        }
        
        // Allow empty values if not required
        if (empty($value)) {
            return true;
        }
        
        // Validate hex color format (3 or 6 digits with #)
        return preg_match('/^#([0-9a-fA-F]{3}){1,2}$/', $value);
    }
}