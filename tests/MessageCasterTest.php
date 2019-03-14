<?php /**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
/** @noinspection PhpUnhandledExceptionInspection */

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Utility\MessageCaster;

class MessageCasterTest extends TestCase
{

    public function testCastMessage(): void
    {
        $expected = new \WildPHP\Messages\Privmsg('#channel', 'Message');
        $expected->setPrefix(new \WildPHP\Messages\Generics\Prefix());

        $incoming = new \WildPHP\Messages\Generics\IrcMessage('', 'PRIVMSG', ['#channel', 'Message']);
        $outcome = MessageCaster::castMessage($incoming);

        $this->assertEquals($expected, $outcome);
    }

    public function testCastMessageInvalidClass(): void
    {
        $incoming = new \WildPHP\Messages\Generics\IrcMessage('', 'Generics\\BaseIRCMessage');

        $this->expectException(\WildPHP\Messages\Exceptions\CastException::class);
        MessageCaster::castMessage($incoming);
    }

    public function testCastMessageClassNotFound(): void
    {
        $incoming = new \WildPHP\Messages\Generics\IrcMessage('', 'FOO');

        $this->expectException(\WildPHP\Messages\Exceptions\CastException::class);
        MessageCaster::castMessage($incoming);
    }

    public function testCastNumericVerbMessage(): void
    {
        $expected = new \WildPHP\Messages\RPL\Topic();

        $incoming = new \WildPHP\Messages\Generics\IrcMessage('', '332', ['', '', '']);
        $outcome = MessageCaster::castMessage($incoming);

        $this->assertEquals($expected, $outcome);
    }
}
