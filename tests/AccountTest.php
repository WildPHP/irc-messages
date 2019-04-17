<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Account;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;

class AccountTest extends TestCase
{

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'ACCOUNT';
        $args = ['ircAccount'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $account = Account::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $account->getPrefix());
        $this->assertEquals('ircAccount', $account->getAccountName());
    }

    public function testFromIncomingIrcMessageThrowsException(): void
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(InvalidArgumentException::class);
        Account::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetSetAccountName(): void
    {
        $account = new Account('ircAccount');

        $accountName = 'test';
        $account->setAccountName($accountName);
        $this->assertEquals($accountName, $account->getAccountName());
    }

    public function test__construct(): void
    {
        $account = new Account('ircAccount');
        $this->assertEquals('ircAccount', $account->getAccountName());
    }
}
