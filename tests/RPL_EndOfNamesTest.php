<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:48
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\RPL\EndOfNames;
use PHPUnit\Framework\TestCase;

class RPL_EndOfNamesTest extends TestCase
{
    public function testFromIncomingMessage()
    {
        $prefix = 'server';
        $verb = '366';
        $args = ['nickname', '#channel', 'End of /NAMES list.'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_endofnames = EndOfNames::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $rpl_endofnames->getNickname());
        $this->assertEquals('#channel', $rpl_endofnames->getChannel());
        $this->assertEquals('End of /NAMES list.', $rpl_endofnames->getMessage());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        EndOfNames::fromIncomingMessage($incomingIrcMessage);
    }
}
