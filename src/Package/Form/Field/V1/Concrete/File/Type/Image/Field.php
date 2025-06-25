<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\File\Type\Image;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
    FlexPhp\Package\Form\Field\V1\Concrete\File\Field as FileField
};

class Field extends FileField
{
    public function init(array $data = []): static
    {
        parent::init($data);
        $this->allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $this->class = 'image-input';
        return $this;
    }

    public function render(): void
    {
        parent::render();
        
        // Add preview functionality for images
        echo '<div class="image-preview-container" id="'.$this->id.'-preview"></div>';
        
        // Add JavaScript for preview
        $this->renderPreviewJs();
    }
    
    protected function renderPreviewJs(): void
    {
        echo '<script>
        document.getElementById("'.$this->id.'").addEventListener("change", function(e) {
            const preview = document.getElementById("'.$this->id.'-preview");
            preview.innerHTML = "";
            
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.match("image.*")) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.style.maxWidth = "150px";
                        img.style.maxHeight = "150px";
                        img.style.margin = "5px";
                        preview.appendChild(img);
                    }
                    reader.readAsDataURL(files[i]);
                }
            }
        });
        </script>';
    }
}
