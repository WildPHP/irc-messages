<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
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
class Join extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    use Traits\ChannelsTrait;
    use NicknameTrait;
    use PrefixTrait;

    /**
     * @var string
     */
    protected static $verb = 'JOIN';

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

        if (!empty($keys) && count($channels) !== count($keys)) {
            throw new InvalidArgumentException('Channel and key count mismatch');
        }

        $this->setChannels($channels);
        $this->setKeys($keys);
    }

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() !== self::getVerb()) {
            throw new InvalidArgumentException(sprintf(
                'Expected incoming %s; got %s',
                self::getVerb(),
                $incomingMessage->getVerb()
            ));
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        $args = $incomingMessage->getArgs();
        $channel = $args[0];
        $ircAccount = $args[1] ?? '';
        $realname = $args[2] ?? '';

        $object = new self($channel);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());
        $object->setIrcAccount($ircAccount);
        $object->setRealname($realname);
        $object->setTags($incomingMessage->getTags());

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

        return 'JOIN ' . $channels . (!empty($keys) ? ' ' . $keys : '') . "\r\n";
    }
}
