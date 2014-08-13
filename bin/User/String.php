<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Class String
 */
class String
{
    /**
     * @param string $string
     * @param string $prefix
     * @return bool
     */
    public function startsWith($string, $prefix)
    {
        return (strncmp($string, $prefix, strlen($prefix)) === 0);
    }
} 