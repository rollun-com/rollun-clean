<?php

namespace unit\Infrastructure\Services\Classes;

class EntityDto
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var \unit\Infrastructure\Services\Classes\EntityInnerDto
     */
    public $inner;

    /**
     * @var \unit\Infrastructure\Services\Classes\EntityItemDto[]
     */
    public $items;

    public function __construct($id)
    {
        $this->id = $id;
    }
}