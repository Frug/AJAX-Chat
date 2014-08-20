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
     * @return File
     * @throws Exception
     */
    public function createFile($path)
    {
        if (touch($path) === false) {
            throw new Exception(
                'can not create file "' . $path . '"'
            );
        }

        return $this->getFile($path);
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
     * taken from: https://github.com/stevleibelt/examples/blob/master/php/filesystem/listFilesInDirectory.php
     * @param string $path
     * @param array $blackList
     * @param bool $addPathToName
     * @return array
     */
    public function getDirectories($path, array $blackList = array(), $addPathToName = true)
    {
        $blackList = array_merge(
            $blackList,
            array(
                '.',
                '..'
            )
        );
        $directories = array();

        if (is_dir($path)) {
            if ($directoryHandle = opendir($path)) {
                while (false !== ($directory = readdir($directoryHandle))) {
                    if (is_dir($path . DIRECTORY_SEPARATOR . $directory)) {
                        if (!in_array($directory, $blackList)) {
                            if ($addPathToName) {
                                $directories[] = $path . DIRECTORY_SEPARATOR . $directory;
                            } else {
                                $directories[] = $directory;
                            }
                        }
                    }
                }
                closedir($directoryHandle);
            }
        }

        return $directories;
    }

    /**
     * taken from: https://github.com/stevleibelt/examples/blob/master/php/filesystem/listFilesInDirectory.php
     * @param string $path
     * @param array $blackList
     * @param bool $addPathToName
     * @return array
     */
    public function getFiles($path, array $blackList = array(), $addPathToName = true)
    {
        $files = array();

        if (is_dir($path)) {
            if ($directoryHandle = opendir($path)) {
                while (false !== ($file = readdir($directoryHandle))) {
                    if (is_file($path . DIRECTORY_SEPARATOR . $file)) {
                        if (!in_array($file, $blackList)) {
                            if ($addPathToName) {
                                $files[] = $path . DIRECTORY_SEPARATOR . $file;
                            } else {
                                $files[] = $file;
                            }
                        }
                    }
                }
                closedir($directoryHandle);
            }
        }

        return $files;
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

    /**
     * @param string $source
     * @param string $target
     * @throws Exception
     */
    public function move($source, $target)
    {
        if (rename($source, $target) === false) {
            throw new Exception(
                'can not move "' . $source . '" to "' . $target . '"'
            );
        }
    }
}