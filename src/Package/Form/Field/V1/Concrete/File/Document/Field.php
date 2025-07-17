<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\File\Document;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    public $multiple_attr;
    public $accept_attr;
    public $field_name;
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
    protected array $preview_items = [];

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
        $this->set_type('hidden');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_class($data['class'] ?? '');
        $this->set_label($data['label'] ?? '');
        $this->set_required($data['required'] ?? false);
        $this->set_value($data['value'] ?? null);
        $this->set_help_text($data['help_text'] ?? '');
        
        $this->set_type('hidden'); // Set type to 'file'
        
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

        if (isset($data['preview_items'])) {
            $this->preview_items = (array)$data['preview_items'];
        }
        
        return $this;
    }

    public function render(): void
    {
        $this->multiple_attr = $this->multiple ? ' multiple' : '';
        $this->accept_attr = !empty($this->allowed_types) ? ' accept=".pdf,.doc,.docx,.xls,.xlsx"' : '';
        $this->field_name = $this->name . ($this->multiple ? '[]' : '');
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

            <button type="button" class="button document-upload">
                <?php echo esc_html($this->upload_action_text); ?>
            </button>
            
            <?php if (!empty($this->help_text)): ?>
                <span class="help-text"><?php echo esc_html($this->help_text); ?></span>            
            <?php endif; ?>
            
            <input type="hidden" 
                id="<?php echo esc_attr($this->id); ?>" 
                name="<?php echo esc_attr($this->field_name); ?>"
                <?php echo $this->multiple_attr . ' ' . $this->accept_attr; ?>
                <?php echo $this->required ? ' required' : ''; ?>
            >
            <div id="<?php echo esc_attr($this->id);?>-preview" class="document-preview-container">
                <?php $this->render_preview_items(); ?>
            </div>
        </div>
        <?php
    }

    protected function render_preview_items(): void
    {
        if (empty($this->preview_items)) {
            return;
        }
        
        foreach ($this->preview_items as $doc) {
            if (empty($doc)) continue;
            
            $doc_url = is_numeric($doc) ? wp_get_attachment_url($doc) : esc_url($doc);
            $doc_id = is_numeric($doc) ? $doc : attachment_url_to_postid($doc_url);
            $doc_title = is_numeric($doc) ? get_the_title($doc) : basename($doc_url);
            $doc_ext = pathinfo($doc_url, PATHINFO_EXTENSION);
            
            if (empty($doc_url)) continue;
            
            $icon_class = 'dashicons-media-default';
            if ($doc_ext === 'pdf') {
                $icon_class = 'dashicons-media-pdf';
            } elseif (in_array($doc_ext, ['doc', 'docx'])) {
                $icon_class = 'dashicons-media-document';
            } elseif (in_array($doc_ext, ['xls', 'xlsx'])) {
                $icon_class = 'dashicons-media-spreadsheet';
            }
            
            echo '<div class="document-preview-item">';
            echo '<span class="dashicons ' . esc_attr($icon_class) . '"></span>';
            echo '<span class="document-name">' . esc_html($doc_title) . '</span>';
            echo '<input type="hidden" name="' . esc_attr($this->name) . '[]" value="' . esc_attr($doc_id) . '">';
            echo '<button type="button" class="remove-document" title="Remove document">';
            echo '<span class="dashicons dashicons-trash"></span>';
            echo '</button>';
            echo '</div>';
        }
    }

    public function validate(): bool
    {
        if ($this->required && empty($this->value)) {
            return false;
        }
        
        return true;
    }


}