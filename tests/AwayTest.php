<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 21/01/2019
 * Time: 15:27
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Away;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;

class AwayTest extends TestCase
{

    public function test__toString()
    {
        $away = new Away('A sample message');

        $this->assertEquals('A sample message', $away->getMessage());

        $expected = 'AWAY :A sample message' . "\r\n";
        $this->assertEquals($expected, $away->__toString());
    }

    public function testFromIncomingMessage()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'AWAY';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $away = Away::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $away->getNickname());
        $this->assertEquals('A sample message', $away->getMessage());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Away::fromIncomingMessage($incomingIrcMessage);
    }
}
