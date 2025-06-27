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
    public function init(array $data = []): static
    {
        // Set basic properties from data
        $this->set_name($data['name'] ?? '');
        $this->set_type('text');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->set_help_text($data['help_text'] ?? '');
        
        // Set text-specific properties
        $this->minLength = $data['minLength'] ?? null;
        $this->maxLength = $data['maxLength'] ?? null;
        $this->pattern = $data['pattern'] ?? null;

        return $this;
    }

    public function render(): void
    {
        $required_attr = $this->required ? ' required' : '';
        $value = $this->value ?? '';
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
                <?php echo $this->minLength !== null ? 'minlength="'.esc_attr($this->minLength).'"' : ''; ?>
                <?php echo $this->maxLength !== null ? 'maxlength="'.esc_attr($this->maxLength).'"' : ''; ?>
                <?php echo $this->pattern !== null ? 'pattern="'.esc_attr($this->pattern).'"' : ''; ?>
                
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