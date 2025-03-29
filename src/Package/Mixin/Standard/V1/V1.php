<?php

namespace Ababilithub\FlexPhp\Package\Mixin\Standard\V1;

use AbabilIthub\FlexPhp\Package\Mixin\Singleton\Instance\Single\V1\V1 as Singleton;
use Ababilithub\FlexPhp\Package\Mixin\Data\Access\Static\V1\V1 as AccessStaticMember;

trait V1 
{
    use Singleton, AccessStaticMember; 
}