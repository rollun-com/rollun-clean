<?php

namespace unit\Infrastructure\Services\TestClasses;

class EntityInnerDto
{
    public $id;

    public $title;

    /**
     * @var \unit\Infrastructure\Services\TestClasses\EntityInnerDto
     */
    public $inner;

    public function __construct($id)
    {
        $this->id = $id;
    }
}