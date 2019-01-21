<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 21/01/2019
 * Time: 15:38
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Join;
use PHPUnit\Framework\TestCase;

class JoinTest extends TestCase
{

    public function testGetRealname()
    {

    }

    public function testJoinCreateKeyMismatch()
    {
        $this->expectException(InvalidArgumentException::class);

        new Join(['#channel1', '#channel2'], ['key1']);
    }

    public function testCreateArray()
    {
        $join = new Join(['#channel1', '#channel2'], ['key1', 'key2']);

        $this->assertEquals(['#channel1', '#channel2'], $join->getChannels());
        $this->assertEquals(['key1', 'key2'], $join->getKeys());
    }

    public function testCreateString()
    {
        $join = new Join('#channel1', 'key1');

        $this->assertEquals(['#channel1'], $join->getChannels());
        $this->assertEquals(['key1'], $join->getKeys());
    }

    public function test__toString()
    {
        $join = new Join(['#channel1', '#channel2'], ['key1', 'key2']);

        $expected = 'JOIN #channel1,#channel2 key1,key2' . "\r\n";
        $this->assertEquals($expected, $join->__toString());
    }

    public function testGetIrcAccount()
    {

    }

    public function testFromIncomingMessageExtended()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'JOIN';
        $args = ['#channel', 'ircAccountName', 'realname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $join = Join::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $join->getNickname());
        $this->assertEquals(['#channel'], $join->getChannels());
        $this->assertEquals('ircAccountName', $join->getIrcAccount());
        $this->assertEquals('realname', $join->getRealname());
    }

    public function testFromIncomingMessageRegular()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'JOIN';
        $args = ['#channel'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $join = Join::fromIncomingMessage($incoming);

        $this->assertEquals('nickname', $join->getNickname());
        $this->assertEquals(['#channel'], $join->getChannels());
        $this->assertEquals('', $join->getIrcAccount());
        $this->assertEquals('', $join->getRealname());
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Join::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetKeys()
    {

    }
}
