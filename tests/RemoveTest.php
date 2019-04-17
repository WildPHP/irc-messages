<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Remove;

class RemoveTest extends TestCase
{
    public function test__toString(): void
    {
        $remove = new Remove('#channel', 'nickname', 'Get out!');

        $this->assertEquals('#channel', $remove->getChannel());
        $this->assertEquals('nickname', $remove->getTarget());
        $this->assertEquals('Get out!', $remove->getMessage());

        $expected = 'REMOVE #channel nickname :Get out!' . "\r\n";
        $this->assertEquals($expected, $remove->__toString());
    }
}
