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
use WildPHP\Messages\RPL\Welcome;
use PHPUnit\Framework\TestCase;

class RPL_WelcomeTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '001';
        $args = ['nickname', 'Welcome to server!'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_welcome = Welcome::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_welcome->getServer());
        $this->assertEquals('nickname', $rpl_welcome->getNickname());
        $this->assertEquals('Welcome to server!', $rpl_welcome->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Welcome::fromIncomingMessage($incomingIrcMessage);
    }
}
