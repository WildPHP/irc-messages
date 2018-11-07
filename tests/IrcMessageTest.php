<?php
/**
 * WildPHP - an advanced and easily extensible IRC bot written in PHP
 * Copyright (C) 2017 WildPHP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\IncomingIrcMessage;
use WildPHP\Messages\Account;
use WildPHP\Messages\Authenticate;
use WildPHP\Messages\Away;
use WildPHP\Messages\Cap;
use WildPHP\Messages\Error;
use WildPHP\Messages\Join;
use WildPHP\Messages\Kick;
use WildPHP\Messages\Mode;
use WildPHP\Messages\Names;
use WildPHP\Messages\Nick;
use WildPHP\Messages\Notice;
use WildPHP\Messages\Part;
use WildPHP\Messages\Pass;
use WildPHP\Messages\Ping;
use WildPHP\Messages\Pong;
use WildPHP\Messages\Privmsg;
use WildPHP\Messages\Quit;
use WildPHP\Messages\Raw;
use WildPHP\Messages\RPL\EndOfNames;
use WildPHP\Messages\RPL\ISupport;
use WildPHP\Messages\RPL\NamReply;
use WildPHP\Messages\RPL\Topic as RplTopic;
use WildPHP\Messages\RPL\Welcome;
use WildPHP\Messages\RPL\WhosPcRpl;
use WildPHP\Messages\Topic;
use WildPHP\Messages\User;
use WildPHP\Messages\Version;
use WildPHP\Messages\Who;
use WildPHP\Messages\WhoIs;
use WildPHP\Messages\WhoWas;
use WildPHP\Core\Connection\Parser;
use WildPHP\Core\Connection\UserPrefix;

class IrcMessageTest extends TestCase
{
	public function testAccountCreate()
	{
		$account = new Account('ircAccount');

		static::assertEquals('ircAccount', $account->getAccountName());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Account::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testAccountReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname ACCOUNT ircAccount' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$account = Account::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $account->getPrefix());
		static::assertEquals('ircAccount', $account->getAccountName());
	}

	public function testAuthenticateCreate()
	{
		$authenticate = new Authenticate('+');

		static::assertEquals('+', $authenticate->getResponse());

		$expected = 'Authenticate +' . "\r\n";
		static::assertEquals($expected, $authenticate->__toString());
	}

	public function testAuthenticateReceive()
	{
		$line = Parser::parseLine('Authenticate +' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$authenticate = Authenticate::fromIncomingIrcMessage($incoming);

		static::assertEquals('+', $authenticate->getResponse());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Authenticate::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testAwayCreate()
	{
		$away = new Away('A sample message');

		static::assertEquals('A sample message', $away->getMessage());

		$expected = 'Away :A sample message' . "\r\n";
		static::assertEquals($expected, $away->__toString());
	}

	public function testAwayReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname Away :A sample message' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$away = Away::fromIncomingIrcMessage($incoming);

		static::assertEquals('nickname', $away->getNickname());
		static::assertEquals('A sample message', $away->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Away::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testCapCreate()
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        static::assertEquals('REQ', $cap->getCommand());
        static::assertEquals(['cap1', 'cap2'], $cap->getCapabilities());

        $expected = 'Cap REQ :cap1 cap2' . "\r\n";
        static::assertEquals($expected, $cap->__toString());
    }

	public function testCapCreateInvalidSubcommand()
	{
		$this->expectException(\InvalidArgumentException::class);
		
		new Cap('INVALID');
    }

    public function testCapReceive()
    {
        $line = Parser::parseLine(':server Cap * LS :cap1 cap2' . "\r\n");
        $incoming = new IncomingIrcMessage($line);
        $cap = Cap::fromIncomingIrcMessage($incoming);

        static::assertEquals('LS', $cap->getCommand());
        static::assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        static::assertEquals('*', $cap->getNickname());

	    $message = ':server TEEHEE argument' . "\r\n";
	    $parsedLine = Parser::parseLine($message);
	    $incomingIrcMessage = new IncomingIrcMessage($parsedLine);
	    $this->expectException(\InvalidArgumentException::class);
	    Cap::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testErrorReceive()
	{
		$line = Parser::parseLine('Error :A sample message' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$error = Error::fromIncomingIrcMessage($incoming);

		static::assertEquals('A sample message', $error->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Error::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testJoinCreate()
	{
		$join = new Join(['#channel1', '#channel2'], ['key1', 'key2']);

		static::assertEquals(['#channel1', '#channel2'], $join->getChannels());
		static::assertEquals(['key1', 'key2'], $join->getKeys());

		$expected = 'Join #channel1,#channel2 key1,key2' . "\r\n";
		static::assertEquals($expected, $join->__toString());

		$join = new Join('#channel1', 'key1');

		static::assertEquals(['#channel1'], $join->getChannels());
		static::assertEquals(['key1'], $join->getKeys());
	}

	public function testJoinCreateKeyMismatch()
	{
		$this->expectException(InvalidArgumentException::class);

		new Join(['#channel1', '#channel2'], ['key1']);
	}

	public function testJoinReceiveExtended()
	{
		$line = Parser::parseLine(':nickname!username@hostname Join #channel ircAccountName :realname' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$join = Join::fromIncomingIrcMessage($incoming);

		static::assertEquals('nickname', $join->getNickname());
		static::assertEquals(['#channel'], $join->getChannels());
		static::assertEquals('ircAccountName', $join->getIrcAccount());
		static::assertEquals('realname', $join->getRealname());
	}

	public function testJoinReceiveRegular()
	{
		$line = Parser::parseLine(':nickname!username@hostname Join #channel' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$join = Join::fromIncomingIrcMessage($incoming);

		static::assertEquals('nickname', $join->getNickname());
		static::assertEquals(['#channel'], $join->getChannels());
		static::assertEquals('', $join->getIrcAccount());
		static::assertEquals('', $join->getRealname());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Join::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testKickCreate()
	{
		$kick = new Kick('#channel', 'nickname', 'Bleep you!');

		static::assertEquals('#channel', $kick->getChannel());
		static::assertEquals('nickname', $kick->getTarget());
		static::assertEquals('Bleep you!', $kick->getMessage());

		$expected = 'KICK #channel nickname :Bleep you!' . "\r\n";
		static::assertEquals($expected, $kick->__toString());
	}

	public function testKickReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname KICK #somechannel othernickname :You deserved it!');
		$incoming = new IncomingIrcMessage($line);
		$kick = Kick::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $kick->getPrefix());
		static::assertEquals('nickname', $kick->getNickname());
		static::assertEquals('othernickname', $kick->getTarget());
		static::assertEquals('#somechannel', $kick->getChannel());
		static::assertEquals('You deserved it!', $kick->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Kick::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testModeCreate()
	{
		$mode = new Mode('target', '-o+b', ['arg1', 'arg2']);

		static::assertEquals('target', $mode->getTarget());
		static::assertEquals('-o+b', $mode->getFlags());
		static::assertEquals(['arg1', 'arg2'], $mode->getArguments());

		$expected = 'MODE target -o+b arg1 arg2' . "\r\n";
		static::assertEquals($expected, $mode->__toString());
	}

	public function testModeReceiveChannel()
	{
		$line = Parser::parseLine(':nickname!username@hostname MODE #channel -o+b arg1 arg2' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$mode = Mode::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $mode->getPrefix());
		static::assertEquals('#channel', $mode->getTarget());
		static::assertEquals('nickname', $mode->getNickname());
		static::assertEquals('-o+b', $mode->getFlags());
		static::assertEquals(['arg1', 'arg2'], $mode->getArguments());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Mode::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testModeReceiveUser()
	{
		$line = Parser::parseLine(':nickname!username@hostname MODE user -o+b' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$mode = Mode::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $mode->getPrefix());
		static::assertEquals('user', $mode->getTarget());
		static::assertEquals('nickname', $mode->getNickname());
		static::assertEquals('-o+b', $mode->getFlags());
		static::assertEquals([], $mode->getArguments());
	}

	public function testModeReceiveInitial()
	{
		$line = Parser::parseLine(':nickname!username@hostname MODE nickname -o+b' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$mode = Mode::fromIncomingIrcMessage($incoming);

		static::assertEquals('nickname', $mode->getTarget());
		static::assertEquals('nickname', $mode->getNickname());
		static::assertEquals('-o+b', $mode->getFlags());
		static::assertEquals([], $mode->getArguments());
	}

	public function testNamesCreate()
	{
		$names = new Names('#testChannel', 'testServer');

		static::assertEquals(['#testChannel'], $names->getChannels());
		static::assertEquals('testServer', $names->getServer());

		$expected = 'NAMES #testChannel testServer';
		static::assertEquals($expected, $names->__toString());
	}

	public function testNickCreate()
	{
		$nick = new Nick('newnickname');

		static::assertEquals('newnickname', $nick->getNewNickname());

		$expected = 'NICK newnickname' . "\r\n";
		static::assertEquals($expected, $nick->__toString());
	}

	public function testNickReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname NICK newnickname' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$nick = Nick::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $nick->getPrefix());
		static::assertEquals('nickname', $nick->getNickname());
		static::assertEquals('newnickname', $nick->getNewNickname());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Nick::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testNoticeCreate()
	{
		$notice = new Notice('#somechannel', 'This is a test message');

		static::assertEquals('#somechannel', $notice->getChannel());
		static::assertEquals('This is a test message', $notice->getMessage());

		$expected = 'NOTICE #somechannel :This is a test message' . "\r\n";
		static::assertEquals($expected, $notice->__toString());
	}

	public function testNoticeReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname NOTICE #somechannel :This is a test message' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$notice = Notice::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $notice->getPrefix());
		static::assertEquals('#somechannel', $notice->getChannel());
		static::assertEquals('This is a test message', $notice->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Notice::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testPartCreate()
	{
		$part = new Part(['#channel1', '#channel2'], 'I am out');

		static::assertEquals(['#channel1', '#channel2'], $part->getChannels());
		static::assertEquals('I am out', $part->getMessage());

		$expected = 'PART #channel1,#channel2 :I am out' . "\r\n";
		static::assertEquals($expected, $part->__toString());
	}

	public function testPartReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname PART #channel :I have a valid reason' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$part = Part::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $part->getPrefix());
		static::assertEquals('nickname', $part->getNickname());
		static::assertEquals(['#channel'], $part->getChannels());
		static::assertEquals('I have a valid reason', $part->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Part::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testPassCreate()
    {
        $pass = new Pass('myseekritpassw0rd');

        static::assertEquals('myseekritpassw0rd', $pass->getPassword());

        $expected = 'PASS :myseekritpassw0rd' . "\r\n";
        static::assertEquals($expected, $pass->__toString());
    }

	public function testPingCreate()
	{
		$ping = new Ping('testserver1', 'testserver2');

		static::assertEquals('testserver1', $ping->getServer1());
		static::assertEquals('testserver2', $ping->getServer2());

		$expected = 'PING testserver1 testserver2' . "\r\n";
		static::assertEquals($expected, $ping->__toString());
	}

	public function testPingReceive()
	{
		$line = Parser::parseLine('PING testserver1 testserver2' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$ping = Ping::fromIncomingIrcMessage($incoming);

		static::assertEquals('testserver1', $ping->getServer1());
		static::assertEquals('testserver2', $ping->getServer2());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Ping::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testPongCreate()
	{
		$pong = new Pong('testserver1', 'testserver2');

		static::assertEquals('testserver1', $pong->getServer1());
		static::assertEquals('testserver2', $pong->getServer2());

		$expected = 'PONG testserver1 testserver2' . "\r\n";
		static::assertEquals($expected, $pong->__toString());
	}

	public function testPongReceive()
	{
		$line = Parser::parseLine('PONG testserver1 testserver2' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$pong = Pong::fromIncomingIrcMessage($incoming);

		static::assertEquals('testserver1', $pong->getServer1());
		static::assertEquals('testserver2', $pong->getServer2());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Pong::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testPrivmsgCreate()
	{
		$privmsg = new Privmsg('#somechannel', 'This is a test message');

		static::assertEquals('#somechannel', $privmsg->getChannel());
		static::assertEquals('This is a test message', $privmsg->getMessage());

		$expected = 'PRIVMSG #somechannel :This is a test message' . "\r\n";
		static::assertEquals($expected, $privmsg->__toString());
	}

	public function testPrivmsgCreateCTCP()
	{
		$privmsg = new Privmsg('#somechannel', 'This is a test message');
		$privmsg->setCtcpVerb('ACTION');
		$privmsg->setIsCtcp(true);

		static::assertEquals('#somechannel', $privmsg->getChannel());
		static::assertEquals('This is a test message', $privmsg->getMessage());
		static::assertEquals('ACTION', $privmsg->getCtcpVerb());
		static::assertTrue($privmsg->isCtcp());

		$expected = 'PRIVMSG #somechannel :' . "\x01" . 'ACTION This is a test message' . "\x01\r\n";
		static::assertEquals($expected, $privmsg->__toString());
	}

	public function testPrivmsgReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname PRIVMSG #somechannel :This is a test message' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$privmsg = Privmsg::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $privmsg->getPrefix());
		static::assertEquals('#somechannel', $privmsg->getChannel());
		static::assertEquals('This is a test message', $privmsg->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Privmsg::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testPrivmsgReceiveCTCP()
	{
		$line = Parser::parseLine(':nickname!username@hostname PRIVMSG #somechannel :' . "\x01" . 'ACTION This is a test message' . "\x01\r\n");
		$incoming = new IncomingIrcMessage($line);
		$privmsg = Privmsg::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $privmsg->getPrefix());
		static::assertEquals('#somechannel', $privmsg->getChannel());
		static::assertTrue($privmsg->isCtcp());
		static::assertEquals('ACTION', $privmsg->getCtcpVerb());
		static::assertEquals('This is a test message', $privmsg->getMessage());
	}

	public function testQuitCreate()
	{
		$quit = new Quit('A sample message');

		static::assertEquals('A sample message', $quit->getMessage());

		$expected = 'QUIT :A sample message' . "\r\n";
		static::assertEquals($expected, $quit->__toString());
	}

	public function testQuitReceive()
	{
		$line = Parser::parseLine(':nickname!username@hostname QUIT :A sample message' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$quit = Quit::fromIncomingIrcMessage($incoming);

		$userPrefix = new UserPrefix('nickname', 'username', 'hostname');
		static::assertEquals($userPrefix, $quit->getPrefix());
		static::assertEquals('nickname', $quit->getNickname());
		static::assertEquals('A sample message', $quit->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Quit::fromIncomingIrcMessage($incomingIrcMessage);
	}

	public function testRawCreate()
    {
        $raw = new Raw('a command');

        static::assertEquals('a command', $raw->getCommand());

        $expected = 'a command' . "\r\n";
        static::assertEquals($expected, $raw->__toString());
    }

	public function testRemoveCreate()
	{
		$remove = new \WildPHP\Messages\Remove('#channel', 'nickname', 'Get out!');
		
		static::assertEquals('#channel', $remove->getChannel());
		static::assertEquals('nickname', $remove->getTarget());
		static::assertEquals('Get out!', $remove->getMessage());
		
		$expected = 'REMOVE #channel nickname :Get out!' . "\r\n";
		static::assertEquals($expected, $remove->__toString());
    }

	public function testRplEndOfNamesReceive()
	{
		$line = Parser::parseLine(':server 366 nickname #channel :End of /NAMES list.' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$rpl_endofnames = EndOfNames::fromIncomingIrcMessage($incoming);

		static::assertEquals('nickname', $rpl_endofnames->getNickname());
		static::assertEquals('#channel', $rpl_endofnames->getChannel());
		static::assertEquals('End of /NAMES list.', $rpl_endofnames->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		EndOfNames::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testRplIsupportReceive()
	{
		$line = Parser::parseLine(':server 005 nickname KEY1=value KEY2=value2 :are supported by this server' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$rpl_isupport = ISupport::fromIncomingIrcMessage($incoming);

		static::assertEquals(['key1' => 'value', 'key2' => 'value2'], $rpl_isupport->getVariables());
		static::assertEquals('server', $rpl_isupport->getServer());
		static::assertEquals('nickname', $rpl_isupport->getNickname());
		static::assertEquals('are supported by this server', $rpl_isupport->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		ISupport::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testRplNamReplyReceive()
	{
		$line = Parser::parseLine(':server 353 nickname + #channel :nickname1 nickname2 nickname3' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$rpl_namreply = NamReply::fromIncomingIrcMessage($incoming);

		static::assertEquals('server', $rpl_namreply->getServer());
		static::assertEquals('nickname', $rpl_namreply->getNickname());
		static::assertEquals('+', $rpl_namreply->getVisibility());
		static::assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		NamReply::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testRplTopicReceive()
	{
		$line = Parser::parseLine(':server 332 nickname #channel :A new topic message' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$rpl_topic = RplTopic::fromIncomingIrcMessage($incoming);

		static::assertEquals('server', $rpl_topic->getServer());
		static::assertEquals('nickname', $rpl_topic->getNickname());
		static::assertEquals('#channel', $rpl_topic->getChannel());
		static::assertEquals('A new topic message', $rpl_topic->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		RplTopic::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testRplWelcomeReceive()
	{
		$line = Parser::parseLine(':server 001 nickname :Welcome to server!' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$rpl_welcome = Welcome::fromIncomingIrcMessage($incoming);

		static::assertEquals('server', $rpl_welcome->getServer());
		static::assertEquals('nickname', $rpl_welcome->getNickname());
		static::assertEquals('Welcome to server!', $rpl_welcome->getMessage());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		Welcome::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testRplWhosPCRplReceive()
	{
		$line = Parser::parseLine(':server 354 ownnickname username hostname nickname status accountname' . "\r\n");
		$incoming = new IncomingIrcMessage($line);
		$rpl_whospcrpl = WhosPcRpl::fromIncomingIrcMessage($incoming);

		static::assertEquals('server', $rpl_whospcrpl->getServer());
		static::assertEquals('ownnickname', $rpl_whospcrpl->getOwnNickname());
		static::assertEquals('username', $rpl_whospcrpl->getUsername());
		static::assertEquals('hostname', $rpl_whospcrpl->getHostname());
		static::assertEquals('nickname', $rpl_whospcrpl->getNickname());
		static::assertEquals('status', $rpl_whospcrpl->getStatus());
		static::assertEquals('accountname', $rpl_whospcrpl->getAccountname());

		$message = ':server TEEHEE argument' . "\r\n";
		$parsedLine = Parser::parseLine($message);
		$incomingIrcMessage = new IncomingIrcMessage($parsedLine);
		$this->expectException(\InvalidArgumentException::class);
		WhosPcRpl::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testTopicCreate()
    {
        $topic = new TOPIC('#someChannel', 'Test message');

        static::assertEquals('#someChannel', $topic->getChannel());
        static::assertEquals('Test message', $topic->getMessage());

        $expected = 'TOPIC #someChannel :Test message' . "\r\n";
        static::assertEquals($expected, $topic->__toString());
    }

    public function testTopicReceive()
    {
        $line = Parser::parseLine(':nickname!username@hostname TOPIC #someChannel :This is a new topic' . "\r\n");
        $incoming = new IncomingIrcMessage($line);
        $topic = Topic::fromIncomingIrcMessage($incoming);

	    $userPrefix = new UserPrefix('nickname', 'username', 'hostname');
	    static::assertEquals($userPrefix, $topic->getPrefix());
        static::assertEquals('#someChannel', $topic->getChannel());
        static::assertEquals('This is a new topic', $topic->getMessage());

	    $message = ':server TEEHEE argument' . "\r\n";
	    $parsedLine = Parser::parseLine($message);
	    $incomingIrcMessage = new IncomingIrcMessage($parsedLine);
	    $this->expectException(\InvalidArgumentException::class);
	    Topic::fromIncomingIrcMessage($incomingIrcMessage);
    }

    public function testUserCreate()
    {
        $user = new User('myusername', 'localhost', 'someserver', 'arealname');

        static::assertEquals('myusername', $user->getUsername());
        static::assertEquals('localhost', $user->getHostname());
        static::assertEquals('someserver', $user->getServername());
        static::assertEquals('arealname', $user->getRealname());

        $expected = 'USER myusername localhost someserver :arealname' . "\r\n";
        static::assertEquals($expected, $user->__toString());
    }

    public function testUserReceive()
    {
        $line = Parser::parseLine('USER myusername localhost someserver :A real name' . "\r\n");
        $incoming = new IncomingIrcMessage($line);
        $user = User::fromIncomingIrcMessage($incoming);

        static::assertEquals('myusername', $user->getUsername());
        static::assertEquals('localhost', $user->getHostname());
        static::assertEquals('someserver', $user->getServername());
        static::assertEquals('A real name', $user->getRealname());

	    $message = ':server TEEHEE argument' . "\r\n";
	    $parsedLine = Parser::parseLine($message);
	    $incomingIrcMessage = new IncomingIrcMessage($parsedLine);
	    $this->expectException(\InvalidArgumentException::class);
	    User::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testVersionCreate()
	{
		$version = new Version('server');
		static::assertEquals('server', $version->getServer());

		$expected = 'VERSION server';
		static::assertEquals($expected, $version->__toString());

		$version = new Version();
		$expected = 'VERSION';
		static::assertEquals($expected, $version->__toString());
    }

	public function testWhoCreate()
    {
        $who = new Who('#someChannel', '%nuhaf');

        static::assertEquals('#someChannel', $who->getChannel());
        static::assertEquals('%nuhaf', $who->getOptions());

        $expected = 'WHO #someChannel %nuhaf' . "\r\n";
        static::assertEquals($expected, $who->__toString());
    }

    public function testWhoReceive()
    {
        $line = Parser::parseLine(':nickname!username@hostname WHO #someChannel %nuhaf' . "\r\n");
        $incoming = new IncomingIrcMessage($line);
        $who = Who::fromIncomingIrcMessage($incoming);

	    $userPrefix = new UserPrefix('nickname', 'username', 'hostname');
	    static::assertEquals($userPrefix, $who->getPrefix());
        static::assertEquals('#someChannel', $who->getChannel());
        static::assertEquals('%nuhaf', $who->getOptions());

	    $message = ':server TEEHEE argument' . "\r\n";
	    $parsedLine = Parser::parseLine($message);
	    $incomingIrcMessage = new IncomingIrcMessage($parsedLine);
	    $this->expectException(\InvalidArgumentException::class);
	    Who::fromIncomingIrcMessage($incomingIrcMessage);
    }

	public function testWhoisCreate()
	{
		$whois = new WhoIs(['nickname1', 'nickname2'], 'server');
		static::assertEquals(['nickname1', 'nickname2'], $whois->getNicknames());
		static::assertEquals('server', $whois->getServer());

		$expected = 'WHOIS server nickname1,nickname2';
		static::assertEquals($expected, $whois->__toString());
		
		$whois = new WhoIs('nickname1', 'server');
		static::assertEquals(['nickname1'], $whois->getNicknames());
    }

	public function testWhoWasCreate()
	{
		$whowas = new WhoWas(['nickname1', 'nickname2'], 2, 'server');
		static::assertEquals(['nickname1', 'nickname2'], $whowas->getNicknames());
		static::assertEquals(2, $whowas->getCount());
		static::assertEquals('server', $whowas->getServer());

		$expected = 'WHOWAS nickname1,nickname2 2 server';
		static::assertEquals($expected, $whowas->__toString());

		$whowas = new WhoWas('nickname1', 2, 'server');
		static::assertEquals(['nickname1'], $whowas->getNicknames());
	}

	public function testMessageParameters()
	{
		$raw = new Raw('test');
		
		$raw->setMessageParameters(['test']);
		
		self::assertEquals(['test'], $raw->getMessageParameters());
	}
}
