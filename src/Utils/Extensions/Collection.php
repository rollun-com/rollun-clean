<?php


namespace Clean\Common\Utils\Extensions;


use Clean\Common\Domain\Interfaces\ArrayableInterface;
use Clean\Common\Domain\Interfaces\JsonableInterface;

class Collection extends \ArrayIterator implements ArrayableInterface, JsonableInterface
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $results = [];
        foreach ($this->getArrayCopy() as $item) {
            if ($item instanceof ArrayableInterface) {
                $results[] = $item->toArray();
            } elseif (is_scalar($item)) {
                $results[] = $item;
            } else {
                $results[] = (array) $item;
            }
        }

        return $results;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function column($column)
    {
        return $this->map(function ($item) use ($column) {
            return $this->getValueFromItem($item, $column);
        });
    }

    /**
     * @param string $key
     * @param callable|null $callback
     * @return Collection
     * @todo
     */
    public function mapWithKey(string $key, callable $callback = null)
    {
        $collection = $this->newCollection();
        foreach ($this->getArrayCopy() as $item) {
            $name = $this->getValueFromItem($item, $key);
            if ($callback) {
                $item = $callback($item, $key);
            }
            $collection[$name] = $item;
        }

        return $collection;
    }

    /**
     * @param callable $callback
     * @return Collection
     */
    public function map(callable $callback)
    {
        $collection = $this->newCollection();
        foreach ($this->getArrayCopy() as $key => $item) {
            $item = $callback($item, $key);
            $collection[$key] = $item;
        }

        return $collection;
    }

    /**
     * @param callable $callback
     * @return Collection
     */
    public function filter(callable $callback)
    {
        $collection = $this->newCollection();
        foreach (array_filter($this->getArrayCopy(), $callback, ARRAY_FILTER_USE_BOTH) as $key => $item) {
            $collection[$key] = $item;
        }

        return $collection;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        $this->rewind();
        return $this->current();
    }

    public function chunk(int $length)
    {
        $result = $this->newCollection();
        foreach (array_chunk($this->getArrayCopy(), $length) as $key => $group) {
            $collection = $this->newCollection($group);
            $result[] = $collection;
        }

        return $result;
    }

    public function eachChunk(int $length, callable $callback)
    {
        foreach ($this->chunk($length) as $key => $group) {
            $callback($group, $key);
        }
    }

    public function __toString()
    {
        return (string) json_encode($this->toArray());
    }

    public function merge(Collection $collection)
    {
        return $this->newCollection(
            array_merge(
                $this->getArrayCopy(),
                $collection->getArrayCopy()
            )
        );
    }

    protected function getValueFromItem($item, $key)
    {
        if (is_object($item)) {
            $getter = 'get' . $key;
            if (method_exists($item, $getter)) {
                return $item->{$getter}();
            }

            if (property_exists($item, $key)) {
                return $item->{$key};
            }
        } elseif (is_array($item)) {
            return $item[$key];
        }

        throw new \Exception('Can not get ' . $key . ' value from item type ' . gettype($item));
    }

    public function groupByColumn($column)
    {
        $collection = $this->newCollection();
        foreach ($this->getArrayCopy() as $key => $item) {
            $value = $this->getValueFromItem($item, $column);
            $group = $collection[$value] ?? null;
            if (!$group) {
                $group = new static();
                $collection[$value] = $group;
            }
            $group[] = $item;
        }

        return $collection;
    }

    public function diff($another)
    {
        if ($another instanceof Collection) {
            $another->getArrayCopy();
        }

        $diff = array_diff($this->getArrayCopy(), $another);

        return $this->newCollection($diff);
    }

    public function unique()
    {
        return $this->newCollection(array_unique($this->getArrayCopy()));
    }

    public function each(callable $callback)
    {
        foreach ($this as $key => $value) {
            $callback($value, $key);
        }
    }

    public function newCollection($values = []): self
    {
        return new static($values);
    }
}