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
use WildPHP\Messages\RPL\LoggedIn;

class LoggedInTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '900';
        $args = ['nickname', 'nick!user@host', 'account', 'You are now logged in'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_welcome = LoggedIn::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_welcome->getServer());
        $this->assertEquals('nickname', $rpl_welcome->getNickname());
        $this->assertEquals(Prefix::fromString('nick!user@host'), $rpl_welcome->getPrefix());
        $this->assertEquals('account', $rpl_welcome->getIrcAccount());
        $this->assertEquals('You are now logged in', $rpl_welcome->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        LoggedIn::fromIncomingMessage($incomingIrcMessage);
    }
}
