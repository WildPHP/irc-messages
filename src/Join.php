<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;


use WildPHP\Core\Connection\IncomingIrcMessage;
use WildPHP\Core\Connection\UserPrefix;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class Join
 * @package WildPHP\Messages
 *
 * Syntax (extended-join): prefix JOIN #channel accountName :realname
 * Syntax (regular): prefix JOIN #channel
 * Syntax (sender): JOIN #channels [keys]
 */
class Join extends BaseIRCMessage implements ReceivableMessage, SendableMessage
{
    use Traits\ChannelsTrait;
    use NicknameTrait;
    use PrefixTrait;

    /**
     * @var string
     */
    protected static $verb = 'Join';

    /**
     * @var string
     */
    protected $ircAccount = '';

    /**
     * @var string
     */
    protected $realname = '';

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * Join constructor.
     *
     * @param string[]|string $channels
     * @param string[]|string $keys
     */
    public function __construct($channels, $keys = [])
    {
        if (!is_array($channels)) {
            $channels = [$channels];
        }

        if (!is_array($keys)) {
            $keys = [$keys];
        }

        if (!empty($keys) && count($channels) != count($keys)) {
            throw new \InvalidArgumentException('Channel and key count mismatch');
        }

        $this->setChannels($channels);
        $this->setKeys($keys);
    }

    /**
     * @param IncomingIrcMessage $incomingIrcMessage
     *
     * @return \self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingIrcMessage(IncomingIrcMessage $incomingIrcMessage): self
    {
        if ($incomingIrcMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingIrcMessage->getVerb());
        }

        $prefix = UserPrefix::fromIncomingIrcMessage($incomingIrcMessage);
        $args = $incomingIrcMessage->getArgs();
        $channel = $args[0];
        $ircAccount = $args[1] ?? '';
        $realname = $args[2] ?? '';

        $object = new self($channel);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());
        $object->setIrcAccount($ircAccount);
        $object->setRealname($realname);

        return $object;
    }

    /**
     * @return string
     */
    public function getIrcAccount(): string
    {
        return $this->ircAccount;
    }

    /**
     * @param string $ircAccount
     */
    public function setIrcAccount(string $ircAccount)
    {
        $this->ircAccount = $ircAccount;
    }

    /**
     * @return string
     */
    public function getRealname(): string
    {
        return $this->realname;
    }

    /**
     * @param string $realname
     */
    public function setRealname(string $realname)
    {
        $this->realname = $realname;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @param array $keys
     */
    public function setKeys(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $channels = implode(',', $this->getChannels());
        $keys = implode(',', $this->getKeys());

        return 'Join ' . $channels . (!empty($keys) ? ' ' . $keys : '') . "\r\n";
    }
}