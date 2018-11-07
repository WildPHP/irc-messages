<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;


interface SendableMessage
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return string
     */
    public static function getVerb(): string;
}