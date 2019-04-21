<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\RPL\Motd;

class MotdTest extends TestCase
{
    public function testFromIncomingMessage()
    {
        $prefix = 'server';
        $verb = '372';
        $args = ['nickname', '- This is a test motd'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $motd = Motd::fromIncomingMessage($incoming);

        $this->assertEquals('server', $motd->getServer());
        $this->assertEquals('nickname', $motd->getNickname());
        $this->assertEquals('This is a test motd', $motd->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Motd::fromIncomingMessage($incomingIrcMessage);
    }
}
