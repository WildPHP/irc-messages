<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
declare(strict_types=1);

/** @noinspection PhpUnhandledExceptionInspection */

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Exceptions\CastException;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Privmsg;
use WildPHP\Messages\RPL\Topic;
use WildPHP\Messages\Utility\MessageCaster;

class MessageCasterTest extends TestCase
{
    public function testCastMessage(): void
    {
        $expected = new Privmsg('#channel', 'Message');
        $expected->setPrefix(new Prefix());

        $incoming = new IrcMessage('', 'PRIVMSG', ['#channel', 'Message']);
        $outcome = MessageCaster::castMessage($incoming);

        $this->assertEquals($expected, $outcome);
    }

    public function testCastMessageInvalidClass(): void
    {
        $incoming = new IrcMessage('', 'Generics\\IRCMessage');

        $this->expectException(CastException::class);
        MessageCaster::castMessage($incoming);
    }

    public function testCastMessageClassNotFound(): void
    {
        $incoming = new IrcMessage('', 'FOO');

        $this->expectException(CastException::class);
        MessageCaster::castMessage($incoming);
    }

    public function testCastNumericVerbMessage(): void
    {
        $expected = new Topic();

        $incoming = new IrcMessage('', '332', ['', '', '']);
        $outcome = MessageCaster::castMessage($incoming);

        $this->assertEquals($expected, $outcome);
    }

    public function testCastNumericNonexistingVerbMessage()
    {
        $incoming = new IrcMessage('', '999', ['', '', '']);

        $this->expectException(CastException::class);
        MessageCaster::castMessage($incoming);
    }
}
