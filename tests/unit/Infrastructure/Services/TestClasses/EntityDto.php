<?php

namespace unit\Infrastructure\Services\TestClasses;

class EntityDto
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var \unit\Infrastructure\Services\TestClasses\EntityInnerDto
     */
    public $inner;

    /**
     * @var \unit\Infrastructure\Services\TestClasses\EntityItemDto[]
     */
    public $items;

    /**
     * @var \Clean\Common\Utils\Extensions\DateTime
     */
    public $date;

    public function __construct($id)
    {
        $this->id = $id;
    }
}