<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Data;

class DomainItem
{
    private $title;
    private $camelizedTitle;
    private $description;
    private $way;

    public function __construct($title, $description, $way)
    {
        $this->title = $title;
        $this->description = $description;
        $this->way = $way;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWay(): string
    {
        return $this->way;
    }

    public function getCamelizedTitle(string $suffix = ''): string
    {
        if ($this->camelizedTitle) {
            return $this->camelizedTitle;
        }

        return $this->camelizedTitle = DomainsData::getCamelizedTitle($this->title, $suffix);
    }
}
