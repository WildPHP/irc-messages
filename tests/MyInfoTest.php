<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\RPL\MyInfo;
use PHPUnit\Framework\TestCase;

class MyInfoTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '004';
        $args = ['nickname', 'server', '1.0', 'abc', 'def', 'ghi'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_welcome = MyInfo::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_welcome->getServer());
        $this->assertEquals('nickname', $rpl_welcome->getNickname());
        $this->assertEquals('1.0', $rpl_welcome->getVersion());
        $this->assertEquals(['a', 'b', 'c'], $rpl_welcome->getUserModes());
        $this->assertEquals(['d', 'e', 'f'], $rpl_welcome->getChannelModes());
        $this->assertEquals(['g', 'h', 'i'], $rpl_welcome->getChannelModesWithParameter());
    }

    public function testFromIncomingMessageWithFiveParameters(): void
    {
        $prefix = 'server';
        $verb = '004';
        $args = ['nickname', 'server', '1.0', 'abc', 'def'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_welcome = MyInfo::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_welcome->getServer());
        $this->assertEquals('nickname', $rpl_welcome->getNickname());
        $this->assertEquals('1.0', $rpl_welcome->getVersion());
        $this->assertEquals(['a', 'b', 'c'], $rpl_welcome->getUserModes());
        $this->assertEquals(['d', 'e', 'f'], $rpl_welcome->getChannelModes());
        $this->assertEquals([], $rpl_welcome->getChannelModesWithParameter());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        MyInfo::fromIncomingMessage($incomingIrcMessage);
    }
}
