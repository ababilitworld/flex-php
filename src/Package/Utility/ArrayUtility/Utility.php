<?php
namespace Ababilithub\FlexPhp\Package\Utility\ArrayUtility;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
};
class Utility
{
    use StandardMixin;

    public static function search_array(array $needleArray, array $haystack, $keys = 'id'): array|bool    
    {
        // Single key search
        if (is_string($keys)) 
        {
            return in_array($needleArray[$keys], array_column($haystack, $keys));
        }
        
        // Multiple key search
        return array_filter( 
         $haystack, 
      function($item) use ($needleArray, $keys) 
                {
                    foreach ($keys as $key) 
                    {
                        if (!isset($item[$key]) || $item[$key] !== $needleArray[$key]) 
                        {
                            return false;
                        }
                    }
                    return true;
                }
        );

    }
}
