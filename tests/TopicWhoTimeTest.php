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
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\RPL\TopicWhoTime;
use PHPUnit\Framework\TestCase;

class TopicWhoTimeTest extends TestCase
{

    public function testFromIncomingMessage()
    {
        $prefix = 'server';
        $verb = '333';
        $args = ['nickname', '#channel', 'nick!user@host', '5'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_welcome = TopicWhoTime::fromIncomingMessage($incoming);

        $expectedPrefix = new Prefix('nick', 'user', 'host');
        $this->assertEquals($expectedPrefix, $rpl_welcome->getPrefix());
        $this->assertEquals('server', $rpl_welcome->getServer());
        $this->assertEquals('nickname', $rpl_welcome->getNickname());
        $this->assertEquals('#channel', $rpl_welcome->getChannel());
        $this->assertEquals(5, $rpl_welcome->getTimestamp());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        TopicWhoTime::fromIncomingMessage($incomingIrcMessage);
    }
}
