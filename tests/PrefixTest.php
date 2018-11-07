<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 07/11/2018
 * Time: 16:08
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IncomingMessage;
use WildPHP\Messages\Generics\Prefix;
use PHPUnit\Framework\TestCase;

class PrefixTest extends TestCase
{

    public function testSetNickname()
    {
        $prefix = new Prefix();
        $prefix->setNickname('test');
        $this->assertEquals('test', $prefix->getNickname());
    }

    public function testFromString()
    {
        $prefixString = 'nickname!username@hostname';

        $prefix = Prefix::fromString($prefixString);

        $this->assertEquals('nickname', $prefix->getNickname());
        $this->assertEquals('username', $prefix->getUsername());
        $this->assertEquals('hostname', $prefix->getHostname());

        $this->expectException(\InvalidArgumentException::class);
        Prefix::fromString('nickname!');
    }

    public function testFromIncomingMessage()
    {
        $incoming = new IncomingMessage('nickname!username@hostname', 'test');

        $prefix = Prefix::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $prefix->getNickname());
        $this->assertEquals('username', $prefix->getUsername());
        $this->assertEquals('hostname', $prefix->getHostname());
    }

    public function testSetHostname()
    {
        $prefix = new Prefix();
        $prefix->setHostname('test');
        $this->assertEquals('test', $prefix->getHostname());
    }

    public function testSetUsername()
    {
        $prefix = new Prefix();
        $prefix->setUsername('test');
        $this->assertEquals('test', $prefix->getUsername());
    }
}
