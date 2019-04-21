<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
declare(strict_types=1);

namespace WildPHP\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\RPL\ISupport;

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
        $this->expectException(InvalidArgumentException::class);
        ISupport::fromIncomingMessage($incomingIrcMessage);
    }
}
