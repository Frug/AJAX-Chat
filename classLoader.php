<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

if (function_exists('spl_autoload_register')) {
    //own autoloader - since support is from php 5 up, no namespaces are available so back to pear style
    function classLoader($className) {
        $pathToScript = __DIR__;

        $directories = array(
            $pathToScript . DIRECTORY_SEPARATOR . 'source'
        );

        $classNameAsPath = str_replace('_', DIRECTORY_SEPARATOR, $className);

        foreach ($directories as $directory) {
            $filePath = $directory . DIRECTORY_SEPARATOR . $classNameAsPath . '.php';

            if (is_file($filePath)) {
                require_once $filePath;
                break;
            }
        }

        throw new Exception(
            'can not load class "' . $className . '"'
        );
    }
    spl_autoload_register('classLoader');
}
