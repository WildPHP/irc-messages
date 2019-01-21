<?php
/**
 * WildPHP - an advanced and easily extensible IRC bot written in PHP
 * Copyright (C) 2017 WildPHP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Authenticate;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;

class AuthenticateTest extends TestCase
{

    public function testFromIncomingMessage()
    {
        $prefix = '';
        $verb = 'AUTHENTICATE';
        $args = ['+'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $authenticate = Authenticate::fromIncomingMessage($incoming);

        $this->assertEquals('+', $authenticate->getResponse());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Authenticate::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetSetResponse()
    {
        $authenticate = new Authenticate('+');

        $response = '-';
        $authenticate->setResponse($response);
        $this->assertEquals($response, $authenticate->getResponse());
    }

    public function test__toString()
    {
        $authenticate = new Authenticate('+');

        $this->assertEquals('+', $authenticate->getResponse());

        $expected = 'AUTHENTICATE +' . "\r\n";
        $this->assertEquals($expected, $authenticate->__toString());
    }
}
