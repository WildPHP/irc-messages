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

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Cap;
use WildPHP\Messages\Generics\IrcMessage;

class CapTest extends TestCase
{

    public function testGetCommand(): void
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);
        $this->assertEquals('REQ', $cap->getCommand());

        $cap->setCommand('ACK');
        $this->assertEquals('ACK', $cap->getCommand());
    }

    public function testSetCommandThrowsException(): void
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        $this->expectException(\InvalidArgumentException::class);
        $cap->setCommand('TEEHEE');
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = 'CAP';
        $args = ['*', 'LS', 'cap1 cap2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $cap = Cap::fromIncomingMessage($incoming);

        $this->assertEquals('LS', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        $this->assertEquals('*', $cap->getClientIdentifier());
        $this->assertTrue($cap->isFinalMessage());

        $prefix = 'server';
        $verb = 'CAP';
        $args = ['*', 'LS', '*', 'cap1 cap2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $cap = Cap::fromIncomingMessage($incoming);

        $this->assertEquals('LS', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        $this->assertEquals('*', $cap->getClientIdentifier());
        $this->assertFalse($cap->isFinalMessage());
    }

    public function testCapCreateInvalidSubcommand(): void
    {
        new Cap('LS 302');

        $this->expectException(\InvalidArgumentException::class);

        new Cap('INVALID');
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Cap::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetCapabilities(): void
    {
        $capabilities = ['cap1', 'cap2'];
        $cap = new Cap('REQ', $capabilities);
        $this->assertEquals($capabilities, $cap->getCapabilities());

        $cap->setCapabilities(['cap1']);
        $this->assertEquals(['cap1'], $cap->getCapabilities());
    }

    public function testGetClientIdentifier(): void
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        $cap->setClientIdentifier('test');
        $this->assertSame('test', $cap->getClientIdentifier());
    }

    public function test__toString(): void
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        $this->assertEquals('REQ', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        $this->assertTrue($cap->isFinalMessage());

        $expected = 'CAP REQ :cap1 cap2' . "\r\n";
        $this->assertEquals($expected, $cap->__toString());
    }

    public function testIsFinalMessage(): void
    {
        $cap = new Cap('REQ', ['cap1', 'cap2'], false);
        $this->assertFalse($cap->isFinalMessage());

        $cap->setFinalMessage(true);
        $this->assertTrue($cap->isFinalMessage());
    }
}
