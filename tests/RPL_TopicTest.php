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
use WildPHP\Messages\RPL\Topic;

class RPL_TopicTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '332';
        $args = ['nickname', '#channel', 'A new topic message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_topic = Topic::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_topic->getServer());
        $this->assertEquals('nickname', $rpl_topic->getNickname());
        $this->assertEquals('#channel', $rpl_topic->getChannel());
        $this->assertEquals('A new topic message', $rpl_topic->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Topic::fromIncomingMessage($incomingIrcMessage);
    }
}
