<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\Traits;

use WildPHP\Messages\Interfaces\PrefixInterface;

trait PrefixTrait
{
    /**
     * @var PrefixInterface
     */
    protected $prefix;

    /**
     * @return PrefixInterface
     */
    public function getPrefix(): PrefixInterface
    {
        return $this->prefix;
    }

    /**
     * @param PrefixInterface $prefix
     */
    public function setPrefix(PrefixInterface $prefix): void
    {
        $this->prefix = $prefix;
    }
}
