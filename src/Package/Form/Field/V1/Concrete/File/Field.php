<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\File;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    protected bool $multiple = false;
    protected array $allowed_types = [];
    protected int $max_size = 0; // in bytes
    protected bool $enable_media_library = true;

    protected array $previewImages = [];

    public function init(array $data = []): static
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('file');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        
        if (isset($data['multiple'])) {
            $this->multiple = (bool)$data['multiple'];
        }
        
        if (isset($data['allowed_types'])) {
            $this->allowed_types = (array)$data['allowed_types'];
        }
        
        if (isset($data['max_size'])) {
            $this->max_size = (int)$data['max_size'];
        }
        
        if (isset($data['enable_media_library'])) {
            $this->enable_media_library = (bool)$data['enable_media_library'];
        }

        if (isset($data['preview_images'])) {
            $this->previewImages = (array)$data['preview_images'];
        }
        
        return $this;
    }

    public function render(): void
    {
        $multiple_attr = $this->multiple ? ' multiple' : '';
        $accept_attr = !empty($this->allowed_types) ? ' accept="'.implode(',', $this->allowed_types).'"' : '';
        
        ?>
        <!-- Image Field -->
        <div class="form-field file-upload-field">

        <?php if (!empty($this->label)): ?>    
            <label class="form-label" for="<?php echo $this->id;?>"><?php echo $this->label;?>
                <?php if($this->required): ?>
                    <span class="required">*</span>
                <?php endif; ?>            
            </label>
        <?php endif; ?>

        <?php $this->name = $this->name . ($this->multiple ? '[]' : ''); ?>
        
            <input 
                type="<?php echo $this->type;?>" 
                id="<?php echo $this->id;?>" 
                name="<?php echo $this->name;?>"
                class="form-control button <?php echo $this->class;?>" 
                <?php echo $multiple_attr.' '.$accept_attr;?>
            >
        <?php if (!empty($this->help_text)): ?>
            <span class="help-text"><?php echo $this->help_text;?></span>            
        <?php endif; ?>
        </div>

        <?php $this->renderPreviewItems(); ?>
        <?php
    }

    public function renderPreviewItems(): void
    {
        // <label for="document-images">Document Images:</label>
        // <input type="button" class="button" id="upload-images-button" value="Upload Images">
        echo '<ul id="'.esc_attr($this->name ).'-preview">';
        
            if (is_array($this->previewImages) && count($this->previewImages) > 0) 
            {
                foreach ($this->previewImages as $image) 
                {
                    echo '<li>
                            <img src="' . wp_get_attachment_url(esc_url($image)) . '" style="max-width: 150px;">
                            <input type="hidden" name="'.esc_attr($this->name ).'" value="' . esc_url($image) . '">
                            <a href="#" class="remove-image" title="Remove image">
                                <span class="dashicons dashicons-trash"></span>
                            </a>
                        </li>';
                }
            }
            
        echo '</ul>';
    }



    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        if (!empty($this->value) && !array_key_exists($this->value, $this->options)) {
            return false;
        }
        
        return true;
    }
}
