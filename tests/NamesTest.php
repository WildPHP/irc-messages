<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Names;

class NamesTest extends TestCase
{

    public function test__toString(): void
    {
        $names = new Names('#testChannel', 'testServer');

        $this->assertEquals(['#testChannel'], $names->getChannels());
        $this->assertEquals('testServer', $names->getServer());

        $expected = 'NAMES #testChannel testServer';
        $this->assertEquals($expected, $names->__toString());
    }
}
