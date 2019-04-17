<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\RPL;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class RPL_NAMREPLY
 * @package WildPHP\Messages
 *
 * Syntax: :server 353 nickname visibility channel :nicknames
 */
class NamReply extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ChannelTrait;
    use ServerTrait;

    protected static $verb = '353';

    protected $visibility = '';

    protected $nicknames = [];

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $server = $incomingMessage->getPrefix();
        $args = $incomingMessage->getArgs();
        $nickname = array_shift($args);
        $visibility = array_shift($args);
        $channel = array_shift($args);
        $nicknames = explode(' ', array_shift($args));

        $object = new self();
        $object->setNickname($nickname);
        $object->setVisibility($visibility);
        $object->setChannel($channel);
        $object->setNicknames($nicknames);
        $object->setServer($server);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility(string $visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return array
     */
    public function getNicknames(): array
    {
        return $this->nicknames;
    }

    /**
     * @param array $nicknames
     */
    public function setNicknames(array $nicknames)
    {
        $this->nicknames = $nicknames;
    }
}