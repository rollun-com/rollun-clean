<?php

namespace Clean\Common\Utils\Extensions\TypedCollection;

use Clean\Common\Utils\Extensions\Collection;

abstract class TypedCollection extends Collection
{
    public const TYPE_INTEGER = 'integer';

    public const TYPE_DOUBLE = 'double';

    public const TYPE_STRING = 'string';

    public const TYPE_RESOURCE = 'resource';

    public const TYPE_NULL = 'NULL';

    public const TYPE_ARRAY = 'array';

    public const TYPE_BOOLEAN = 'boolean';

    abstract protected function getType();

    protected function isValid($value)
    {
        if (is_object($value)) {
            return is_a($value, $this->getType(), true);
        }

        return gettype($value) === $this->getType();
    }

    protected function checkType($value)
    {
        if (!$this->isValid($value)) {
            throw new NotValidTypeException('Not valid type ' . gettype($value) . '. Expected ' . $this->getType());
        }
    }

    public function offsetSet(mixed $key, mixed $value)
    {
        $this->checkType($value);
        parent::offsetSet($key, $value);
    }

    public function append(mixed $value)
    {
        $this->checkType($value);
        parent::append($value);
    }

    public function column($column, $collection = null)
    {
        return $this->map(function ($item) use ($column) {
            return $this->getValueFromItem($item, $column);
        }, $collection ?? parent::class);
    }

    public function map(callable $callback, $collection = null)
    {
        $collection = $this->newCollection($collection);
        foreach ($this->getArrayCopy() as $key => $item) {
            $item = $callback($item, $key);
            $collection[$key] = $item;
        }

        return $collection;
    }

    public function mapWithKey(string $key, callable $callback = null, $collection = null)
    {
        $collection = $this->newCollection($collection);
        foreach ($this->getArrayCopy() as $item) {
            $name = $this->getValueFromItem($item, $key);
            if ($callback) {
                $item = $callback($item, $key);
            }
            $collection[$name] = $item;
        }

        return $collection;
    }

    protected function newCollection($collection = null)
    {
        if (!$collection) {
            $collection = static::class;
        }

        if (is_string($collection)) {
            $collection = new Collection();
        }

        if (!is_a($collection, Collection::class, true)) {
            throw new \Exception('Class must extend ' . Collection::class);
        }

        return $collection;
    }
}