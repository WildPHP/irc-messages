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

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Kick;
use PHPUnit\Framework\TestCase;

class KickTest extends TestCase
{

    public function test__toString(): void
    {
        $kick = new Kick('#channel', 'nickname', 'Bleep you!');

        $this->assertEquals('#channel', $kick->getChannel());
        $this->assertEquals('nickname', $kick->getTarget());
        $this->assertEquals('Bleep you!', $kick->getMessage());

        $expected = 'KICK #channel nickname :Bleep you!' . "\r\n";
        $this->assertEquals($expected, $kick->__toString());
    }

    public function testGetTarget(): void
    {
        $kick = new Kick('#channel', 'nickname', 'Bleep you!');
        $this->assertEquals('nickname', $kick->getTarget());

        $kick->setTarget('othername');
        $this->assertEquals('othername', $kick->getTarget());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'KICK';
        $args = ['#somechannel', 'othernickname', 'You deserved it!'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $kick = Kick::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $kick->getPrefix());
        $this->assertEquals('nickname', $kick->getNickname());
        $this->assertEquals('othernickname', $kick->getTarget());
        $this->assertEquals('#somechannel', $kick->getChannel());
        $this->assertEquals('You deserved it!', $kick->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Kick::fromIncomingMessage($incomingIrcMessage);
    }
}
