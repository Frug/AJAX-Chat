<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Class File
 */
class File
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param null|string $path
     */
    public function __construct($path = null)
    {
        if (!is_null($path)) {
            $this->setPath($path);
        }
    }

    /**
     * @param string|array $content
     * @throws Exception
     */
    public function append($content)
    {
        if (is_array($content)) {
            $content = implode("\n", $content);
        }

        $numberOfBytes = file_put_contents($this->getPath(), $content, FILE_APPEND);

        if ($numberOfBytes === false) {
            throw new Exception(
                'can not append content to file "' . $this->getPath() . '"'
            );
        }
    }

    /**
     * @param string $path
     * @throws Exception
     */
    public function copy($path)
    {
        $couldBeCopied = copy($this->getPath(), $path);

        if ($couldBeCopied === false) {
            throw new Exception(
                'could not copy file from path "' . $this->getPath() . '" to "' . $path . '"'
            );
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function exists()
    {
        return (is_file($this->getPath()));
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getPath()
    {
        if (is_null($this->path)) {
            throw new Exception(
                'no path set'
            );
        }

        return $this->path;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function read()
    {
        return explode("\n", file_get_contents($this->getPath()));
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = (string) $path;
    }

    /**
     * @param string|array $content
     * @throws Exception
     */
    public function write($content)
    {
        if (is_array($content)) {
            $content = implode("\n", $content);
        }

        $numberOfBytes = file_put_contents($this->getPath(), $content);

        if ($numberOfBytes === false) {
            throw new Exception(
                'can not append content to file "' . $this->getPath() . '"'
            );
        }
    }
}