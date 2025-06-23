<?php 
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Contract;

interface Field
{
    /**
     * Initialize the field with configuration data
     * 
     * @param array $data Field configuration options
     * @return static
     */
    public function init(array $data = []): static;
    
    /**
     * Render the field HTML
     */
    public function render(): void; 
}