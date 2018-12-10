<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Generics;

use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\PrefixInterface;

class Prefix implements PrefixInterface
{
    /**
     * @var string
     */
    public static $regex = '/^(?<server>[^!@]+)?$|^(?<nick>[^!@]+) (?:!(?<user>[^@]+))? (?:@(?<host>.+))?$/x';

    /**
     * @var string
     */
    protected $nickname = '';

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $hostname = '';

    /**
     * UserPrefix constructor.
     *
     * @param string $nickname
     * @param string $username
     * @param string $hostname
     */
    public function __construct(string $nickname = '', string $username = '', string $hostname = '')
    {
        $this->setNickname($nickname);
        $this->setUsername($username);
        $this->setHostname($hostname);
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     */
    public function setHostname(string $hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @param string $prefix
     *
     * @return PrefixInterface
     */
    public static function fromString(string $prefix): PrefixInterface
    {
        if (preg_match(self::$regex, $prefix, $matches) == false) {
            throw new \InvalidArgumentException('Got invalid prefix');
        }

        $nickname = $matches['nick'] ?? '';
        $username = $matches['user'] ?? '';
        $hostname = $matches['host'] ?? ($matches['server'] ?? '');

        return new self($nickname, $username, $hostname);
    }

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return PrefixInterface
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): PrefixInterface
    {
        if (!empty($incomingMessage->getPrefix())) {
            return self::fromString($incomingMessage->getPrefix());
        }

        return new self();
    }
}