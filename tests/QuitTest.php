<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:46
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Quit;
use PHPUnit\Framework\TestCase;

class QuitTest extends TestCase
{
    public function test__toString(): void
    {
        $quit = new Quit('A sample message');

        $this->assertEquals('A sample message', $quit->getMessage());

        $expected = 'QUIT :A sample message' . "\r\n";
        $this->assertEquals($expected, $quit->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'QUIT';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $quit = Quit::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $quit->getPrefix());
        $this->assertEquals('nickname', $quit->getNickname());
        $this->assertEquals('A sample message', $quit->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Quit::fromIncomingMessage($incomingIrcMessage);
    }
}
