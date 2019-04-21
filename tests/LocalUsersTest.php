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
use WildPHP\Messages\RPL\LocalUsers;

class LocalUsersTest extends TestCase
{
    public function testFromIncomingMessage2Parameters()
    {
        $prefix = 'server';
        $verb = '265';
        $args = ['nickname', 'message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $localUsers = LocalUsers::fromIncomingMessage($incoming);

        $this->assertEquals('server', $localUsers->getServer());
        $this->assertEquals('nickname', $localUsers->getNickname());
        $this->assertEquals('message', $localUsers->getMessage());
    }

    public function testFromIncomingMessage4Parameters()
    {
        $prefix = 'server';
        $verb = '265';
        $args = ['nickname', '5', '6', 'message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $localUsers = LocalUsers::fromIncomingMessage($incoming);

        $this->assertEquals('server', $localUsers->getServer());
        $this->assertEquals('nickname', $localUsers->getNickname());
        $this->assertEquals(5, $localUsers->getCurrentUsers());
        $this->assertEquals(6, $localUsers->getMaximumUsers());
        $this->assertEquals('message', $localUsers->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        LocalUsers::fromIncomingMessage($incomingIrcMessage);
    }
}
