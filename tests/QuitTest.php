<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
declare(strict_types=1);

namespace WildPHP\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Quit;

class QuitTest extends TestCase
{
    public function test__toString(): void
    {
        $quit = new Quit('A sample message');

        $this->assertEquals('A sample message', $quit->getMessage());

        $expected = 'QUIT :A sample message' . "\r\n";
        $this->assertEquals($expected, $quit->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'QUIT';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $quit = Quit::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $quit->getPrefix());
        $this->assertEquals('nickname', $quit->getNickname());
        $this->assertEquals('A sample message', $quit->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Quit::fromIncomingMessage($incomingIrcMessage);
    }
}
