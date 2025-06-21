<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Concrete\Hidden;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Base\Field as BaseField,
};

class Field extends BaseField
{
    public function init(array $data = []): void
    {
        $this->set_name($data['name'] ?? '');
        $this->set_type('hidden');
        $this->set_id($data['id'] ?? $data['name'] ?? '');
        $this->set_value($data['value'] ?? '');
    }

    public function render(): void
    {
        $value = htmlspecialchars($this->value ?? '');
        
        echo <<<HTML
            <input type="hidden" 
                id="{$this->id}" 
                name="{$this->name}" 
                value="{$value}">
        HTML;
    }

    public function validate(): bool
    {
        return true; // Hidden fields typically don't need validation
    }
}