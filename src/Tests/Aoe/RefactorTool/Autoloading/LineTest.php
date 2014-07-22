<?php
namespace Aoe\RefactorTool\Tests\Autoloading;

use Aoe\RefactorTool\Autoloading\Line;

/**
 * @package Aoe\RefactorTool\Autoloading
 */
class LineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\Line::getNumber
     */
    public function getNumberShouldReturnPreviouslySetValue()
    {
        $line = new Line('lorem ipsum dolor sit...', 4711);
        $this->assertEquals(4711, $line->getNumber());
    }

    /**
     * @test
     * @covers \Aoe\RefactorTool\Autoloading\Line::getContents
     */
    public function getContentsShouldReturnPreviouslySetValue()
    {
        $line = new Line('lorem ipsum dolor sit...', 4711);
        $this->assertEquals('lorem ipsum dolor sit...', $line->getContents());
    }

    /**
     * @test
     * @dataProvider provideValidRequireLines
     * @covers       \Aoe\RefactorTool\Autoloading\Line::isRequire
     */
    public function isRequireShouldReturnTrueOnValidRequireLines($contents)
    {
        $line = new Line($contents, 4711);
        $this->assertTrue($line->isRequire());
    }

    /**
     * @test
     * @dataProvider provideInvalidRequireLines
     * @covers       \Aoe\RefactorTool\Autoloading\Line::isRequire
     */
    public function isRequireShouldReturnFalseOnInvalidRequireLines($contents)
    {
        $line = new Line($contents, 4711);
        $this->assertFalse($line->isRequire());
    }

    /**
     * @test
     * @dataProvider provideValidClassDefinitionLines
     * @covers       \Aoe\RefactorTool\Autoloading\Line::isClassDefinition
     */
    public function isClassDefinitionShouldReturnTrueOnValidClassDefinitionLines($contents)
    {
        $line = new Line($contents, 4711);
        $this->assertTrue($line->isClassDefinition());
    }

    /**
     * @test
     * @dataProvider provideInvalidClassDefinitionLines
     * @covers       \Aoe\RefactorTool\Autoloading\Line::isClassDefinition
     */
    public function isClassDefinitionShouldReturnFalseOnInvalidClassDefinitionLines($contents)
    {
        $line = new Line($contents, 4711);
        $this->assertFalse($line->isClassDefinition());
    }

    /**
     * @test
     * @dataProvider provideValidPhpTagLines
     * @covers       \Aoe\RefactorTool\Autoloading\Line::isOpeningPHP
     */
    public function isOpeningPHPShouldReturnTrueOnValidClassDefinitionLines($contents)
    {
        $line = new Line($contents, 4711);
        $this->assertTrue($line->isOpeningPHP());
    }

    /**
     * @test
     * @dataProvider provideInvalidPhpTagLines
     * @covers       \Aoe\RefactorTool\Autoloading\Line::isOpeningPHP
     */
    public function isOpeningPHPDefinitionShouldReturnFalseOnInvalidClassDefinitionLines($contents)
    {
        $line = new Line($contents, 4711);
        $this->assertFalse($line->isOpeningPHP());
    }

    /**
     * @test
     * @dataProvider provideValidMatches
     * @covers       \Aoe\RefactorTool\Autoloading\Line::matches
     */
    public function matchesReturnTrueIfExpressionMatch($contents, $expression)
    {
        $line = new Line($contents, 4711);
        $this->assertTrue($line->matches($expression));
    }

    /**
     * @test
     * @dataProvider provideInvalidMatches
     * @covers       \Aoe\RefactorTool\Autoloading\Line::matches
     */
    public function matchesReturnFalseIfExpressionNotMatch($contents, $expression)
    {
        $line = new Line($contents, 4711);
        $this->assertFalse($line->matches($expression));
    }

    /**
     * @return array
     */
    public function provideValidRequireLines()
    {
        return array(
            array('require_once \'path/to/something.php\';'),
            array('require_once "path/to/something.php";'),
            array('require_once (\'path/to/something.php\');'),
            array('require_once(\'path/to/something.php\');'),
            array('require_once      (\'path/to/something.php\');'),
            array('require_once ( \'path/to/something.php\' );'),
        );
    }

    /**
     * @return array
     */
    public function provideInvalidRequireLines()
    {
        return array(
            array('require_once \'path/to/something.php\''),
            array('require_once\'path/to/something.php\';'),
            array(' require_once \'path/to/something.php\';'),
        );
    }

    /**
     * @return array
     */
    public function provideValidClassDefinitionLines()
    {
        return array(
            array('class Foo {'),
            array('class Foo'),
            array('class  Foo'),
            array('class F_o_o'),
        );
    }

    /**
     * @return array
     */
    public function provideInvalidClassDefinitionLines()
    {
        return array(
            array('classFoo {'),
            array('classFoo'),
            array(' class Foo'),
            array(' class Foo {'),
        );
    }

    /**
     * @return array
     */
    public function provideValidPhpTagLines()
    {
        return array(
            array('<?php'),
            array('<?php '),
        );
    }

    /**
     * @return array
     */
    public function provideInvalidPhpTagLines()
    {
        return array(
            array(' <?php'),
            array('<? php'),
            array('<? php'),
            array('<?php asdf'),
        );
    }

    /**
     * @return array
     */
    public function provideValidMatches()
    {
        return array(
            array('require_once path/to/my/project/foo.php', '~my\/project~'),
            array('require_once path/to/my/project/foo.php', '~foo\.php~'),
        );
    }

    /**
     * @return array
     */
    public function provideInvalidMatches()
    {
        return array(
            array('require_once path/to/my/project/foo.php', '~non\/existing~'),
            array('require_once path/to/my/project/foo.php', '~bar\.php~'),
        );
    }
}
