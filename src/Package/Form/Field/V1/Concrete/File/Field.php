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
    protected string $upload_action_text;

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
        $this->set_help_text($data['help_text'] ?? '');
        
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
         <div class="form-field">
            <label class="form-label">
                Deed Image
                <span class="required">*</span>
            </label>            
        </div>
        <div class="form-field">

        <?php if (!empty($this->label)): ?>    
            <label class="form-label" for="<?php echo $this->id;?>"><?php echo $this->label;?>
                <?php if($this->required): ?>
                    <span class="required">*</span>
                <?php endif; ?>            
            </label>
        <?php endif; ?>

        <?php $this->name = $this->name . ($this->multiple ? '[]' : ''); ?>

            <button class="button image-upload"><?php echo $this->upload_action_text;?></button>
            <?php if (!empty($this->help_text)): ?>
                <span class="help-text"><?php echo $this->help_text;?></span>            
            <?php endif; ?>
            <input type="hidden" 
                id="<?php echo $this->id;?>" 
                name="<?php echo $this->name;?>"
                <?php echo $multiple_attr.' '.$accept_attr;?>
            >
            <?php $this->renderPreviewItems(); ?>
        <?php
    }

    public function renderPreviewItems(): void
    {
        echo '<div id="' . esc_attr($this->id) . '-preview" class="image-preview-container">';
        
        if (is_array($this->previewImages) && count($this->previewImages) > 0) {
            foreach ($this->previewImages as $image) {
                $image_url = is_numeric($image) ? wp_get_attachment_url($image) : esc_url($image);
                $image_id = is_numeric($image) ? $image : attachment_url_to_postid($image_url);
                
                echo '<div class="image-preview-item">';
                echo '<img src="' . esc_url($image_url) . '" style="max-width: 150px;">';
                echo '<input type="hidden" name="' . esc_attr($this->name) . '[]" value="' . esc_attr($image_id) . '">';
                echo '<a href="#" class="remove-image" title="Remove image">';
                echo '<span class="dashicons dashicons-trash"></span>';
                echo '</a>';
                echo '</div>';
            }
        }
        
        echo '</div>';
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
