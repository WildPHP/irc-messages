<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Interfaces;


use WildPHP\Messages\Generics\IncomingMessage;

/**
 * Interface ReceivableMessage
 * @package WildPHP\Messages
 *
 * A syntax sample is included with all supported messages.
 */
interface IncomingMessageInterface
{
    /**
     * @param IncomingMessage $incomingMessage
     *
     * @return mixed
     */
    public static function fromIncomingMessage(IncomingMessage $incomingMessage);

    /**
     * @return string
     */
    public static function getVerb(): string;
}