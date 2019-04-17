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
use WildPHP\Messages\Error;
use WildPHP\Messages\Generics\IrcMessage;

class ErrorTest extends TestCase
{

    public function testFromIncomingMessage(): void
    {
        $prefix = '';
        $verb = 'ERROR';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $error = Error::fromIncomingMessage($incoming);

        $this->assertEquals('A sample message', $error->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Error::fromIncomingMessage($incomingIrcMessage);
    }
}
