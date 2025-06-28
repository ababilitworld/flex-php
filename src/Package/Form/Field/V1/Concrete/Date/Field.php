<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Date;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
    FlexPhp\Package\Form\Field\V1\Contract\Field as FieldContract,
};

class Field extends BaseField
{
    protected ?string $minDate = null;
    protected ?string $maxDate = null;
    protected bool $enableDatepicker = true;
    protected array $datepickerOptions = [];

    public function init(array $data = []): static
    {
        // Set basic properties from data
        $this->set_name($data['name'] ?? '');
        $this->set_type('text'); // Changed to text for datepicker compatibility
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->set_help_text($data['help_text'] ?? '');
        
        // Set date-specific properties
        $this->minDate = $data['minDate'] ?? null;
        $this->maxDate = $data['maxDate'] ?? null;
        $this->enableDatepicker = $data['enableDatepicker'] ?? true;
        $this->datepickerOptions = $data['datepickerOptions'] ?? [];

        return $this;
    }

    public function render(): void
    {
        $required_attr = $this->required ? ' required' : '';
        $value = $this->value ?? '';
        
        // Add datepicker class if enabled
        $classes = $this->class;
        if ($this->enableDatepicker) {
            $classes .= ' flexphp-datepicker';
            $this->enqueueDatepickerAssets();
        }
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
                class="form-control <?php echo esc_attr(trim($classes)); ?>"
                value="<?php echo esc_attr($value); ?>"
                <?php echo $required_attr; ?>
                <?php if (!$this->enableDatepicker): ?>
                    <?php echo $this->minDate !== null ? 'min="'.esc_attr($this->minDate).'"' : ''; ?>
                    <?php echo $this->maxDate !== null ? 'max="'.esc_attr($this->maxDate).'"' : ''; ?>
                <?php endif; ?>
                autocomplete="off"
            >
            
            <?php if (!empty($this->help_text)): ?>
                <span class="help-text"><?php echo esc_html($this->help_text); ?></span>            
            <?php endif; ?>
        </div>
        
        <?php if ($this->enableDatepicker): ?>
        <script>
            jQuery(document).ready(function($) {
                $('#<?php echo esc_js($this->id); ?>').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: '<?php echo esc_js($this->minDate ?? ''); ?>',
                    maxDate: '<?php echo esc_js($this->maxDate ?? ''); ?>',
                    <?php foreach ($this->datepickerOptions as $key => $val): ?>
                    <?php echo esc_js($key); ?>: <?php echo is_string($val) ? "'".esc_js($val)."'" : json_encode($val); ?>,
                    <?php endforeach; ?>
                });
            });
        </script>
        <?php endif;
    }

    protected function enqueueDatepickerAssets(): void
    {
        static $assetsEnqueued = false;
        
        if (!$assetsEnqueued) 
        {
            // Check if we're in admin or frontend
            $isAdmin = is_admin();
            
            // Hook to the appropriate enqueue action
            $hook = $isAdmin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';
            
            add_action($hook, function() use ($isAdmin) {
                // Check if scripts are already registered/enqueued
                if (!wp_script_is('jquery-ui-datepicker', 'registered')) 
                {
                    wp_enqueue_script('jquery-ui-datepicker');
                }
                
                // Check if styles are already registered/enqueued
                if (!wp_style_is('jquery-ui-css', 'registered')) 
                {
                    // Use WordPress bundled version instead of CDN when in admin
                    if ($isAdmin) 
                    {
                        wp_enqueue_style('jquery-ui-css', 
                            includes_url('css/jquery-ui.min.css'),
                            [],
                            false
                        );
                    } 
                    else 
                    {
                        wp_enqueue_style('jquery-ui-css', 
                            'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
                            [],
                            false
                        );
                    }
                }
                
                // Ensure the scripts are enqueued
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_style('jquery-ui-css');
            });
            
            $assetsEnqueued = true;
        }
    }

    public function validate(): bool
    {
        $value = $this->value;
        
        if ($this->required && empty($value)) {
            return false;
        }

        if (!empty($value)) {
            try {
                $date = new \DateTime($value);
                
                if ($this->minDate !== null && $date < new \DateTime($this->minDate)) {
                    return false;
                }
                
                if ($this->maxDate !== null && $date > new \DateTime($this->maxDate)) {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }
}