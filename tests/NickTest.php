<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Nick;
use PHPUnit\Framework\TestCase;

class NickTest extends TestCase
{
    public function test__toString(): void
    {
        $nick = new Nick('newnickname');

        $this->assertEquals('newnickname', $nick->getNewNickname());

        $expected = 'NICK newnickname' . "\r\n";
        $this->assertEquals($expected, $nick->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'NICK';
        $args = ['newnickname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $nick = Nick::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $nick->getPrefix());
        $this->assertEquals('nickname', $nick->getNickname());
        $this->assertEquals('newnickname', $nick->getNewNickname());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];

        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Nick::fromIncomingMessage($incomingIrcMessage);
    }
}
