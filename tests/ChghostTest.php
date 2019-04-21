<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Chghost;
use WildPHP\Messages\Generics\IrcMessage;

class ChghostTest extends TestCase
{
    public function testFromIncomingMessage()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'CHGHOST';
        $args = ['newUsername', 'newHostname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $chghost = Chghost::fromIncomingMessage($incoming);

        $this->assertEquals('newUsername', $chghost->getNewUsername());
        $this->assertEquals('newHostname', $chghost->getNewHostname());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Chghost::fromIncomingMessage($incomingIrcMessage);
    }
}
