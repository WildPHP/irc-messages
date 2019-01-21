<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 21/01/2019
 * Time: 15:24
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Authenticate;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;

class AuthenticateTest extends TestCase
{

    public function testFromIncomingMessage()
    {
        $prefix = '';
        $verb = 'AUTHENTICATE';
        $args = ['+'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $authenticate = Authenticate::fromIncomingMessage($incoming);

        $this->assertEquals('+', $authenticate->getResponse());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Authenticate::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetSetResponse()
    {
        $authenticate = new Authenticate('+');

        $response = '-';
        $authenticate->setResponse($response);
        $this->assertEquals($response, $authenticate->getResponse());
    }

    public function test__toString()
    {
        $authenticate = new Authenticate('+');

        $this->assertEquals('+', $authenticate->getResponse());

        $expected = 'AUTHENTICATE +' . "\r\n";
        $this->assertEquals($expected, $authenticate->__toString());
    }
}
