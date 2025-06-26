<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\File;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    /**
     * @var bool Whether multiple files can be selected
     */
    protected bool $multiple = false;
    
    /**
     * @var array Allowed file types (extensions or MIME types)
     */
    protected array $allowed_types = [];
    
    /**
     * @var int Maximum file size in bytes
     */
    protected int $max_size = 0;
    
    /**
     * @var bool Whether to enable WordPress media library integration
     */
    protected bool $enable_media_library = true;
    
    /**
     * @var string Text for the upload button
     */
    protected string $upload_action_text = 'Upload';
    
    /**
     * @var array Array of image URLs/IDs for preview
     */
    protected array $previewImages = [];

    /**
     * Initialize the field with configuration
     * 
     * @param array $data {
     *     Field configuration options
     *     
     *     @type string      $name                 Field name attribute
     *     @type string      $id                   Field ID attribute
     *     @type string      $class                CSS class(es) for the field
     *     @type string      $label                Label text for the field
     *     @type bool        $required             Whether the field is required
     *     @type mixed       $value                Current value of the field
     *     @type string      $help_text            Help text displayed below the field
     *     @type bool        $multiple             Whether to allow multiple file selection
     *     @type array       $allowed_types        Array of allowed file types
     *     @type int         $max_size             Maximum file size in bytes
     *     @type bool        $enable_media_library Whether to enable media library
     *     @type string      $upload_action_text   Text for the upload button
     *     @type array       $preview_images       Array of image URLs/IDs for preview
     * }
     * @return static
     */
    public function init(array $data = []): static
    {
        // Set base field properties
        $this->set_name($data['name'] ?? '');
        $this->set_type('file');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->set_help_text($data['help_text'] ?? '');
        
        // Set file-specific properties
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

        if (isset($data['upload_action_text'])) {
            $this->upload_action_text = (string)$data['upload_action_text'];
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
        </div>
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
