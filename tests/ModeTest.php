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
use WildPHP\Messages\Mode;

class ModeTest extends TestCase
{

    public function test__toString(): void
    {
        $mode = new Mode('target', '-o+b', ['arg1', 'arg2']);

        $this->assertEquals('target', $mode->getTarget());
        $this->assertEquals('-o+b', $mode->getFlags());
        $this->assertEquals(['arg1', 'arg2'], $mode->getArguments());

        $expected = 'MODE target -o+b arg1 arg2' . "\r\n";
        $this->assertEquals($expected, $mode->__toString());
    }

    public function testFromIncomingMessageChannel(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'MODE';
        $args = ['#channel', '-o+b', 'arg1', 'arg2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $mode = Mode::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $mode->getPrefix());
        $this->assertEquals('#channel', $mode->getTarget());
        $this->assertEquals('nickname', $mode->getNickname());
        $this->assertEquals('-o+b', $mode->getFlags());
        $this->assertEquals(['arg1', 'arg2'], $mode->getArguments());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Mode::fromIncomingMessage($incomingIrcMessage);
    }

    public function testFromIncomingMessageUser(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'MODE';
        $args = ['user', '-o+b'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $mode = Mode::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $mode->getPrefix());
        $this->assertEquals('user', $mode->getTarget());
        $this->assertEquals('nickname', $mode->getNickname());
        $this->assertEquals('-o+b', $mode->getFlags());
        $this->assertEquals([], $mode->getArguments());
    }

    public function testFromIncomingMessageInitial(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'MODE';
        $args = ['nickname', '-o+b'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $mode = Mode::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $mode->getTarget());
        $this->assertEquals('nickname', $mode->getNickname());
        $this->assertEquals('-o+b', $mode->getFlags());
        $this->assertEquals([], $mode->getArguments());
    }
}
