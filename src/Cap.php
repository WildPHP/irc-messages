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
 * Class Cap
 * @package WildPHP\Messages
 *
 * Syntax: prefix CAP nickname command [:capabilities]
 */
class Cap extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    use PrefixTrait;
    use NicknameTrait;

    protected static $verb = 'CAP';

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var array
     */
    protected $capabilities = [];

    /**
     * Cap constructor.
     *
     * @param string $command
     * @param array $capabilities
     */
    public function __construct(string $command, array $capabilities = [])
    {
        if (!in_array($command, ['LS', 'LIST', 'REQ', 'ACK', 'NAK', 'END'])) {
            throw new \InvalidArgumentException('Cap subcommand not valid');
        }

        $this->setCommand($command);
        $this->setCapabilities($capabilities);
    }

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

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        $args = $incomingMessage->getArgs();
        $nickname = array_shift($args);
        $command = array_shift($args);
        $capabilities = explode(' ', array_shift($args));

        $object = new self($command, $capabilities);
        $object->setNickname($nickname);
        $object->setPrefix($prefix);

        return $object;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command)
    {
        $this->command = $command;
    }

    /**
     * @return array
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * @param array $capabilities
     */
    public function setCapabilities(array $capabilities)
    {
        $this->capabilities = $capabilities;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $capabilities = implode(' ', $this->getCapabilities());

        return 'Cap ' . $this->getCommand() . (!empty($capabilities) ? ' :' . $capabilities : '') . "\r\n";
    }
}