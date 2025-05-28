<?php

namespace Ababilithub\FlexPhp\Package\Mixin\V1\Standard;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Singleton\Instance\Single\Mixin as Singleton,
    FlexPhp\Package\Mixin\V1\Data\Access\Static\Mixin as AccessStaticMember
};

trait Mixin 
{
    use Singleton, AccessStaticMember; 
}