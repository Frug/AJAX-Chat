<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-19
 */

/**
 * Class Output
 */
class Output
{
    /**
     * @var array
     */
    private $content;

    /**
     * @var string
     */
    private $indention;

    public function __construct()
    {
        $this->content = array();
        $this->indention = '    ';
    }

    public function __clone()
    {
        $this->content = array();
    }

    /**
     * @param string $line
     * @param int $numberOfIndention
     */
    public function addLine($line = '', $numberOfIndention = 0)
    {
        $this->content[] = (str_repeat($this->indention, $numberOfIndention)) . $line;
    }

    /**
     * @param string $indention
     */
    public function setIndention($indention)
    {
        $this->indention = (string) $indention;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->content;
    }
} 