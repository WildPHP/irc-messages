<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Topic;
use PHPUnit\Framework\TestCase;

class TopicTest extends TestCase
{
    public function test__toString(): void
    {
        $topic = new TOPIC('#someChannel', 'Test message');

        $this->assertEquals('#someChannel', $topic->getChannel());
        $this->assertEquals('Test message', $topic->getMessage());

        $expected = 'TOPIC #someChannel :Test message' . "\r\n";
        $this->assertEquals($expected, $topic->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'TOPIC';
        $args = ['#someChannel', 'This is a new topic'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $topic = Topic::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $topic->getPrefix());
        $this->assertEquals('#someChannel', $topic->getChannel());
        $this->assertEquals('This is a new topic', $topic->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Topic::fromIncomingMessage($incomingIrcMessage);
    }
}
