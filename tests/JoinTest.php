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
use WildPHP\Messages\Join;

class JoinTest extends TestCase
{
    public function testJoinCreateKeyMismatch(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Join(['#channel1', '#channel2'], ['key1']);
    }

    public function testCreateArray(): void
    {
        $join = new Join(['#channel1', '#channel2'], ['key1', 'key2']);

        $this->assertEquals(['#channel1', '#channel2'], $join->getChannels());
        $this->assertEquals(['key1', 'key2'], $join->getKeys());
    }

    public function testCreateString(): void
    {
        $join = new Join('#channel1', 'key1');

        $this->assertEquals(['#channel1'], $join->getChannels());
        $this->assertEquals(['key1'], $join->getKeys());
    }

    public function test__toString(): void
    {
        $join = new Join(['#channel1', '#channel2'], ['key1', 'key2']);

        $expected = 'JOIN #channel1,#channel2 key1,key2' . "\r\n";
        $this->assertEquals($expected, $join->__toString());
    }

    public function testFromIncomingMessageExtended(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'JOIN';
        $args = ['#channel', 'ircAccountName', 'realname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $join = Join::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $join->getNickname());
        $this->assertEquals(['#channel'], $join->getChannels());
        $this->assertEquals('ircAccountName', $join->getIrcAccount());
        $this->assertEquals('realname', $join->getRealname());
    }

    public function testFromIncomingMessageRegular(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'JOIN';
        $args = ['#channel'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $join = Join::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $join->getNickname());
        $this->assertEquals(['#channel'], $join->getChannels());
        $this->assertEquals('', $join->getIrcAccount());
        $this->assertEquals('', $join->getRealname());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Join::fromIncomingMessage($incomingIrcMessage);
    }
}
