<?php
namespace Aoe\RefactorTool\Autoloading;

/**
 * @package Aoe\RefactorTool\Autoloading
 */
class Line
{
    /**
     * @var string
     */
    private $contents;

    /**
     * @var integer
     */
    private $number;

    /**
     * @param string $contents
     * @param integer $number
     */
    public function __construct($contents, $number)
    {
        $this->contents = $contents;
        $this->number = $number;
    }

    /**
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return boolean
     */
    public function isRequire()
    {
        if (preg_match('~^require_once[\s*\(].*[\)]?;~', $this->contents)) {
            return true;
        }
        if (preg_match('~^require[\s*\(].*[\)]?;~', $this->contents)) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function isClassDefinition()
    {
        if (preg_match('~^class\s+[A-z]~', $this->contents)) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function isOpeningPHP()
    {
        if (preg_match('~^\<\?php\s*$~', $this->contents)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $pattern
     * @return boolean
     */
    public function matches($pattern)
    {
        if (preg_match($pattern, $this->contents)) {
            return true;
        }
        return false;
    }
}
