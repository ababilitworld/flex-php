<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\File\Type\Image;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Field as FileField
};

<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\File\Type\Image;

use Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\File\Field as FileField;

class MultipleImageField extends FileField
{
    protected bool $enableMediaLibrary = false;
    protected array $previewImages = [];

    public function init(array $data = []): static
    {
        parent::init($data);
        
        // Force multiple for this field type
        $this->multiple = true;
        
        $this->allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $this->class = 'image-input multiple-image-upload';
        
        if (isset($data['enable_media_library'])) {
            $this->enableMediaLibrary = (bool)$data['enable_media_library'];
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
        
        echo '<div class="form-field multiple-image-upload-field">';
        
        // Field label
        if (!empty($this->label)) {
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        }
        
        // Upload button and hidden input
        echo '<div class="upload-controls">';
        echo '<button type="button" class="button upload-images-button" id="upload-'.$this->id.'">Select Images</button>';
        echo '<input type="file" 
                    id="'.$this->id.'" 
                    name="'.$this->name.'[]" 
                    class="'.$this->class.'" 
                    style="display: none;"
                    '.$multiple_attr.$accept_attr.'>';
        echo '</div>';
        
        
        // Preview container
        echo '<div class="image-preview-container" id="'.$this->id.'-preview">';
        
        // Render existing preview images if any
        foreach ($this->previewImages as $image) {
            $this->renderPreviewItem($image['url'], $image['id']);
        }
        
        echo '</div>';
        
        // Add JavaScript
        $this->renderJs();
    }
    
    protected function renderPreviewItem(string $imageUrl, $imageId = null): void
    {
        echo '<div class="image-preview-item">';
        echo '<img src="'.esc_url($imageUrl).'" style="max-width: 150px;">';
        
        if ($imageId !== null) {
            echo '<input type="hidden" name="'.$this->name.'_existing[]" value="'.esc_attr($imageId).'">';
        }
        
        echo '<a href="#" class="remove-image" title="Remove image">';
        echo '<span class="dashicons dashicons-trash"></span>';
        echo '</a>';
        echo '</div>';
    }
    
    protected function renderJs(): void
    {
        ?>
        <script>
        jQuery(document).ready(function($) {
            var mediaUploader;
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            var previewContainer = $('#<?php echo $this->id; ?>-preview');
            var fileInput = $('#<?php echo $this->id; ?>');
            
            // Handle click on our custom upload button
            $('#upload-<?php echo $this->id; ?>').click(function(e) {
                e.preventDefault();
                
                <?php if ($this->enableMediaLibrary): ?>
                    // WordPress media library upload
                    if (typeof wp !== 'undefined' && wp.media) {
                        this.openMediaUploader();
                        return;
                    }
                <?php endif; ?>
                
                // Fallback to regular file input
                fileInput.trigger('click');
            });
            
            // Handle file selection via regular file input
            fileInput.on('change', function() {
                var files = this.files;
                
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    
                    // Validate file type
                    if (!this.isValidImage(file)) {
                        alert('Only image files are allowed (jpg, jpeg, png, gif, webp)');
                        continue;
                    }
                    
                    // Create preview
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var previewItem = $('<div class="image-preview-item"></div>');
                        previewItem.append('<img src="' + e.target.result + '" style="max-width: 150px;">');
                        previewItem.append('<a href="#" class="remove-image" title="Remove image"><span class="dashicons dashicons-trash"></span></a>');
                        previewContainer.append(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
                
                // Reset input to allow selecting same files again
                $(this).val('');
            });
            
            // Remove image handler
            previewContainer.on('click', '.remove-image', function(e) {
                e.preventDefault();
                $(this).closest('.image-preview-item').remove();
            });
            
            <?php if ($this->enableMediaLibrary): ?>
            // WordPress media uploader
            this.openMediaUploader = function() {
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Images',
                    button: {
                        text: 'Choose Images'
                    },
                    multiple: true,
                    library: {
                        type: 'image'
                    },
                    uploader: {
                        filters: {
                            mime_types: [
                                {
                                    title: "Image Files",
                                    extensions: allowedExtensions.join(',')
                                }
                            ]
                        }
                    }
                });

                mediaUploader.on('select', function() {
                    var attachments = mediaUploader.state().get('selection').map(function(attachment) {
                        attachment.toJSON();
                        return attachment;
                    });
                    
                    attachments.forEach(function(attachment) {
                        // Double check the file type
                        var fileUrl = attachment.attributes.url.toLowerCase();
                        var isValid = allowedExtensions.some(function(ext) {
                            return fileUrl.endsWith('.' + ext);
                        });
                        
                        if (!isValid) {
                            alert('Only image files are allowed (jpg, jpeg, png, gif, webp)');
                            return;
                        }
                        
                        // Create preview with remove button
                        var previewItem = $('<div class="image-preview-item"></div>');
                        previewItem.append('<img src="' + attachment.attributes.url + '" style="max-width: 150px;">');
                        previewItem.append('<input type="hidden" name="<?php echo $this->name; ?>[]" value="' + attachment.id + '">');
                        previewItem.append('<a href="#" class="remove-image" title="Remove image"><span class="dashicons dashicons-trash"></span></a>');
                        
                        previewContainer.append(previewItem);
                    });
                });

                mediaUploader.on('uploader:error', function(error) {
                    alert('Error uploading file: ' + error.message);
                });

                mediaUploader.open();
            };
            <?php endif; ?>
            
            // Helper function to validate image files
            this.isValidImage = function(file) {
                var fileName = file.name.toLowerCase();
                return allowedExtensions.some(function(ext) {
                    return fileName.endsWith('.' + ext);
                });
            };
        });
        </script>
        <?php
    }
}
