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
use WildPHP\Messages\RPL\LUserChannels;

class LUserChannelsTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '254';
        $args = ['nickname', '5', 'channels formed'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_welcome = LUserChannels::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_welcome->getServer());
        $this->assertEquals('nickname', $rpl_welcome->getNickname());
        $this->assertEquals(5, $rpl_welcome->getCount());
        $this->assertEquals('channels formed', $rpl_welcome->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        LUserChannels::fromIncomingMessage($incomingIrcMessage);
    }
}
