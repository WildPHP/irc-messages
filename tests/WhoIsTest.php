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
use WildPHP\Messages\WhoIs;

class WhoIsTest extends TestCase
{
    public function test__toString(): void
    {
        $whois = new WhoIs(['nickname1', 'nickname2'], 'server');
        $this->assertEquals(['nickname1', 'nickname2'], $whois->getNicknames());
        $this->assertEquals('server', $whois->getServer());

        $expected = 'WHOIS server nickname1,nickname2';
        $this->assertEquals($expected, $whois->__toString());

        $whois = new WhoIs('nickname1', 'server');
        $this->assertEquals(['nickname1'], $whois->getNicknames());
    }
}
