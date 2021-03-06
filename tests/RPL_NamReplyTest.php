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
use WildPHP\Messages\RPL\NamReply;

class RPL_NamReplyTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', 'nickname1 nickname2 nickname3'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_namreply = NamReply::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_namreply->getServer());
        $this->assertEquals('nickname', $rpl_namreply->getNickname());
        $this->assertEquals('+', $rpl_namreply->getVisibility());
        $this->assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());
        $this->assertEquals([], $rpl_namreply->getPrefixes());
    }

    public function testFromIncomingMessageWithUserhostInNames()
    {
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', 'nickname1!user1@host1 nickname2!user2@host2 nickname3!user3@host3'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_namreply = NamReply::fromIncomingMessage($incoming);

        $expectedPrefixes = [
            'nickname1' => new Prefix('nickname1', 'user1', 'host1'),
            'nickname2' => new Prefix('nickname2', 'user2', 'host2'),
            'nickname3' => new Prefix('nickname3', 'user3', 'host3')
        ];

        $expectedModes = [
            'nickname1' => [],
            'nickname2' => [],
            'nickname3' => []
        ];

        $this->assertEquals('server', $rpl_namreply->getServer());
        $this->assertEquals('nickname', $rpl_namreply->getNickname());
        $this->assertEquals('+', $rpl_namreply->getVisibility());
        $this->assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());
        $this->assertEquals($expectedPrefixes, $rpl_namreply->getPrefixes());
        $this->assertEquals($expectedModes, $rpl_namreply->getModes());
    }

    public function testFromIncomingMessageWithModes()
    {
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', '@nickname1 +nickname2 @nickname3 nickname4'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_namreply = NamReply::fromIncomingMessage($incoming);

        $expectedModes = [
            'nickname1' => ['o'],
            'nickname2' => ['v'],
            'nickname3' => ['o'],
            'nickname4' => []
        ];

        $this->assertEquals('server', $rpl_namreply->getServer());
        $this->assertEquals('nickname', $rpl_namreply->getNickname());
        $this->assertEquals('+', $rpl_namreply->getVisibility());
        $this->assertEquals(['nickname1', 'nickname2', 'nickname3', 'nickname4'], $rpl_namreply->getNicknames());
        $this->assertEquals([], $rpl_namreply->getPrefixes());
        $this->assertEquals($expectedModes, $rpl_namreply->getModes());
    }

    public function testFromIncomingMessageWithUserhostInNamesAndModes()
    {
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', '@nickname1!user1@host1 nickname2!user2@host2 +nickname3!user3@host3'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_namreply = NamReply::fromIncomingMessage($incoming);

        $expectedPrefixes = [
            'nickname1' => new Prefix('nickname1', 'user1', 'host1'),
            'nickname2' => new Prefix('nickname2', 'user2', 'host2'),
            'nickname3' => new Prefix('nickname3', 'user3', 'host3')
        ];

        $expectedModes = [
            'nickname1' => ['o'],
            'nickname2' => [],
            'nickname3' => ['v']
        ];

        $this->assertEquals('server', $rpl_namreply->getServer());
        $this->assertEquals('nickname', $rpl_namreply->getNickname());
        $this->assertEquals('+', $rpl_namreply->getVisibility());
        $this->assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());
        $this->assertEquals($expectedPrefixes, $rpl_namreply->getPrefixes());
        $this->assertEquals($expectedModes, $rpl_namreply->getModes());
    }

    public function testFromIncomingMessageWithUserhostInNamesMixed()
    {
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', 'nickname1!user1@host1 nickname2 nickname3!user3@host3'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_namreply = NamReply::fromIncomingMessage($incoming);

        $expectedPrefixes = [
            'nickname1' => new Prefix('nickname1', 'user1', 'host1'),
            'nickname3' => new Prefix('nickname3', 'user3', 'host3')
        ];

        $expectedModes = [
            'nickname1' => [],
            'nickname2' => [],
            'nickname3' => []
        ];

        $this->assertEquals('server', $rpl_namreply->getServer());
        $this->assertEquals('nickname', $rpl_namreply->getNickname());
        $this->assertEquals('+', $rpl_namreply->getVisibility());
        $this->assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());
        $this->assertEquals($expectedPrefixes, $rpl_namreply->getPrefixes());
        $this->assertEquals($expectedModes, $rpl_namreply->getModes());
    }

    public function testFromIncomingMessageWithUserhostInNamesMixedAndModes()
    {
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', '@nickname1!user1@host1 +nickname2 nickname3!user3@host3'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_namreply = NamReply::fromIncomingMessage($incoming);

        $expectedPrefixes = [
            'nickname1' => new Prefix('nickname1', 'user1', 'host1'),
            'nickname3' => new Prefix('nickname3', 'user3', 'host3')
        ];

        $expectedModes = [
            'nickname1' => ['o'],
            'nickname2' => ['v'],
            'nickname3' => []
        ];

        $this->assertEquals('server', $rpl_namreply->getServer());
        $this->assertEquals('nickname', $rpl_namreply->getNickname());
        $this->assertEquals('+', $rpl_namreply->getVisibility());
        $this->assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());
        $this->assertEquals($expectedPrefixes, $rpl_namreply->getPrefixes());
        $this->assertEquals($expectedModes, $rpl_namreply->getModes());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        NamReply::fromIncomingMessage($incomingIrcMessage);
    }
}
