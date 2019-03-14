<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:44
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Ping;
use PHPUnit\Framework\TestCase;

class PingTest extends TestCase
{
    public function test__toString()
    {
        $ping = new Ping('testserver1', 'testserver2');

        $this->assertEquals('testserver1', $ping->getServer1());
        $this->assertEquals('testserver2', $ping->getServer2());

        $expected = 'PING testserver1 testserver2' . "\r\n";
        $this->assertEquals($expected, $ping->__toString());
    }

    public function testFromIncomingMessage()
    {
        $prefix = '';
        $verb = 'PING';
        $args = ['testserver1', 'testserver2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $ping = Ping::fromIncomingMessage($incoming);

        $this->assertEquals('testserver1', $ping->getServer1());
        $this->assertEquals('testserver2', $ping->getServer2());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Ping::fromIncomingMessage($incomingIrcMessage);
    }
}
