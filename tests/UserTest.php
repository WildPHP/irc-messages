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
use WildPHP\Messages\User;

class UserTest extends TestCase
{
    public function test__toString(): void
    {
        $user = new User('myusername', 'localhost', 'someserver', 'arealname');

        $this->assertEquals('myusername', $user->getUsername());
        $this->assertEquals('localhost', $user->getHostname());
        $this->assertEquals('someserver', $user->getServername());
        $this->assertEquals('arealname', $user->getRealname());

        $expected = 'USER myusername localhost someserver :arealname' . "\r\n";
        $this->assertEquals($expected, $user->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = '';
        $verb = 'USER';
        $args = ['myusername', 'localhost', 'someserver', 'A real name'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $user = User::fromIncomingMessage($incoming);

        $this->assertEquals('myusername', $user->getUsername());
        $this->assertEquals('localhost', $user->getHostname());
        $this->assertEquals('someserver', $user->getServername());
        $this->assertEquals('A real name', $user->getRealname());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        User::fromIncomingMessage($incomingIrcMessage);
    }
}
