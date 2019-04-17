<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\WebIrc;

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

    public function testGetSetPassword()
    {
        $webIrc = new WebIrc('password', 'gateway', 'hostname', 'ip');
        $this->assertEquals('password', $webIrc->getPassword());

        $webIrc->setPassword('hunter2');
        $this->assertEquals('hunter2', $webIrc->getPassword());
    }

    public function testGetSetGateway()
    {
        $webIrc = new WebIrc('password', 'gateway', 'hostname', 'ip');
        $this->assertEquals('gateway', $webIrc->getGateway());

        $webIrc->setGateway('gateway2');
        $this->assertEquals('gateway2', $webIrc->getGateway());
    }

    public function testGetSetHostname()
    {
        $webIrc = new WebIrc('password', 'gateway', 'hostname', 'ip');
        $this->assertEquals('hostname', $webIrc->getHostname());

        $webIrc->setHostname('hostname2');
        $this->assertEquals('hostname2', $webIrc->getHostname());
    }

    public function testGetSetIp()
    {
        $webIrc = new WebIrc('password', 'gateway', 'hostname', 'ip');
        $this->assertEquals('ip', $webIrc->getIp());

        $webIrc->setIp('ip2');
        $this->assertEquals('ip2', $webIrc->getIp());
    }
}
