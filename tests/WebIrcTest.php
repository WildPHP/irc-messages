<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use WildPHP\Messages\WebIrc;
use PHPUnit\Framework\TestCase;

class WebIrcTest extends TestCase
{
    public function test__toString(): void
    {
        $webIrc = new WebIrc('password', 'gateway', 'hostname', 'ip');
        $this->assertEquals('password', $webIrc->getPassword());
        $this->assertEquals('gateway', $webIrc->getGateway());
        $this->assertEquals('hostname', $webIrc->getHostname());
        $this->assertEquals('ip', $webIrc->getIp());

        $expected = 'WEBIRC password gateway hostname ip';
        $this->assertEquals($expected, $webIrc->__toString());
    }
}
