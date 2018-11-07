<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Generics;


class IncomingMessage
{
    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var string
     */
    protected $verb = '';

    /**
     * @var array
     */
    protected $args = [];

    /**
     * IncomingIrcMessage constructor.
     *
     * @param string $prefix
     * @param string $verb
     * @param array $args
     */
    public function __construct(string $prefix, string $verb, array $args = [])
    {
        $this->setPrefix($prefix);
        $this->setVerb($verb);
        $this->setArgs(array_values($args));
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getVerb(): string
    {
        return $this->verb;
    }

    /**
     * @param string $verb
     */
    public function setVerb(string $verb)
    {
        $this->verb = $verb;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }
}