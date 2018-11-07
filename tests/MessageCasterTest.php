<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 07/11/2018
 * Time: 15:47
 */

use WildPHP\Messages\Utility\MessageCaster;
use PHPUnit\Framework\TestCase;

class MessageCasterTest extends TestCase
{

    public function testCastMessage()
    {
        $expected = new \WildPHP\Messages\Privmsg('#channel', 'Message');
        $expected->setPrefix(new \WildPHP\Messages\Generics\Prefix());

        $incoming = new \WildPHP\Messages\Generics\IncomingMessage('', 'PRIVMSG', ['#channel', 'Message']);
        $outcome = MessageCaster::castMessage($incoming);

        $this->assertEquals($expected, $outcome);
    }

    public function testCastMessageInvalidClass()
    {
        $incoming = new \WildPHP\Messages\Generics\IncomingMessage('', 'Generics\\BaseIRCMessage');

        $this->expectException(\WildPHP\Messages\Exceptions\CastException::class);
        MessageCaster::castMessage($incoming);
    }

    public function testCastMessageClassNotFound()
    {
        $incoming = new \WildPHP\Messages\Generics\IncomingMessage('', 'FOO');

        $this->expectException(\WildPHP\Messages\Exceptions\CastException::class);
        MessageCaster::castMessage($incoming);
    }

    public function testCastNumericVerbMessage()
    {
        $expected = new \WildPHP\Messages\RPL\Topic();

        $incoming = new \WildPHP\Messages\Generics\IncomingMessage('', '332', ['', '', '']);
        $outcome = MessageCaster::castMessage($incoming);

        $this->assertEquals($expected, $outcome);
    }
}
