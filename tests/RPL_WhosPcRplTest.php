<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\RPL\WhosPcRpl;
use PHPUnit\Framework\TestCase;

class RPL_WhosPcRplTest extends TestCase
{
    public function testFromIncomingMessage(): void
    {
        $prefix = 'server';
        $verb = '354';
        $args = ['ownnickname', 'username', 'hostname', 'nickname', 'status', 'accountname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $rpl_whospcrpl = WhosPcRpl::fromIncomingMessage($incoming);

        $this->assertEquals('server', $rpl_whospcrpl->getServer());
        $this->assertEquals('ownnickname', $rpl_whospcrpl->getOwnNickname());
        $this->assertEquals('username', $rpl_whospcrpl->getUsername());
        $this->assertEquals('hostname', $rpl_whospcrpl->getHostname());
        $this->assertEquals('nickname', $rpl_whospcrpl->getNickname());
        $this->assertEquals('status', $rpl_whospcrpl->getStatus());
        $this->assertEquals('accountname', $rpl_whospcrpl->getAccountname());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        WhosPcRpl::fromIncomingMessage($incomingIrcMessage);
    }
}
