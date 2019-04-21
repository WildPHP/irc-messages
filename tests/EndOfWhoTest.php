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
use WildPHP\Messages\RPL\EndOfWho;

class EndOfWhoTest extends TestCase
{
    public function testFromIncomingMessage()
    {
        $prefix = 'server';
        $verb = '315';
        $args = ['nickname', '#channel', 'End of WHO'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_welcome = EndOfWho::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_welcome->getServer());
        $this->assertEquals('nickname', $rpl_welcome->getNickname());
        $this->assertEquals('#channel', $rpl_welcome->getChannel());
        $this->assertEquals('End of WHO', $rpl_welcome->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        EndOfWho::fromIncomingMessage($incomingIrcMessage);
    }
}
