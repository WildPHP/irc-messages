<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:46
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Raw;
use PHPUnit\Framework\TestCase;

class RawTest extends TestCase
{
    public function test__toString(): void
    {
        // silence this, since raw throws a warning.
        $raw = @new Raw('a command');

        $this->assertEquals('a command', $raw->getCommand());

        $expected = 'a command' . "\r\n";
        $this->assertEquals($expected, $raw->__toString());
    }
}
