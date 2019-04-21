<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
declare(strict_types=1);

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Invite;

class InviteTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'INVITE';
        $args = ['nickname2', '#channel'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $invite = Invite::fromIncomingMessage($incoming);

        $this->assertEquals('nickname2', $invite->getNickname());
        $this->assertEquals('#channel', $invite->getChannel());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Invite::fromIncomingMessage($incomingIrcMessage);
    }
}
