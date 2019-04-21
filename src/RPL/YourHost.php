<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\RPL;

/**
 * Class YourHost
 * @package WildPHP\Messages\RPL
 *
 * Syntax: client 002 nickname :message
 */
class YourHost extends Welcome
{
    protected static $verb = '002';
}