<?php

namespace Clean\Common\Frameworks;

use Clean\Common\Application\Interfaces\EntityMapperInterface;
use Clean\Common\Infrastructure\Services\SimpleMapper\SimpleMapper;
use rollun\utils\Factory\AbstractServiceAbstractFactory;

/**
 * @todo Возможно конфиги фреймворка не нужны в этом пакете
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            AbstractServiceAbstractFactory::KEY => [
                EntityMapperInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => SimpleMapper::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => []
                ],
            ]
        ];
    }
}