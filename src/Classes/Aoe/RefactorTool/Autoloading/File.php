<?php
namespace Aoe\RefactorTool\Autoloading;

use Symfony\Component\Finder\SplFileInfo;

/**
 * @package Aoe\RefactorTool\Autoloading
 */
class File
{
    /**
     * @var \Symfony\Component\Finder\SplFileInfo
     */
    private $file;

    /**
     * @var array
     */
    private $lines = array();

    /**
     * @var boolean
     */
    private $isModified = false;

    /**
     * @param SplFileInfo $file
     */
    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
        $this->process();
    }

    /**
     * @return array
     */
    public function getAllLines()
    {
        return $this->lines;
    }

    /**
     * @return array
     */
    public function getLinesBetweenOpeningPHPAndClassDefinition()
    {
        $start = false;
        $end = false;
        $lines = array();
        foreach ($this->lines as $line) {
            /** @var Line $line */
            if ($line->isOpeningPHP()) {
                $start = true;
            }
            if ($start) {
                $lines[$line->getNumber()] = $line;
            }
            if ($line->isClassDefinition()) {
                $end = true;
                break;
            }
        }
        if (false === $end) {
            return array();
        }
        return $lines;
    }

    /**
     * @return boolean
     */
    public function isModified()
    {
        return $this->isModified;
    }

    /**
     * @return boolean
     */
    public function isPhpFile()
    {
        if ($this->file->isFile() && $this->file->getExtension() === 'php') {
            return true;
        }
        return false;
    }

    /**
     * @param Line $line
     */
    public function deleteLine(Line $line)
    {
        $this->isModified = true;
        unset($this->lines[$line->getNumber()]);
    }

    /**
     * @throws \Exception
     */
    public function write()
    {
        $handle = fopen($this->file->getRealPath(), 'w+');
        if ($handle) {
            foreach ($this->lines as $line) {
                /** @var Line $line */
                fwrite($handle, $line->getContents());
            }
        } else {
            throw new \Exception('cannot write file: ' . $this->file->getRealPath());
        }
        fclose($handle);
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function process()
    {
        $handle = fopen($this->file->getRealPath(), 'r');
        if ($handle) {
            $number = 1;
            while (($line = fgets($handle)) !== false) {
                $this->lines[$number] = new Line($line, $number);
                $number++;
            }
        } else {
            throw new \Exception('cannot open file: ' . $this->file->getRealPath());
        }
        fclose($handle);
    }
}
