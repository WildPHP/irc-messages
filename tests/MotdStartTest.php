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
use WildPHP\Messages\RPL\MotdStart;
use PHPUnit\Framework\TestCase;

class MotdStartTest extends TestCase
{
    public function testFromIncomingMessage()
    {
        $prefix = 'server';
        $verb = '375';
        $args = ['nickname', '- server Message Of The Day -'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $motd = MotdStart::fromIncomingMessage($incoming);

        $this->assertEquals('server', $motd->getServer());
        $this->assertEquals('nickname', $motd->getNickname());
        $this->assertEquals('server Message Of The Day', $motd->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        MotdStart::fromIncomingMessage($incomingIrcMessage);
    }
}
