<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Interfaces;

interface IrcMessageInterface
{
    /**
     * @return string
     */
    public function getPrefix(): string;

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix);

    /**
     * @return string
     */
    public function getVerb(): string;

    /**
     * @param string $verb
     */
    public function setVerb(string $verb);

    /**
     * @return array
     */
    public function getArgs(): array;

    /**
     * @param array $args
     */
    public function setArgs(array $args);

    /**
     * @return array
     */
    public function getTags(): array;

    /**
     * @param array $tags
     */
    public function setTags(array $tags);
}