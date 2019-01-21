<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 21/01/2019
 * Time: 15:41
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Kick;
use PHPUnit\Framework\TestCase;

class KickTest extends TestCase
{

    public function test__toString()
    {
        $kick = new Kick('#channel', 'nickname', 'Bleep you!');

        $this->assertEquals('#channel', $kick->getChannel());
        $this->assertEquals('nickname', $kick->getTarget());
        $this->assertEquals('Bleep you!', $kick->getMessage());

        $expected = 'KICK #channel nickname :Bleep you!' . "\r\n";
        $this->assertEquals($expected, $kick->__toString());
    }

    public function testGetTarget()
    {
        $kick = new Kick('#channel', 'nickname', 'Bleep you!');
        $this->assertEquals('nickname', $kick->getTarget());

        $kick->setTarget('othername');
        $this->assertEquals('othername', $kick->getTarget());
    }

    public function testFromIncomingMessage()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'KICK';
        $args = ['#somechannel', 'othernickname', 'You deserved it!'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $kick = Kick::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $kick->getPrefix());
        $this->assertEquals('nickname', $kick->getNickname());
        $this->assertEquals('othernickname', $kick->getTarget());
        $this->assertEquals('#somechannel', $kick->getChannel());
        $this->assertEquals('You deserved it!', $kick->getMessage());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Kick::fromIncomingMessage($incomingIrcMessage);
    }
}
