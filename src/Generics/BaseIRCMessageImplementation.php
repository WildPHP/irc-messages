<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Generics;

use WildPHP\Messages\Interfaces\IrcMessageImplementationInterface;

abstract class BaseIRCMessageImplementation implements IrcMessageImplementationInterface
{
    /**
     * @var string
     */
    protected static $verb;

    /**
     * Additional data to be sent with the message.
     * @var array
     */
    protected $tags = [];

    /**
     * @return string
     */
    public static function getVerb(): string
    {
        return static::$verb;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }
}