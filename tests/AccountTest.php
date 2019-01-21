<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 21/01/2019
 * Time: 15:21
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Account;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;

class AccountTest extends TestCase
{

    public function testFromIncomingMessage()
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

    public function testFromIncomingIrcMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Account::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetSetAccountName()
    {
        $account = new Account('ircAccount');

        $accountName = 'test';
        $account->setAccountName($accountName);
        $this->assertEquals($accountName, $account->getAccountName());
    }

    public function test__construct()
    {
        $account = new Account('ircAccount');
        $this->assertEquals('ircAccount', $account->getAccountName());
    }
}
