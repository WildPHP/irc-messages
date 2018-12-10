<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class MODE
 * @package WildPHP\Messages
 *
 * Syntax (initial): nickname MODE nickname :modes
 * Syntax (user): prefix MODE nickname flags
 * Syntax (channel): prefix MODE #channel flags [arguments]
 */
class Mode extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    use PrefixTrait;
    use NicknameTrait;

    /**
     * @var string
     */
    protected static $verb = 'MODE';

    /**
     * @var string
     */
    protected $flags = '';

    /**
     * @var string
     */
    protected $target = '';

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * MODE constructor.
     *
     * @param string $target
     * @param string $flags
     * @param array $arguments
     */
    public function __construct(string $target, string $flags, array $arguments = [])
    {
        $this->setTarget($target);
        $this->setFlags($flags);
        $this->setArguments($arguments);
    }

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);

        $args = $incomingMessage->getArgs();
        $target = array_shift($args);
        $flags = array_shift($args);

        $object = new self($target, $flags, $args);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());

        return $object;
    }

    /**
     * @return string
     */
    public function getFlags(): string
    {
        return $this->flags;
    }

    /**
     * @param string $flags
     */
    public function setFlags(string $flags)
    {
        $this->flags = $flags;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $arguments = implode(' ', $this->getArguments());

        return 'MODE ' . $this->getTarget() . ' ' . $this->getFlags() . ' ' . $arguments . "\r\n";
    }
}