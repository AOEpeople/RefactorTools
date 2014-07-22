<?php
namespace Aoe\RefactorTool\Tests\Autoloading;

use Aoe\RefactorTool\Autoloading\File;
use Aoe\RefactorTool\Autoloading\Line;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @package Aoe\RefactorTool\Autoloading
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SplFileInfo
     */
    private $phpTestFile;
    /**
     * @var SplFileInfo
     */
    private $htmlTestFile;

    /**
     * Set up test file
     */
    public function setUp()
    {
        $this->phpTestFile = new SplFileInfo(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Fixtures/Foo.php',
            'Fixtures/',
            'Fixtures/Foo.php'
        );
        $this->htmlTestFile = new SplFileInfo(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Fixtures/Baz.html',
            'Fixtures/',
            'Fixtures/Foo.php'
        );
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\File::getAllLines
     */
    public function getAllLinesShouldHaveTheCorrectCount()
    {
        $file = new File($this->phpTestFile);
        $this->assertCount(28, $file->getAllLines());
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\File::getAllLines
     */
    public function getLinesBetweenOpeningPHPAndClassDefinitionShouldHaveTheCorrectCount()
    {
        $file = new File($this->phpTestFile);
        $this->assertCount(17, $file->getLinesBetweenOpeningPHPAndClassDefinition());
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\File::isModified
     */
    public function isModifiedShouldReturnFalseIfNoModificationsMade()
    {
        $file = new File($this->phpTestFile);
        $this->assertFalse($file->isModified());
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\File::isModified
     */
    public function isModifiedShouldReturnTrueIfALineIsDeleted()
    {
        $line = $this->getLineMock();
        $line->expects($this->once())->method('getNumber')->will($this->returnValue(10));
        $file = new File($this->phpTestFile);
        /** @var Line $line */
        $file->deleteLine($line);
        $this->assertTrue($file->isModified());
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\File::isPhpFile
     */
    public function isPhpFileReturnsTrueIfPhpFileExtensionGiven()
    {
        $file = new File($this->phpTestFile);
        $this->assertTrue($file->isPhpFile());
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\File::isPhpFile
     */
    public function isPhpFileReturnsFalseIfHtmlFileExtensionGiven()
    {
        $file = new File($this->htmlTestFile);
        $this->assertFalse($file->isPhpFile());
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\File::isModified
     */
    public function deleteLineReducesCount()
    {
        $line = $this->getLineMock();
        $line->expects($this->once())->method('getNumber')->will($this->returnValue(10));
        $file = new File($this->phpTestFile);
        /** @var Line $line */
        $file->deleteLine($line);
        $this->assertCount(27, $file->getAllLines());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getLineMock()
    {
        return $this->getMock(
            '\\Aoe\\RefactorTool\\Autoloading\\Line',
            array('getNumber', 'getContents'),
            array(),
            '',
            false
        );
    }
}
