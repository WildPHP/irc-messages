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
use WildPHP\Messages\RPL\EndOfNames;
use PHPUnit\Framework\TestCase;

class RPL_EndOfNamesTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '366';
        $args = ['nickname', '#channel', 'End of /NAMES list.'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_endofnames = EndOfNames::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $rpl_endofnames->getNickname());
        $this->assertEquals('#channel', $rpl_endofnames->getChannel());
        $this->assertEquals('End of /NAMES list.', $rpl_endofnames->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        EndOfNames::fromIncomingMessage($incomingIrcMessage);
    }
}
