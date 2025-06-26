<?php 
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Form\Field\V1\Contract\Field as FieldContract
};

abstract class Field implements FieldContract
{
    protected string $name;
    protected string $type;
    protected string $id;
    protected string $class;
    protected array $data = [];
    protected string $label;
    protected bool $required;
    protected $value;
    protected $help_text;

    public function __construct()
    {
        $this->init();
    }
    abstract public function init(array $data = []): static;
    abstract public function render():void;

    public function set_name(string $name):void
    {
        $this->name = $name;
    }

    public function set_type(string $type):void
    {
        $this->type = $type;
    }
    public function set_id(string $id):void
    {
        $this->id = $id;
    }

    public function set_class(string $class):void
    {
        $this->class = $class;
    }

    public function set_data(array $data = []):void
    {
        $this->data = $data;
    }

    public function set_label(string $label):void
    {
        $this->label = $label;
    }

    public function set_required(bool $required = false):void
    {
        $this->required = $required;
    }

    public function set_value(mixed $value):void
    {
        $this->value = $value;
    }

    public function set_help_text(string $help_text):void
    {
        $this->help_text = $help_text;
    }
    
}
