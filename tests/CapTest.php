<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 21/01/2019
 * Time: 15:29
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Cap;
use WildPHP\Messages\Generics\IrcMessage;

class CapTest extends TestCase
{

    public function testGetCommand()
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);
        $this->assertEquals('REQ', $cap->getCommand());

        $cap->setCommand('ACK');
        $this->assertEquals('ACK', $cap->getCommand());
    }

    public function testSetCommandThrowsException()
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        $this->expectException(\InvalidArgumentException::class);
        $cap->setCommand('TEEHEE');
    }

    public function testFromIncomingMessage()
    {
        $prefix = 'server';
        $verb = 'CAP';
        $args = ['*', 'LS', 'cap1 cap2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $cap = Cap::fromIncomingMessage($incoming);

        $this->assertEquals('LS', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        $this->assertEquals('*', $cap->getClientIdentifier());
        $this->assertTrue($cap->isFinalMessage());

        $prefix = 'server';
        $verb = 'CAP';
        $args = ['*', 'LS', '*', 'cap1 cap2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $cap = Cap::fromIncomingMessage($incoming);

        $this->assertEquals('LS', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        $this->assertEquals('*', $cap->getClientIdentifier());
        $this->assertFalse($cap->isFinalMessage());
    }

    public function testCapCreateInvalidSubcommand()
    {
        new Cap('LS 302');

        $this->expectException(\InvalidArgumentException::class);

        new Cap('INVALID');
    }

    public function testFromIncomingMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Cap::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetCapabilities()
    {
        $capabilities = ['cap1', 'cap2'];
        $cap = new Cap('REQ', $capabilities);
        $this->assertEquals($capabilities, $cap->getCapabilities());

        $cap->setCapabilities(['cap1']);
        $this->assertEquals(['cap1'], $cap->getCapabilities());
    }

    public function testGetClientIdentifier()
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        $cap->setClientIdentifier('test');
        $this->assertSame('test', $cap->getClientIdentifier());
    }

    public function test__toString()
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        $this->assertEquals('REQ', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        $this->assertTrue($cap->isFinalMessage());

        $expected = 'CAP REQ :cap1 cap2' . "\r\n";
        $this->assertEquals($expected, $cap->__toString());
    }

    public function testIsFinalMessage()
    {
        $cap = new Cap('REQ', ['cap1', 'cap2'], false);
        $this->assertFalse($cap->isFinalMessage());

        $cap->setFinalMessage(true);
        $this->assertTrue($cap->isFinalMessage());
    }
}
