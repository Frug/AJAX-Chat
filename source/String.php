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

    /**
     * @param string $string
     * @param int $start
     * @param null|int $length
     * @return string
     */
    public function cut($string, $start = 0, $length = 0)
    {
        if (($length === 0)) {
            $part = substr($string, $start);
        } else {
            $part = substr($string, $start, $length);
        }

        return $part;
    }
}