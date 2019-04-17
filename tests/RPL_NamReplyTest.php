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
use WildPHP\Messages\RPL\NamReply;

class RPL_NamReplyTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', 'nickname1 nickname2 nickname3'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_namreply = NamReply::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_namreply->getServer());
        $this->assertEquals('nickname', $rpl_namreply->getNickname());
        $this->assertEquals('+', $rpl_namreply->getVisibility());
        $this->assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        NamReply::fromIncomingMessage($incomingIrcMessage);
    }
}
