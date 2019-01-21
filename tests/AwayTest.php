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

use WildPHP\Messages\Away;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;

class AwayTest extends TestCase
{

    public function test__toString()
    {
        $away = new Away('A sample message');

        $this->assertEquals('A sample message', $away->getMessage());

        $expected = 'AWAY :A sample message' . "\r\n";
        $this->assertEquals($expected, $away->__toString());
    }

    public function testFromIncomingMessage()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'AWAY';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $away = Away::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $away->getNickname());
        $this->assertEquals('A sample message', $away->getMessage());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Away::fromIncomingMessage($incomingIrcMessage);
    }
}
