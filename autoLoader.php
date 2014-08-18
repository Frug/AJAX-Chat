<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

if (function_exists('spl_autoload_register')) {
    //own autoloader - since support is from php 5 up, no namespaces are available so back to pear style
    function classLoader($className) {
        $classNameAsPath = str_replace('_', DIRECTORY_SEPARATOR, $className);
        $directories = array(
            __DIR__ . DIRECTORY_SEPARATOR . 'source',
            __DIR__ . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'class'
        );
        $isLoaded = false;
        $filePath = '';

        foreach ($directories as $directory) {
            $filePath = $directory . DIRECTORY_SEPARATOR . $classNameAsPath . '.php';

            if (is_file($filePath)) {
                require_once $filePath;
                $isLoaded = true;
                break;
            }
        }

        if (!$isLoaded) {
            throw new Exception(
                'can not load class "' . $className . '"' . PHP_EOL .
                'last try with path "' . $filePath . '"'
            );
        }
    }
    spl_autoload_register('classLoader');
} else {
    throw new Exception(
        'spl_autoload_register function is missing but needed'
    );
}
