<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Pass;

class PassTest extends TestCase
{
    public function test__toString(): void
    {
        $pass = new Pass('myseekritpassw0rd');

        $this->assertEquals('myseekritpassw0rd', $pass->getPassword());

        $expected = 'PASS :myseekritpassw0rd' . "\r\n";
        $this->assertEquals($expected, $pass->__toString());
    }
}
