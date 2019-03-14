<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:49
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\RPL\ISupport;
use PHPUnit\Framework\TestCase;

class RPL_ISupportTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '005';
        $args = ['nickname', 'KEY1=value', 'KEY2=value2', 'are supported by this server'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_isupport = ISupport::fromIncomingMessage($incoming);

        $this->assertEquals(['key1' => 'value', 'key2' => 'value2'], $rpl_isupport->getVariables());
        $this->assertEquals('server', $rpl_isupport->getServer());
        $this->assertEquals('nickname', $rpl_isupport->getNickname());
        $this->assertEquals('are supported by this server', $rpl_isupport->getMessage());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        ISupport::fromIncomingMessage($incomingIrcMessage);
    }
}
