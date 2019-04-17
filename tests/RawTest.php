<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
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
