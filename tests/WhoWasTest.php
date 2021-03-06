<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
declare(strict_types=1);

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\WhoWas;

class WhoWasTest extends TestCase
{
    public function test__toString(): void
    {
        $whowas = new WhoWas(['nickname1', 'nickname2'], 2, 'server');
        $this->assertEquals(['nickname1', 'nickname2'], $whowas->getNicknames());
        $this->assertEquals(2, $whowas->getCount());
        $this->assertEquals('server', $whowas->getServer());

        $expected = 'WHOWAS nickname1,nickname2 2 server';
        $this->assertEquals($expected, $whowas->__toString());

        $whowas = new WhoWas('nickname1', 2, 'server');
        $this->assertEquals(['nickname1'], $whowas->getNicknames());
    }
}
