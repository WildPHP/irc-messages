<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:45
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Privmsg;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function test__toString()
    {
        $privmsg = new Privmsg('#somechannel', 'This is a test message');

        $this->assertEquals('#somechannel', $privmsg->getChannel());
        $this->assertEquals('This is a test message', $privmsg->getMessage());

        $expected = 'PRIVMSG #somechannel :This is a test message' . "\r\n";
        $this->assertEquals($expected, $privmsg->__toString());
    }

    public function test__toStringCTCP()
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

    public function testFromIncomingMessage()
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

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Privmsg::fromIncomingMessage($incomingIrcMessage);
    }

    public function testFromIncomingMessageCTCP()
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
