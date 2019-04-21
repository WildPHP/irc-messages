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
use WildPHP\Messages\RPL\EndOfMotd;
use PHPUnit\Framework\TestCase;

class EndOfMotdTest extends TestCase
{
    public function testFromIncomingMessage()
    {
        $prefix = 'server';
        $verb = '376';
        $args = ['nickname', 'End of MOTD'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $motd = EndOfMotd::fromIncomingMessage($incoming);

        $this->assertEquals('server', $motd->getServer());
        $this->assertEquals('nickname', $motd->getNickname());
        $this->assertEquals('End of MOTD', $motd->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        EndOfMotd::fromIncomingMessage($incomingIrcMessage);
    }
}
