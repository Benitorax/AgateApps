<?php

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
