<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;

/**
 * Class WebIrc
 * @package WildPHP\Messages
 *
 * Syntax: WEBIRC password gateway hostname ip
 *
 * @see https://ircv3.net/specs/extensions/webirc.html
 */
class WebIrc extends BaseIRCMessageImplementation implements OutgoingMessageInterface
{
    protected static $verb = 'WEBIRC';

    protected $password = '';

    protected $gateway = '';

    protected $hostname = '';

    protected $ipAddress = '';

    /**
     * WebIrc constructor.
     * @param string $password
     * @param string $gateway
     * @param string $hostname
     * @param string $ipAddress
     */
    public function __construct(string $password, string $gateway, string $hostname, string $ipAddress)
    {
        $this->password = $password;
        $this->gateway = $gateway;
        $this->hostname = $hostname;
        $this->ipAddress = $ipAddress;
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
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getGateway(): string
    {
        return $this->gateway;
    }

    /**
     * @param string $gateway
     */
    public function setGateway(string $gateway): void
    {
        $this->gateway = $gateway;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     */
    public function setHostname(string $hostname): void
    {
        $this->hostname = $hostname;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'WEBIRC %s %s %s %s',
            $this->getPassword(),
            $this->getGateway(),
            $this->getHostname(),
            $this->getIpAddress()
        );
    }
}
