<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:45
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Pong;
use PHPUnit\Framework\TestCase;

class PongTest extends TestCase
{
    public function test__toString(): void
    {
        $pong = new Pong('testserver1', 'testserver2');

        $this->assertEquals('testserver1', $pong->getServer1());
        $this->assertEquals('testserver2', $pong->getServer2());

        $expected = 'PONG testserver1 testserver2' . "\r\n";
        $this->assertEquals($expected, $pong->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = '';
        $verb = 'PONG';
        $args = ['testserver1', 'testserver2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $pong = Pong::fromIncomingMessage($incoming);

        $this->assertEquals('testserver1', $pong->getServer1());
        $this->assertEquals('testserver2', $pong->getServer2());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Pong::fromIncomingMessage($incomingIrcMessage);
    }
}
