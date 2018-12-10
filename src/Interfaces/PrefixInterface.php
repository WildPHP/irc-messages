<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Interfaces;

interface PrefixInterface
{
    /**
     * @return string
     */
    public function getNickname(): string;

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname);

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @param string $username
     */
    public function setUsername(string $username);

    /**
     * @return string
     */
    public function getHostname(): string;

    /**
     * @param string $hostname
     */
    public function setHostname(string $hostname);
}