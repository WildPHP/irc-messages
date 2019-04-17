<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\RPL;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class RPL_WHOSPCRPL
 * @package WildPHP\Messages
 *
 * Syntax (as used by WildPHP): :server 354 ownnickname username hostname nickname status accountname
 */
class WhosPcRpl extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ChannelTrait;
    use MessageTrait;
    use ServerTrait;

    protected static $verb = '354';

    /**
     * @var string
     */
    protected $ownNickname = '';

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $hostname = '';

    /**
     * @var string
     */
    protected $status = '';

    /**
     * @var string
     */
    protected $accountname = '';

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() !== self::getVerb()) {
            throw new InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $server = $incomingMessage->getPrefix();
        [$ownNickname, $username, $hostname, $nickname, $status, $accountname] = $incomingMessage->getArgs();

        $object = new self();
        $object->setOwnNickname($ownNickname);
        $object->setUsername($username);
        $object->setHostname($hostname);
        $object->setNickname($nickname);
        $object->setStatus($status);
        $object->setAccountname($accountname);
        $object->setServer($server);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function getOwnNickname(): string
    {
        return $this->ownNickname;
    }

    /**
     * @param string $ownNickname
     */
    public function setOwnNickname(string $ownNickname)
    {
        $this->ownNickname = $ownNickname;
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getAccountname(): string
    {
        return $this->accountname;
    }

    /**
     * @param string $accountname
     */
    public function setAccountname(string $accountname)
    {
        $this->accountname = $accountname;
    }
}