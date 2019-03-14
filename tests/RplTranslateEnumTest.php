<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 07/11/2018
 * Time: 16:06
 */

namespace WildPHP\Tests;

use WildPHP\Messages\RPL\Topic;
use WildPHP\Messages\Utility\RplTranslateEnum;
use PHPUnit\Framework\TestCase;

class RplTranslateEnumTest extends TestCase
{
    public function testTranslate(): void
    {
        $expected = Topic::class;

        $this->assertEquals($expected, RplTranslateEnum::translateNumeric('332'));
    }

    public function testTranslateNotFound(): void
    {
        $expected = false;

        $this->assertEquals($expected, RplTranslateEnum::translateNumeric('999'));
    }
}
