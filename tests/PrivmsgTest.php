<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Privmsg;

class PrivmsgTest extends TestCase
{
    public function test__toString(): void
    {
        $privmsg = new Privmsg('#somechannel', 'This is a test message');

        $this->assertEquals('#somechannel', $privmsg->getChannel());
        $this->assertEquals('This is a test message', $privmsg->getMessage());

        $expected = 'PRIVMSG #somechannel :This is a test message' . "\r\n";
        $this->assertEquals($expected, $privmsg->__toString());
    }

    public function test__toStringCTCP(): void
    {
        $privmsg = new Privmsg('#somechannel', 'This is a test message');
        $privmsg->setCtcpVerb('ACTION');
        $privmsg->setIsCtcp(true);

        $this->assertEquals('#somechannel', $privmsg->getChannel());
        $this->assertEquals('This is a test message', $privmsg->getMessage());
        $this->assertEquals('ACTION', $privmsg->getCtcpVerb());
        $this->assertTrue($privmsg->isCtcp());

        $expected = 'PRIVMSG #somechannel :' . "\x01" . 'ACTION This is a test message' . "\x01\r\n";
        $this->assertEquals($expected, $privmsg->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'PRIVMSG';
        $args = ['#somechannel', 'This is a test message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $privmsg = Privmsg::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $privmsg->getPrefix());
        $this->assertEquals('#somechannel', $privmsg->getChannel());
        $this->assertEquals('This is a test message', $privmsg->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Privmsg::fromIncomingMessage($incomingIrcMessage);
    }

    public function testFromIncomingMessageCTCP(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'PRIVMSG';
        $args = ['#somechannel', "\x01" . 'ACTION This is a test message' . "\x01"];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $privmsg = Privmsg::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $privmsg->getPrefix());
        $this->assertEquals('#somechannel', $privmsg->getChannel());
        $this->assertTrue($privmsg->isCtcp());
        $this->assertEquals('ACTION', $privmsg->getCtcpVerb());
        $this->assertEquals('This is a test message', $privmsg->getMessage());
    }
}
