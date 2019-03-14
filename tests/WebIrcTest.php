<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:53
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
