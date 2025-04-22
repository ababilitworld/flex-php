<?php 
namespace Ababilithub\FlexPhp\Package\Contract\App\V1;

interface V1 
{
    public function register(): void;
    public function boot(): void;
    public function name(): string;
}
