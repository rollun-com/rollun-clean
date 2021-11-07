<?php

namespace Clean\Common\Application\Interfaces;

interface EntityMapperInterface
{
    /**
     * @param object $entity
     * @param object|string $dto
     * @return mixed
     */
    public function fromEntityToDto(object $entity, $dto);

    /**
     * @param object $dto
     * @param object|string $entity
     * @return mixed
     */
    public function fromDtoToEntity(object $dto, $entity);

    /**
     * @param array $data
     * @param object|string $instance
     * @return mixed
     */
    public function fromArrayToObject(array $data, $instance);

    /**
     * @param object $instance
     * @return mixed
     */
    public function fromObjectToArray(object $instance);
}