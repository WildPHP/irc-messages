<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Notice;
use PHPUnit\Framework\TestCase;

class NoticeTest extends TestCase
{
    public function test__toString(): void
    {
        $notice = new Notice('#somechannel', 'This is a test message');

        $this->assertEquals('#somechannel', $notice->getChannel());
        $this->assertEquals('This is a test message', $notice->getMessage());

        $expected = 'NOTICE #somechannel :This is a test message' . "\r\n";
        $this->assertEquals($expected, $notice->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'NOTICE';
        $args = ['#somechannel', 'This is a test message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $notice = Notice::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $notice->getPrefix());
        $this->assertEquals('#somechannel', $notice->getChannel());
        $this->assertEquals('This is a test message', $notice->getMessage());
    }
    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Notice::fromIncomingMessage($incomingIrcMessage);
    }
}
