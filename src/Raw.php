<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;

/**
 * Class RAW
 * @package WildPHP\Messages
 *
 * Syntax: N/A
 *
 * Should only be used for development purposes.
 */
class Raw extends BaseIRCMessageImplementation implements OutgoingMessageInterface
{
    /**
     * It is unsafe to rely on the verb of this message type.
     * @var string
     */
    public static $verb = 'WPHP_RAW';

    /**
     * @var string
     */
    protected $command;

    /**
     * RAW constructor.
     *
     * @param string $command
     */
    public function __construct(string $command)
    {
        trigger_error('The use of the RAW IRC message type is meant for development purposes only. ' .
            'If the message you want to use is not implemented, please file a bug report.');
        $this->setCommand($command);
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
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCommand() . "\r\n";
    }
}