<?php 
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Contract;

interface Field
{
    public function init(array $data = []): void;
    public function render(): void; 
}