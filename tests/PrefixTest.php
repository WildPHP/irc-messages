<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
declare(strict_types=1);

namespace WildPHP\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;

class PrefixTest extends TestCase
{

    public function testSetNickname(): void
    {
        $prefix = new Prefix();
        $prefix->setNickname('test');
        $this->assertEquals('test', $prefix->getNickname());
    }

    public function testFromString(): void
    {
        $prefixString = 'nickname!username@hostname';

        $prefix = Prefix::fromString($prefixString);

        $this->assertEquals('nickname', $prefix->getNickname());
        $this->assertEquals('username', $prefix->getUsername());
        $this->assertEquals('hostname', $prefix->getHostname());

        $this->expectException(InvalidArgumentException::class);
        Prefix::fromString('nickname!');
    }

    public function testFromIncomingMessage(): void
    {
        $incoming = new IrcMessage('nickname!username@hostname', 'test');

        $prefix = Prefix::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $prefix->getNickname());
        $this->assertEquals('username', $prefix->getUsername());
        $this->assertEquals('hostname', $prefix->getHostname());
    }

    public function testSetHostname(): void
    {
        $prefix = new Prefix();
        $prefix->setHostname('test');
        $this->assertEquals('test', $prefix->getHostname());
    }

    public function testSetUsername(): void
    {
        $prefix = new Prefix();
        $prefix->setUsername('test');
        $this->assertEquals('test', $prefix->getUsername());
    }
}
