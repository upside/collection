<?php

declare(strict_types=1);

namespace Upside\Collection;

use ArrayIterator;
use Exception;
use InvalidArgumentException;
use JsonException;

class Collection implements CollectionInterface
{
    private array $items;

    public static function make(array $items = []): static
    {
        return new static($items);
    }

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function avg(callable|int|string $key = null): float
    {
        return $this->count() > 0 ? $this->sum($key) / $this->count() : 0;
    }

    /**
     * @inheritDoc
     */
    public function average(callable|int|string $key = null): float
    {
        return $this->avg($key);
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $size): static
    {
        $result = static::make();

        if ($size <= 0) {
            return $result;
        }

        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $result->push(static::make($chunk));
        }

        return $result;
    }

    /**
     * TODO: Не реализован
     *
     * @inheritDoc
     */
    public function chunkWhile(callable $callback): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function collapse(): static
    {
        $results = [];

        foreach ($this->items as $values) {
            if ($values instanceof static) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return static::make(array_merge([], ...$results));
    }

    /**
     * @inheritDoc
     */
    public function collect(): static
    {
        return static::make($this->items);
    }

    /**
     * @inheritDoc
     */
    public function combine($values): static
    {
        return static::make(array_combine($this->values()->all(), array_values($values)));
    }

    /**
     * TODO: Тут с поддержкой ключей, в интерфейсе без ключей
     *
     * @inheritDoc
     */
    public function concat(CollectionInterface|array $source): static
    {
        return static::make([...$this->items, ...$source]);
    }

    /**
     * @inheritDoc
     */
    public function contains(mixed $value, callable|int|string|null $key = null, bool $strict = false): bool
    {
        $retriever = $this->valueRetriever($key);
        $comparator = $this->duplicateComparator($strict);
        foreach ($this->items as $item) {
            if ($comparator($retriever($item), $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function containsStrict(mixed $value, callable|int|string|null $key = null): bool
    {
        return $this->contains($value, $key, true);
    }

    /**
     * @inheritDoc
     */
    public function countBy(callable|null $callback = null): static
    {
        $countBy = is_null($callback)
            ? $this->identity()
            : $this->valueRetriever($callback);

        $counts = [];

        foreach ($this->items as $key => $item) {
            $group = $countBy($item, $key);
            if (empty($counts[$group])) {
                $counts[$group] = 0;
            }
            $counts[$group]++;
        }

        return static::make($counts);
    }

    /**
     * @inheritDoc
     */
    public function crossJoin(array ...$lists): static
    {
        $results = [[]];
        array_unshift($lists, $this->items);
        foreach ($lists as $index => $array) {
            $append = [];
            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;

                    $append[] = $product;
                }
            }
            $results = $append;
        }

        return static::make($results);
    }

    /**
     * @inheritDoc
     */
    public function diff(CollectionInterface|array $items): static
    {
        return static::make(array_diff($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function diffAssoc(CollectionInterface|array $items): static
    {
        return static::make(array_diff_assoc($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function diffKeys(CollectionInterface|array $items): static
    {
        return static::make(array_diff_key($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function duplicates(callable|string $callback = null, bool $strict = false): static
    {
        $items = $this->pluck($callback);

        $uniqueItems = $items->unique(null, $strict);

        $compare = $this->duplicateComparator($strict);

        $duplicates = static::make();

        foreach ($items->all() as $key => $value) {
            if ($uniqueItems->isNotEmpty() && $compare($value, $uniqueItems->first())) {
                $uniqueItems->shift();
            } else {
                if ($k = $duplicates->search($value)) {
                    $duplicates->forget($k);
                }
                $duplicates->put($key, $value);
            }
        }

        return $duplicates;
    }

    /**
     * @inheritDoc
     */
    public function duplicatesStrict(callable|string $callback = null): static
    {
        return $this->duplicates($callback, true);
    }

    /**
     * @inheritDoc
     */
    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eachSpread(callable $callback): static
    {
        return $this->each(function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }

    /**
     * @inheritDoc
     */
    public function every(callable $callback): bool
    {
        foreach ($this->items as $key => $item) {
            if (!$callback($item, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function except(CollectionInterface|array $keys): static
    {
        return $this->filter(function ($value, $key) use ($keys) {
            return !in_array($key, $keys, true);
        });
    }

    /**
     * @inheritDoc
     */
    public function filter(callable|null $callback = null): static
    {
        if (is_null($callback)) {
            return static::make(array_filter($this->items));
        }

        return static::make(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @inheritDoc
     */
    public function first(callable|null $callback = null, mixed $default = null): mixed
    {
        if (is_null($callback)) {
            reset($this->items);
            $firstKey = key($this->items);

            return is_null($firstKey) ? $default : $this->items[$firstKey];
        }

        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                return $item;
            }
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function firstWhere(callable|int|string|null $key, Operator|null $operator = null, mixed $value = null): mixed
    {
        return $this->first($this->operatorForWhere($key, $operator, $value ?? true));
    }

    /**
     * @inheritDoc
     */
    public function flatMap(callable $callback): static
    {
        return $this->map($callback)->collapse();
    }

    /**
     * @inheritDoc
     */
    public function flatten(int|null $depth = null): static
    {
        $result = static::make();
        foreach ($this->items as $item) {
            $item = $item instanceof static ? $item->all() : $item;
            if (!is_array($item)) {
                $result->push($item);
            } else {
                if ($depth === 1) {
                    $values = array_values($item);
                } else {
                    $values = static::make(array_values($item))->flatten($depth - 1)->all();
                }
                foreach ($values as $value) {
                    $result->push($value);
                }
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function flip(): static
    {
        return static::make(array_flip($this->items));
    }

    /**
     * @inheritDoc
     */
    public function forget($key): static
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function forPage(int $page, int $size): static
    {
        if ($page > 0) {
            return $this->slice(($page - 1) * $size, $size);
        }

        return $this->slice($page * $size, $size);
    }

    /**
     * @inheritDoc
     */
    public function get(callable|int|string|null $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function groupBy($groupBy, bool $preserveKeys = false): static
    {
        if (is_array($groupBy) && !$this->useAsCallable($groupBy)) {
            $nextGroups = $groupBy;

            $groupBy = array_shift($nextGroups);
        }

        $groupBy = $this->valueRetriever($groupBy);

        $results = [];

        foreach ($this->items as $key => $value) {
            $groupKeys = $groupBy($value, $key);

            if (!is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }

            foreach ($groupKeys as $groupKey) {
                $groupKey = is_bool($groupKey) ? (int)$groupKey : $groupKey;

                if (!array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = static::make();
                }

                $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
            }
        }

        $result = static::make($results);

        if (!empty($nextGroups)) {
            return $result->map(function (CollectionInterface $item) use ($nextGroups, $preserveKeys) {
                return $item->groupBy($nextGroups, $preserveKeys);
            });
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function has(callable|int|string $key): bool
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        foreach ($key as $item) {
            if (!array_key_exists($item, $this->items)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function implode(string $separator, callable|int|string|null $key = null): string
    {
        return implode($separator, is_null($key) ? $this->items : $this->pluck($key)->all());
    }

    /**
     * @inheritDoc
     */
    public function intersect(CollectionInterface|array $values): static
    {
        $items = static::make($values);

        return $this->filter(function ($value) use ($items) {
            return in_array($value, $items->all(), true);
        });
    }

    /**
     * @inheritDoc
     */
    public function intersectByKeys(CollectionInterface|array $values): static
    {
        $items = static::make($values);

        return $this->filter(function ($value, $key) use ($items) {
            return $items->has($key);
        });
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * @inheritDoc
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * @inheritDoc
     */
    public function join(string $glue, string $finalGlue = ''): string
    {
        if ($finalGlue === '') {
            return $this->implode($glue);
        }

        $count = $this->count();

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $this->last();
        }

        $collection = static::make($this->items);

        $finalItem = $collection->pop();

        return $collection->implode($glue) . $finalGlue . $finalItem;
    }

    /**
     * @inheritDoc
     */
    public function keyBy(callable|int|string|null $key): static
    {
        $result = static::make();
        $retriever = $this->valueRetriever($key);
        foreach ($this->items as $item) {
            $result[$retriever($item)] = $item;
            $result->put($retriever($item), $item);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function keys(): static
    {
        return static::make(array_keys($this->items));
    }

    /**
     * @inheritDoc
     */
    public function last(callable|null $callback = null): mixed
    {
        if (is_null($callback)) {
            return end($this->items);
        }

        $result = null;

        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                $result = $item;
                continue;
            }
            break;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback): static
    {
        $result = static::make();
        foreach ($this->items as $key => $value) {
            $result->put($key, $callback($value, $key));
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function mapInto(string $class): static
    {
        return $this->map(function ($item) use ($class) {
            return new $class($item);
        });
    }

    /**
     * @inheritDoc
     */
    public function mapSpread(callable $callback): static
    {
        return $this->map(function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return is_array($chunk) ? $callback(...$chunk) : $callback(...$chunk->values()->all());
        });
    }

    /**
     * @inheritDoc
     */
    public function mapToGroups(callable $callback): static
    {
        $groups = $this->mapToDictionary($callback);

        return $groups->map([$this, 'make']);
    }

    public function mapToDictionary(callable $callback): static
    {
        $dictionary = [];

        foreach ($this->items as $key => $item) {
            $pair = $callback($item, $key);

            $key = key($pair);

            $value = reset($pair);

            if (!isset($dictionary[$key])) {
                $dictionary[$key] = [];
            }

            $dictionary[$key][] = $value;
        }

        return static::make($dictionary);
    }

    /**
     * @inheritDoc
     */
    public function mapWithKeys(callable $callback): static
    {
        $result = static::make();
        foreach ($this->items as $key => $value) {
            $assoc = $callback($value, $key);
            foreach ($assoc as $mapKey => $mapValue) {
                $result[$mapKey] = $mapValue;
                $result->put($mapKey, $mapValue);
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function max(callable|int|string|null $key = null): mixed
    {
        return max(is_null($key) ? $this->items : $this->pluck($key)->all());
    }

    /**
     * @inheritDoc
     */
    public function median(callable|int|string|null $key = null): mixed
    {
        $values = (isset($key) ? $this->pluck($key) : $this)
            ->filter(function ($item) {
                return !is_null($item);
            })->sort()->values();

        $count = $values->count();

        if ($count === 0) {
            return null;
        }

        $middle = (int)($count / 2);

        if ($count % 2) {
            return $values->get($middle);
        }

        return static::make([
            $values->get($middle - 1),
            $values->get($middle),
        ])->average();
    }

    /**
     * @inheritDoc
     */
    public function merge(iterable $items): static
    {
        return static::make(array_merge($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function mergeRecursive(CollectionInterface|array $items): static
    {
        return static::make(array_merge_recursive($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function min(callable|int|string|null $key = null): mixed
    {
        return min(is_null($key) ? $this->items : $this->pluck($key)->toArray());
    }

    /**
     * @inheritDoc
     */
    public function mode(int|string $key = null): array|null
    {
        if ($this->count() === 0) {
            return null;
        }

        $collection = is_null($key) ? $this : $this->pluck($key);

        $counts = static::make();

        $collection->each(function ($value) use ($counts) {
            $counts->put($value, $counts->has($value) ? $counts->get($value) + 1 : 1);
        });

        $sorted = $counts->sort();

        $highestValue = $sorted->last();

        return $sorted
            ->filter(function ($value) use ($highestValue) { return $value == $highestValue; })
            ->sort()
            ->keys()
            ->all();
    }

    /**
     * @inheritDoc
     */
    public function nth(int $step, int $offset = 0): static
    {
        $result = static::make();
        $position = 0;

        foreach ($this->items as $item) {
            if ($position % $step === $offset) {
                $result->push($item);
            }
            $position++;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function only(array $keys): static
    {
        return static::make(array_intersect_key($this->items, array_flip($keys)));
    }

    /**
     * @inheritDoc
     */
    public function pad(int $size, mixed $value): static
    {
        return static::make(array_pad($this->items, $size, $value));
    }

    /**
     * @inheritDoc
     */
    public function partition(callable|int|string|null $key, Operator|null $operator = null, mixed $value = null): static
    {
        $passed = [];
        $failed = [];

        $callback = is_null($operator) && is_null($value)
            ? $this->valueRetriever($key)
            : $this->operatorForWhere($key, $operator ?? Operator::EQUAL, $value ?? true);

        foreach ($this->items as $k => $item) {
            if ($callback($item, $key)) {
                $passed[$k] = $item;
            } else {
                $failed[$k] = $item;
            }
        }

        return static::make([static::make($passed), static::make($failed)]);
    }

    /**
     * @inheritDoc
     */
    public function pipe(callable $callback): mixed
    {
        return $callback($this);
    }

    /**
     * @inheritDoc
     */
    public function pipeInto(string $class): object
    {
        return new $class($this);
    }

    /**
     *
     * TODO: почему бы не использовать array_column()
     *
     * @inheritDoc
     */
    public function pluck(callable|int|string|null $key = null): static
    {
        $retriever = $this->valueRetriever($key);
        $result = static::make();
        foreach ($this->items as $item) {
            $result->push($retriever($item));
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function pop(): mixed
    {
        return array_pop($this->items);
    }

    /**
     * @inheritDoc
     */
    public function prepend($value): static
    {
        array_unshift($this->items, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function pull(callable|int|string $key): mixed
    {
        $value = $this->items[$key] ?? null;
        $this->forget($key);

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function push(mixed $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function put(int|string $key, mixed $value): static
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function random(int $items = 1): mixed
    {
        if ($this->count() < $items) {
            throw new InvalidArgumentException();
        }

        return $items > 1 ? $this->only(array_rand($this->items, $items)) : $this->get(array_rand($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $result = $initial;
        foreach ($this->items as $key => $item) {
            $result = $callback($result, $item, $key);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function reject(callable $callback): static
    {
        return $this->filter(function ($value, $key) use ($callback) {
            return $this->useAsCallable($callback) ? !$callback($value, $key) : $value !== $callback;
        });
    }

    /**
     * @inheritDoc
     */
    public function replace(CollectionInterface|array $items): static
    {
        return static::make(array_replace($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function replaceRecursive(CollectionInterface|array $items): static
    {
        return static::make(array_replace_recursive($this->items, $items));
    }

    /**
     * @inheritDoc
     */
    public function reverse(): static
    {
        return static::make(array_reverse($this->items, true));
    }

    /**
     * @inheritDoc
     */
    public function search(mixed $value, bool $strict = true): int|string|bool
    {
        if ($this->useAsCallable($value)) {
            foreach ($this->items as $key => $item) {
                if ($value($item, $key)) {
                    return $key;
                }
            }

            return false;
        }

        return array_search($value, $this->items, $strict);
    }

    /**
     * @inheritDoc
     */
    public function shift(): mixed
    {
        return array_shift($this->items);
    }

    /**
     * @inheritDoc
     */
    public function shuffle(): static
    {
        shuffle($this->items);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function skip(int $skip): static
    {
        return $this->slice($skip);
    }

    /**
     * @inheritDoc
     */
    public function skipUntil(callable|int|string $value): static
    {
        $callback = $this->useAsCallable($value) ? $value : $this->equality($value);

        return $this->skipWhile($this->negate($callback));
    }

    /**
     * @inheritDoc
     */
    public function skipWhile(callable $callback): static
    {
        $result = static::make();
        $return = false;
        foreach ($this->items as $key => $item) {

            if ($callback($item)) {
                $return = true;
            }

            if ($return) {
                $result->put($key, $item);
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function slice(int $offset, int|null $length = null): static
    {
        return static::make(array_slice($this->items, $offset, $length));
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function sole(callable|int|string|null $key = null, Operator|null $operator = null, mixed $value = null): mixed
    {
        $result = $this->filter($this->operatorForWhere($key, $operator ?? Operator::EQUAL, $value ?? true));

        if ($result->isEmpty()) {
            throw new Exception('Not found');
        }

        if ($result->count() > 1) {
            throw new Exception('Found multiple');
        }

        return $result->first();
    }

    /**
     * @inheritDoc
     */
    public function some(mixed $value, callable|int|string|null $key = null): bool
    {
        return $this->contains($value, $key);
    }

    /**
     * @inheritDoc
     */
    public function sort(callable|null $callback = null): static
    {
        $items = $this->items;
        is_callable($callback) ? uasort($items, $callback) : asort($items, SORT_REGULAR);

        return static::make($items);
    }

    /**
     * TODO: Не реализовано
     *
     * @inheritDoc
     */
    public function sortBy($callback, int $options = SORT_REGULAR, bool $descending = false): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sortByDesc(callable $callback, int $options = SORT_REGULAR): static
    {
        return $this->sortBy($callback, $options, true);
    }

    /**
     * @inheritDoc
     */
    public function sortDesc(int $options = SORT_REGULAR): static
    {
        $items = $this->items;
        arsort($items, $options);

        return static::make($items);
    }

    /**
     * @inheritDoc
     */
    public function sortKeys(int $options = SORT_REGULAR, bool $descending = false): static
    {
        $items = $this->items;
        $descending ? krsort($items, $options) : ksort($items, $options);

        return static::make($items);
    }

    /**
     * @inheritDoc
     */
    public function sortKeysDesc(int $options = SORT_REGULAR): static
    {
        return $this->sortKeys($options, true);
    }

    /**
     * @inheritDoc
     */
    public function splice(int $offset, int|null $length = null, array $replacement = []): static
    {
        if (func_num_args() === 1) {
            return static::make(array_splice($this->items, $offset));
        }

        return static::make(array_splice($this->items, $offset, $length, $replacement));
    }

    /**
     * @inheritDoc
     */
    public function split(int $numberOfGroups): static
    {
        if ($this->isEmpty()) {
            return static::make();
        }

        $groups = static::make();

        $groupSize = (int)floor($this->count() / $numberOfGroups);

        $remain = $this->count() % $numberOfGroups;

        $start = 0;

        for ($i = 0; $i < $numberOfGroups; $i++) {
            $size = $groupSize;

            if ($i < $remain) {
                $size++;
            }

            if ($size) {
                $groups->push(static::make(array_slice($this->items, $start, $size)));

                $start += $size;
            }
        }

        return $groups;
    }

    /**
     * @inheritDoc
     */
    public function splitIn(int $numberOfGroups): static
    {
        return $this->chunk((int)ceil($this->count() / $numberOfGroups));
    }

    /**
     * @inheritDoc
     */
    public function sum(callable|int|string|null $key = null): mixed
    {
        $result = 0;
        $retriever = $this->valueRetriever($key);
        foreach ($this->items as $item) {
            $result += $retriever($item);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function take(int $limit): static
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }

        return $this->slice(0, $limit);
    }

    /**
     * @inheritDoc
     */
    public function takeUntil($value): static
    {
        $callback = $this->useAsCallable($value) ? $value : $this->equality($value);

        $result = static::make();

        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                return $result;
            }
            $result->put($key, $item);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function takeWhile($value): static
    {
        $result = static::make();
        foreach ($this->items as $key => $item) {
            if ($value($item, $key)) {
                $result->put($key, $item);
                continue;
            }
            break;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function tap(callable $callback): static
    {
        $callback(clone $this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this
            ->map(function ($value) {
                return $value instanceof static ? $value->toArray() : $value;
            })
            ->all();
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @inheritDoc
     */
    public function transform(callable $callback): static
    {
        $this->items = $this->map($callback)->all();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function union(CollectionInterface|array $items): static
    {
        return static::make($this->items + $items);
    }

    /**
     * @inheritDoc
     */
    public function unique(int|string|callable|null $key = null, bool $strict = false): static
    {
        $callback = $this->valueRetriever($key);
        $exists = [];

        return $this->reject(function ($item, $key) use ($callback, $strict, &$exists) {
            if (in_array($id = $callback($item, $key), $exists, $strict)) {
                return true;
            }
            $exists[] = $id;

            return false;
        });
    }

    /**
     * @inheritDoc
     */
    public function uniqueStrict(callable|int|string|null $key = null): static
    {
        return $this->unique($key, true);
    }

    /**
     * @inheritDoc
     */
    public function unless(bool $condition, callable $callback, callable|null $default = null): mixed
    {
        return $this->when(!$condition, $callback, $default);
    }

    /**
     * @inheritDoc
     */
    public function unlessEmpty(callable $callback, callable|null $default = null): mixed
    {
        return $this->whenNotEmpty($callback, $default);
    }

    /**
     * @inheritDoc
     */
    public function unlessNotEmpty(callable $callback, callable|null $default = null): mixed
    {
        return $this->whenEmpty($callback, $default);
    }

    /**
     * @inheritDoc
     */
    public function values(): static
    {
        return static::make(array_values($this->items));
    }

    /**
     * @inheritDoc
     */
    public function when(bool $condition, callable $callback, callable|null $default = null): mixed
    {
        if ($condition) {
            return $callback($this, $condition);
        }
        if (!is_null($default)) {
            return $default($this, $condition);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whenEmpty(callable $callback, callable|null $default = null): mixed
    {
        return $this->when($this->isEmpty(), $callback, $default);
    }

    /**
     * @inheritDoc
     */
    public function whenNotEmpty(callable $callback, callable|null $default = null): mixed
    {
        return $this->when($this->isNotEmpty(), $callback, $default);
    }

    /**
     * @inheritDoc
     */
    public function where(callable|int|string $key, Operator|null $operator = null, mixed $value = null): static
    {
        return $this->filter($this->operatorForWhere($key, $operator, $value));
    }

    /**
     * @inheritDoc
     */
    public function whereStrict(callable|int|string $key, mixed $value = null): static
    {
        return $this->where($key, Operator::EQUAL_STRICT, $value);
    }

    /**
     * @inheritDoc
     */
    public function whereBetween(callable|int|string $key, mixed $from, mixed $to): static
    {
        return $this->where($key, Operator::GREATER_THAN, $from)->where($key, Operator::LESS_THAN, $to);
    }

    /**
     * @inheritDoc
     */
    public function whereIn(callable|int|string $key, CollectionInterface|array $values, bool $strict = false): static
    {
        $retriever = $this->valueRetriever($key);

        return $this->filter(static function ($item) use ($retriever, $values, $strict) {
            return in_array($retriever($item), $values, $strict);
        });
    }

    /**
     * @inheritDoc
     */
    public function whereInStrict(callable|int|string $key, array $values): static
    {
        return $this->whereIn($key, $values, true);
    }

    /**
     * @inheritDoc
     */
    public function whereInstanceOf(array|string $class): static
    {
        return $this->filter(function ($value) use ($class) {
            if (is_array($class)) {
                foreach ($class as $classType) {
                    if ($value instanceof $classType) {
                        return true;
                    }
                }

                return false;
            }

            return $value instanceof $class;
        });
    }

    /**
     * @inheritDoc
     */
    public function whereNotBetween(callable|int|string $key, mixed $from, mixed $to): static
    {
        $retriever = $this->valueRetriever($key);

        return $this->filter(static function ($item) use ($retriever, $from, $to) {
            $value = $retriever($item);

            return $value < $from || $value > $to;
        });
    }

    /**
     * @inheritDoc
     */
    public function whereNotIn(callable|int|string $key, CollectionInterface|array $values, bool $strict = false): static
    {
        $retriever = $this->valueRetriever($key);

        return $this->filter(static function ($item) use ($retriever, $values, $strict) {
            return !in_array($retriever($item), $values, $strict);
        });
    }

    /**
     * @inheritDoc
     */
    public function whereNotInStrict(callable|int|string $key, CollectionInterface|array $values): static
    {
        return $this->whereNotIn($key, $values, true);
    }

    /**
     * @inheritDoc
     */
    public function whereNotNull(callable|int|string $key): static
    {
        return $this->where($key, Operator::NOT_EQUAL_STRICT, null);
    }

    /**
     * @inheritDoc
     */
    public function whereNull(callable|int|string $key): static
    {
        return $this->whereStrict($key, null);
    }

    /**
     * @inheritDoc
     */
    public function zip(array ...$items): static
    {
        $params = array_merge([
            function (...$items) {
                return static::make($items);
            }, $this->items,
        ], $items);

        return static::make(array_map(...$params));
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \Traversable|ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Make a function to check an item's equality.
     *
     * @param mixed $value
     *
     * @return callable
     */
    protected function equality(mixed $value): callable
    {
        return static function ($item) use ($value) {
            return $item === $value;
        };
    }

    /**
     * Make a function that returns what's passed to it.
     *
     * @return callable
     */
    protected function identity(): callable
    {
        return static function ($value) {
            return $value;
        };
    }

    /**
     * Make a function using another function, by negating its result.
     *
     * @param callable $callback
     *
     * @return callable
     */
    protected function negate(callable $callback): callable
    {
        return static function (...$params) use ($callback) {
            return !$callback(...$params);
        };
    }

    protected function operatorForWhere($key, Operator|null $operator, $value = true): callable
    {
        return function ($item) use ($key, $operator, $value) {
            $retrieved = $this->valueRetriever($key)($item);

            return match ($operator) {
                Operator::EQUAL => $retrieved == $value,
                Operator::NOT_EQUAL => $retrieved != $value,
                Operator::LESS_THAN => $retrieved < $value,
                Operator::GREATER_THAN => $retrieved > $value,
                Operator::LESS_THAN_OR_EQUAL => $retrieved <= $value,
                Operator::GREATER_THAN_OR_EQUAL => $retrieved >= $value,
                Operator::EQUAL_STRICT => $retrieved === $value,
                Operator::NOT_EQUAL_STRICT => $retrieved !== $value,
                default => $retrieved == $value,
            };
        };
    }

    protected function valueRetriever(callable|int|string|null $value = null): callable
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return static function ($item) use ($value) {
            if (is_null($value)) {
                return $item;
            }
            if (is_object($item)) {
                return $item->$value;
            }
            if (is_array($item)) {
                return $item[$value];
            }

            return $item;
        };
    }

    protected function useAsCallable(mixed $value): bool
    {
        return !is_string($value) && is_callable($value);
    }

    protected function duplicateComparator(bool $strict = true): callable
    {
        if ($strict) {
            return static function ($a, $b) {
                return $a === $b;
            };
        }

        return static function ($a, $b) {
            return $a == $b;
        };
    }
}
