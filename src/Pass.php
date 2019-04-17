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
 * Class PASS
 * @package WildPHP\Messages
 *
 * Syntax: PASS password
 */
class Pass extends BaseIRCMessageImplementation implements OutgoingMessageInterface
{
    protected static $verb = 'PASS';

    protected $password = '';

    /**
     * PASS constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->setPassword($password);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'PASS :' . $this->getPassword() . "\r\n";
    }
}
