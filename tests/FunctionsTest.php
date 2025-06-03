<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/functions.php';

class FunctionsTest extends TestCase
{
    public static function titleProvider()
    {
        return [
            ['Hello World', 'hello-world'],
            ['  Trim Spaces  ', 'trim-spaces'],
            ['Multiple   Spaces', 'multiple---spaces'],
            ['Mixed CASE', 'mixed-case'],
            ['Special_chars!', 'special_chars!'],
            ['', ''],
        ];
    }

    /**
     * @dataProvider titleProvider
     */
    public function testFormatTitle($input, $expected)
    {
        $this->assertSame($expected, formatTitle($input));
    }
}

