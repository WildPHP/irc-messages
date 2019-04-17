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
use WildPHP\Messages\Part;

class PartTest extends TestCase
{
    public function test__toString(): void
    {
        $part = new Part(['#channel1', '#channel2'], 'I am out');

        $this->assertEquals(['#channel1', '#channel2'], $part->getChannels());
        $this->assertEquals('I am out', $part->getMessage());

        $expected = 'PART #channel1,#channel2 :I am out' . "\r\n";
        $this->assertEquals($expected, $part->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'PART';
        $args = ['#channel', 'I have a valid reason'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $part = Part::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $part->getPrefix());
        $this->assertEquals('nickname', $part->getNickname());
        $this->assertEquals(['#channel'], $part->getChannels());
        $this->assertEquals('I have a valid reason', $part->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Part::fromIncomingMessage($incomingIrcMessage);
    }
}
