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
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class Cap
 * @package WildPHP\Messages
 *
 * Syntax: prefix CAP nickname sub-command (*) [:capabilities]
 *
 * This definition implements version 3.1 and 3.2 of the IRCv3 capability negotiation spec
 * as described in the following documents:
 * https://ircv3.net/specs/core/capability-negotiation-3.1.html
 * https://ircv3.net/specs/core/capability-negotiation-3.2.html
 */
class Cap extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    use PrefixTrait;

    protected static $verb = 'CAP';

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string
     */
    protected $clientIdentifier = '';

    /**
     * @var array
     */
    protected $capabilities = [];

    /**
     * @var bool
     */
    private $finalMessage;

    /**
     * Cap constructor.
     *
     * @param string $command
     * @param array $capabilities
     * @param bool $finalMessage
     */
    public function __construct(string $command, array $capabilities = [], bool $finalMessage = true)
    {
        if (!in_array($command, ['LS', 'LIST', 'REQ', 'ACK', 'NAK', 'END', 'NEW', 'DEL']) && preg_match('/^LS \d{3}$/', $command) === 0) {
            throw new \InvalidArgumentException('Cap sub-command not valid');
        }

        $this->setCommand($command);
        $this->setCapabilities($capabilities);
        $this->finalMessage = $finalMessage;
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
        $isFinal = count($args) == 3;
        $clientIdentifier = array_shift($args);
        $command = array_shift($args);

        if (!$isFinal)
            array_shift($args);

        $capabilities = explode(' ', array_shift($args));

        $object = new self($command, $capabilities, $isFinal);
        $object->setClientIdentifier($clientIdentifier);
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

        return 'CAP ' . $this->getCommand() . (!empty($capabilities) ? ' :' . $capabilities : '') . "\r\n";
    }

    /**
     * @return string
     */
    public function getClientIdentifier(): string
    {
        return $this->clientIdentifier;
    }

    /**
     * @param string $clientIdentifier
     */
    public function setClientIdentifier(string $clientIdentifier): void
    {
        $this->clientIdentifier = $clientIdentifier;
    }

    /**
     * @return bool
     */
    public function isFinalMessage(): bool
    {
        return $this->finalMessage;
    }

    /**
     * @param bool $finalMessage
     */
    public function setFinalMessage(bool $finalMessage): void
    {
        $this->finalMessage = $finalMessage;
    }
}