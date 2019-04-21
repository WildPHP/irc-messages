<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\RPL;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
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
 * Syntax (userhost-in-names): :server 353 nickname visibility channel :prefixes
 */
class NamReply extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ChannelTrait;
    use ServerTrait;

    protected static $verb = '353';

    /**
     * @var string
     */
    protected $visibility = '';

    /**
     * @var array
     */
    protected $nicknames = [];

    /**
     * @var array
     *
     * Format: <nickname, Prefix>
     */
    protected $prefixes = [];

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

        $server = $incomingMessage->getPrefix();
        [$nickname, $visibility, $channel, $nicknames] = $incomingMessage->getArgs();
        $nicknames = explode(' ', $nicknames);

        $prefixes = [];
        foreach ($nicknames as $key => $prefixString) {
            $prefix = Prefix::fromString($prefixString);

            // no nickname means this isn't a full prefix. do not try any further.
            if (empty($prefix->getNickname())) {
                break;
            }

            $prefixes[$prefix->getNickname()] = $prefix;

            // override the original nickname with the parsed result.
            $nicknames[$key] = $prefix->getNickname();
        }

        $object = new self();
        $object->setNickname($nickname);
        $object->setVisibility($visibility);
        $object->setChannel($channel);
        $object->setNicknames($nicknames);
        $object->setPrefixes($prefixes);
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

    /**
     * @return array
     */
    public function getPrefixes(): array
    {
        return $this->prefixes;
    }

    /**
     * @param array $prefixes
     */
    public function setPrefixes(array $prefixes): void
    {
        $this->prefixes = $prefixes;
    }
}
