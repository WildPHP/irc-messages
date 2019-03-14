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
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class VERSION
 * @package WildPHP\Messages
 *
 * Syntax: VERSION [server]
 */
class Version extends BaseIRCMessageImplementation implements OutgoingMessageInterface
{
    protected static $verb = 'VERSION';

    use ServerTrait;

    /**
     * WHOIS constructor.
     *
     * @param string $server
     */
    public function __construct(string $server = '')
    {
        $this->setServer($server);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $server = !empty($this->getServer()) ? ' ' . $this->getServer() : '';
        return 'VERSION' . $server;
    }
}