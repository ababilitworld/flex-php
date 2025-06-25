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
    protected bool $use_framework_upload = false;
    protected string $upload_handler = '';

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
        
        if (isset($data['use_framework_upload'])) {
            $this->use_framework_upload = (bool)$data['use_framework_upload'];
        }
        
        if (isset($data['upload_handler'])) {
            $this->upload_handler = (string)$data['upload_handler'];
        }
        
        return $this;
    }

    public function render(): void
    {
        $multiple_attr = $this->multiple ? ' multiple' : '';
        $accept_attr = !empty($this->allowed_types) ? ' accept="'.implode(',', $this->allowed_types).'"' : '';
        
        echo '<div class="form-field file-upload-field">';
        if (!empty($this->label)) {
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        }
        echo '<input type="'.$this->type.'" 
                    id="'.$this->id.'" 
                    name="'.$this->name.($this->multiple ? '[]' : '').'" 
                    class="'.$this->class.'" 
                    '.$multiple_attr.$accept_attr.'>';
        
        // Add framework-specific upload handler if needed
        if ($this->use_framework_upload && !empty($this->upload_handler)) {
            echo '<button type="button" class="framework-upload-button" data-handler="'.$this->upload_handler.'">Select Files</button>';
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
