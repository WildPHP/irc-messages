<?php

/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Interfaces;


/**
 * Interface ReceivableMessage
 * @package WildPHP\Messages
 *
 * A syntax sample is included with all supported messages.
 */
interface IncomingMessageInterface extends IrcMessageImplementationInterface
{
    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return mixed
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage);
}