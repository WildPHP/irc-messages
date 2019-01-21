<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 21/01/2019
 * Time: 15:35
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Error;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;

class ErrorTest extends TestCase
{

    public function testFromIncomingMessage()
    {
        $prefix = '';
        $verb = 'ERROR';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $error = Error::fromIncomingMessage($incoming);

        $this->assertEquals('A sample message', $error->getMessage());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Error::fromIncomingMessage($incomingIrcMessage);
    }
}
