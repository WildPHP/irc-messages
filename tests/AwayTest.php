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
use WildPHP\Messages\Away;
use WildPHP\Messages\Generics\IrcMessage;

class AwayTest extends TestCase
{

    public function test__toString(): void
    {
        $away = new Away('A sample message');

        $this->assertEquals('A sample message', $away->getMessage());

        $expected = 'AWAY :A sample message' . "\r\n";
        $this->assertEquals($expected, $away->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'AWAY';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $away = Away::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $away->getNickname());
        $this->assertEquals('A sample message', $away->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Away::fromIncomingMessage($incomingIrcMessage);
    }
}
