<?php 
namespace Ababilithub\FlexPhp\Package\App\V1\Contract;

interface App 
{
    public function name(): string;    
    public function boot(): void;
    public function register(): void;
    
}
