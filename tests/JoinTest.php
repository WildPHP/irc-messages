<?php
/**
 * WildPHP - an advanced and easily extensible IRC bot written in PHP
 * Copyright (C) 2017 WildPHP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Join;
use PHPUnit\Framework\TestCase;

class JoinTest extends TestCase
{

    public function testGetRealname(): void
    {

    }

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

    public function testGetIrcAccount(): void
    {

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
        $this->expectException(\InvalidArgumentException::class);
        Join::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetKeys(): void
    {

    }
}
