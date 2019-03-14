<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:49
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\RPL\NamReply;
use PHPUnit\Framework\TestCase;

class RPL_NamReplyTest extends TestCase
{
    public function testFromIncomingMessage()
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
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        NamReply::fromIncomingMessage($incomingIrcMessage);
    }
}
