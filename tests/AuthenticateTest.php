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
use WildPHP\Messages\Authenticate;
use WildPHP\Messages\Generics\IrcMessage;

class AuthenticateTest extends TestCase
{

    public function testFromIncomingMessage(): void
    {
        $prefix = '';
        $verb = 'AUTHENTICATE';
        $args = ['+'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $authenticate = Authenticate::fromIncomingMessage($incoming);

        $this->assertEquals('+', $authenticate->getResponse());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Authenticate::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetSetResponse(): void
    {
        $authenticate = new Authenticate('+');

        $response = '-';
        $authenticate->setResponse($response);
        $this->assertEquals($response, $authenticate->getResponse());
    }

    public function test__toString(): void
    {
        $authenticate = new Authenticate('+');

        $this->assertEquals('+', $authenticate->getResponse());

        $expected = 'AUTHENTICATE +' . "\r\n";
        $this->assertEquals($expected, $authenticate->__toString());
    }
}
