<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-15 
 */

/**
 * Class Filesystem
 */
class Filesystem
{
    /**
     * @param string $source
     * @param string $target
     * @throws Exception
     */
    public function copy($source, $target)
    {
        if (copy($source, $target) === false) {
            throw new Exception(
                'can not copy "' . $source . '" to "' . $target . '"'
            );
        }
    }

    /**
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @throws Exception
     */
    public function createDirectory($path, $mode = 0777, $recursive = false)
    {
        if (mkdir($path, $mode, $recursive) === false) {
            throw new Exception(
                'can not create directory "' . $path . '"'
            );
        }
    }

    /**
     * @param string $path
     * @throws Exception
     */
    public function createFile($path)
    {
        if (touch($path) === false) {
            throw new Exception(
                'can not create file "' . $path . '"'
            );
        }
    }

    /**
     * @param string $path
     * @throws Exception
     */
    public function deleteDirectory($path)
    {
        if (rmdir($path) === false) {
            throw new Exception(
                'can not delete directory "' . $path . '"'
            );
        }
    }

    /**
     * @param string $path
     * @throws Exception
     */
    public function deleteFile($path)
    {
        if (unlink($path) === false) {
            throw new Exception(
                'can not unlink "' . $path . '"'
            );
        }
    }

    /**
     * This method is not bulletproof, it simple checks if $path is a directory
     * @param string $path
     * @return File
     * @throws Exception
     */
    public function getFile($path)
    {
        if ($this->isDirectory($path)) {
            throw new Exception(
                'provided path "' . $path . '" is a directory'
            );
        }

        $file = new File();
        $file->setPath($path);

        return $file;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isDirectory($path)
    {
        return is_dir($path);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isFile($path)
    {
        return is_file($path);
    }
}