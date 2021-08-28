<?php

declare(strict_types=1);

namespace Upside\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface CollectionInterface
 */
interface CollectionInterface extends Countable, ArrayAccess, IteratorAggregate
{
    /**
     * Возвращает базовый массив коллекции
     *
     * @link https://github.com/upside/collection#all
     *
     * @return array
     */
    public function all(): array;

    /**
     * Возвращает среднее значение по ключу
     *
     * @link https://github.com/upside/collection#avg
     *
     * @param callable|int|string|null $key
     *
     * @return float
     */
    public function avg(callable|int|string|null $key = null): float;

    /**
     * Псевдоним avg
     *
     * @link https://github.com/upside/collection#average
     *
     * @param callable|int|string|null $key
     *
     * @return float
     */
    public function average(callable|int|string|null $key = null): float;

    /**
     * Возвращает коллекцию разбитую на несколько меньших коллекций заданного размера
     *
     * @link https://github.com/upside/collection#chunk
     *
     * @param int $size
     *
     * @return static
     */
    public function chunk(int $size): static;

    /**
     * @link https://github.com/upside/collection#chunkWhile
     *
     * @param callable $callback
     *
     * @return static
     */
    public function chunkWhile(callable $callback): static;

    /**
     * Возвращает плоскую коллекцию
     *
     * @link https://github.com/upside/collection#collapse
     *
     * @return static
     */
    public function collapse(): static;

    /**
     * @link https://github.com/upside/collection#collect
     *
     * @return static
     */
    public function collect(): static;

    /**
     * Возвращает коллекцию используя значения коллекции в качестве ключей со значениями массива
     *
     * @link https://github.com/upside/collection#combine
     *
     * @param $values
     *
     * @return static
     */
    public function combine($values): static;

    /**
     * Возвращает коллекцию с добавленными в конец значениями $source
     *
     * @link https://github.com/upside/collection#concat
     *
     * @param CollectionInterface|array $source
     *
     * @return static
     */
    public function concat(CollectionInterface|array $source): static;

    /**
     * Проверяет наличие значения в коллекции
     *
     * @link https://github.com/upside/collection#contains
     *
     * @param mixed $value
     * @param callable|int|string|null $key
     * @param bool $strict
     *
     * @return bool
     */
    public function contains(mixed $value, callable|int|string|null $key = null, bool $strict = false): bool;

    /**
     * Проверяет (строго) наличие значения в коллекции
     *
     * @link https://github.com/upside/collection#containsStrict
     *
     * @param mixed $value
     * @param callable|int|string|null $key
     *
     * @return bool
     */
    public function containsStrict(mixed $value, callable|int|string|null $key = null): bool;

    /**
     * @link https://github.com/upside/collection#countBy
     *
     * @param callable|null $callback
     *
     * @return static
     */
    public function countBy(callable|null $callback = null): static;

    /**
     * @link https://github.com/upside/collection#crossJoin
     *
     * @param array ...$lists
     *
     * @return static
     */
    public function crossJoin(array ...$lists): static;

    /**
     * Возвращает коллекцию с элементами которых нет в $items
     *
     * @link https://github.com/upside/collection#diff
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function diff(CollectionInterface|array $items): static;

    /**
     * Возвращает коллекцию с элементами которых нет в $items c учётом ключей
     *
     * @link https://github.com/upside/collection#diffAssoc
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function diffAssoc(CollectionInterface|array $items): static;

    /**
     * Возвращает коллекцию с элементами которых нет в $items на основе ключей
     *
     * @link https://github.com/upside/collection#diffKeys
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function diffKeys(CollectionInterface|array $items): static;

    /**
     * Возвращает коллекцию повторяющихся значений
     *
     * @link https://github.com/upside/collection#duplicates
     *
     * @param callable|string|null $callback
     * @param bool $strict
     *
     * @return static
     */
    public function duplicates(callable|string|null $callback = null, bool $strict = false): static;

    /**
     * Алиас для duplicates но со строгим сравнением
     *
     * @link https://github.com/upside/collection#duplicatesStrict
     *
     * @param callable|string|null $callback
     *
     * @return static
     */
    public function duplicatesStrict(callable|string|null $callback = null): static;

    /**
     * @link https://github.com/upside/collection#each
     *
     * @param callable $callback
     *
     * @return static
     */
    public function each(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#eachSpread
     *
     * @param callable $callback
     *
     * @return static
     */
    public function eachSpread(callable $callback): static;

    /**
     * Проверяет все ли элементы проходят тест
     *
     * @link https://github.com/upside/collection#every
     *
     * @param callable $callback
     *
     * @return bool
     */
    public function every(callable $callback): bool;

    /**
     * @link https://github.com/upside/collection#except
     *
     * @param CollectionInterface|array $keys
     *
     * @return static
     */
    public function except(CollectionInterface|array $keys): static;

    /**
     * @link https://github.com/upside/collection#filter
     *
     * @param callable|null $callback
     *
     * @return static
     */
    public function filter(callable|null $callback = null): static;

    /**
     * @link https://github.com/upside/collection#first
     *
     * @param callable|null $callback
     * @param mixed $default
     *
     * @return mixed
     */
    public function first(callable|null $callback = null, mixed $default = null): mixed;

    /**
     * @link https://github.com/upside/collection#firstWhere
     *
     * @param callable|int|string|null $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return mixed
     */
    public function firstWhere(callable|int|string|null $key, Operator|null $operator = null, mixed $value = null): mixed;

    /**
     * @link https://github.com/upside/collection#flatMap
     *
     * @param callable $callback
     *
     * @return static
     */
    public function flatMap(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#flatten
     *
     * @param int|null $depth
     *
     * @return static
     */
    public function flatten(int|null $depth = null): static;

    /**
     * @link https://github.com/upside/collection#flip
     *
     * @return static
     */
    public function flip(): static;

    /**
     * @link https://github.com/upside/collection#forget
     *
     * @param $key
     *
     * @return static
     */
    public function forget($key): static;

    /**
     * @link https://github.com/upside/collection#forPage
     *
     * @param int $page
     * @param int $size
     *
     * @return static
     */
    public function forPage(int $page, int $size): static;

    /**
     * Возвращает элемент по заданному ключу. Если ключ не найден возвращает null
     *
     * @link https://github.com/upside/collection#get
     *
     * @param callable|int|string|null $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(callable|int|string|null $key, mixed $default = null): mixed;

    /**
     * Возвращает коллекцию сгруппированную по полю
     *
     * @link https://github.com/upside/collection#groupBy
     *
     * @param $groupBy
     * @param bool $preserveKeys
     *
     * @return static
     */
    public function groupBy($groupBy, bool $preserveKeys = false): static;

    /**
     * @link https://github.com/upside/collection#has
     *
     * @param callable|int|string $key
     *
     * @return bool
     */
    public function has(callable|int|string $key): bool;

    /**
     * @link https://github.com/upside/collection#implode
     *
     * @param string $separator
     * @param callable|int|string|null $key
     *
     * @return string
     */
    public function implode(string $separator, callable|int|string|null $key = null): string;

    /**
     * @link https://github.com/upside/collection#intersect
     *
     * @param CollectionInterface|array $values
     *
     * @return static
     */
    public function intersect(CollectionInterface|array $values): static;

    /**
     * @link https://github.com/upside/collection#intersectByKeys
     *
     * @param CollectionInterface|array $values
     *
     * @return static
     */
    public function intersectByKeys(CollectionInterface|array $values): static;

    /**
     * @link https://github.com/upside/collection#isEmpty
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @link https://github.com/upside/collection#isNotEmpty
     *
     * @return bool
     */
    public function isNotEmpty(): bool;

    /**
     * @link https://github.com/upside/collection#join
     *
     * @param string $glue
     * @param string $finalGlue
     *
     * @return string
     */
    public function join(string $glue, string $finalGlue = ''): string;

    /**
     * @link https://github.com/upside/collection#keyBy
     *
     * @param callable|int|string|null $key
     *
     * @return static
     */
    public function keyBy(callable|int|string|null $key): static;

    /**
     * @link https://github.com/upside/collection#keys
     *
     * @return static
     */
    public function keys(): static;

    /**
     * @link https://github.com/upside/collection#last
     *
     * @param callable|null $callback
     *
     * @return mixed
     */
    public function last(callable|null $callback = null): mixed;

    /**
     * @link https://github.com/upside/collection#map
     *
     * @param callable $callback
     *
     * @return static
     */
    public function map(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#mapInto
     *
     * @param string $class
     *
     * @return static
     */
    public function mapInto(string $class): static;

    /**
     * @link https://github.com/upside/collection#mapSpread
     *
     * @param callable $callback
     *
     * @return static
     */
    public function mapSpread(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#mapToGroups
     *
     * @param callable $callback
     *
     * @return static
     */
    public function mapToGroups(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#mapWithKeys
     *
     * @param callable $callback
     *
     * @return static
     */
    public function mapWithKeys(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#max
     *
     * @param callable|int|string|null $key
     *
     * @return mixed
     */
    public function max(callable|int|string|null $key = null): mixed;

    /**
     * @link https://github.com/upside/collection#median
     *
     * @param callable|int|string|null $key
     *
     * @return mixed
     */
    public function median(callable|int|string|null $key = null): mixed;

    /**
     * @link https://github.com/upside/collection#merge
     *
     * @param iterable $items
     *
     * @return static
     */
    public function merge(iterable $items): static;

    /**
     * @link https://github.com/upside/collection#mergeRecursive
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function mergeRecursive(CollectionInterface|array $items): static;

    /**
     * @link https://github.com/upside/collection#min
     *
     * @param callable|int|string|null $key
     */
    public function min(callable|int|string|null $key = null): mixed;

    /**
     * @link https://github.com/upside/collection#mode
     *
     * @param int|string|null $key
     *
     * @return array|null
     */
    public function mode(int|string|null $key = null): array|null;

    /**
     * @link https://github.com/upside/collection#nth
     *
     * @param int $step
     * @param int $offset
     *
     * @return static
     */
    public function nth(int $step, int $offset = 0): static;

    /**
     * @link https://github.com/upside/collection#only
     *
     * @param int[]|string[] $keys
     *
     * @return static
     */
    public function only(array $keys): static;

    /**
     * @link https://github.com/upside/collection#pad
     *
     * @param int $size
     * @param mixed $value
     *
     * @return static
     */
    public function pad(int $size, mixed $value): static;

    /**
     * @link https://github.com/upside/collection#partition
     *
     * @param callable|int|string|null $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return static
     */
    public function partition(callable|int|string|null $key, Operator|null $operator = null, mixed $value = null): static;

    /**
     * @link https://github.com/upside/collection#pipe
     *
     * @param callable $callback
     *
     * @return mixed
     */
    public function pipe(callable $callback): mixed;

    /**
     * @link https://github.com/upside/collection#pipeInto
     *
     * @param string $class
     *
     * @return object
     */
    public function pipeInto(string $class): object;

    /**
     * @link https://github.com/upside/collection#pluck
     *
     * @param callable|int|string|null $key
     *
     * @return static
     */
    public function pluck(callable|int|string|null $key = null): static;

    /**
     * @link https://github.com/upside/collection#pop
     *
     * @return mixed
     */
    public function pop(): mixed;

    /**
     * @link https://github.com/upside/collection#prepend
     *
     * @param mixed $value
     *
     * @return static
     */
    public function prepend(mixed $value): static;

    /**
     * @link https://github.com/upside/collection#pull
     *
     * @param callable|int|string $key
     *
     * @return mixed
     */
    public function pull(callable|int|string $key): mixed;

    /**
     * Добавляет элемент в конец коллекции
     *
     * @link https://github.com/upside/collection#push
     *
     * @param mixed $item
     *
     * @return static
     */
    public function push(mixed $item): static;

    /**
     * Устанавливает заданный ключ и значение для в коллекции
     *
     * @link https://github.com/upside/collection#put
     *
     * @param int|string $key
     * @param mixed $value
     *
     * @return static
     */
    public function put(int|string $key, mixed $value): static;

    /**
     *
     * Возвращает случайный элемент коллекции
     *
     * @link https://github.com/upside/collection#random
     *
     * @param int $items
     *
     * @return mixed
     */
    public function random(int $items = 1): mixed;

    /**
     * Сокращает коллекцию до одного значения передавая результат каждой итерации в следующую итерацию
     *
     * @link https://github.com/upside/collection#reduce
     *
     * @param callable $callback
     * @param mixed|null $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, mixed $initial = null): mixed;

    /**
     * @link https://github.com/upside/collection#reject
     *
     * @param callable $callback
     *
     * @return static
     */
    public function reject(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#replace
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function replace(CollectionInterface|array $items): static;

    /**
     * @link https://github.com/upside/collection#replaceRecursive
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function replaceRecursive(CollectionInterface|array $items): static;

    /**
     * @link https://github.com/upside/collection#reverse
     *
     * @return static
     */
    public function reverse(): static;

    /**
     * Ищет значение в коллекции и возвращает его ключ если найдено и false если не найдено
     *
     * @link https://github.com/upside/collection#search
     *
     * @param mixed $value
     * @param bool $strict
     *
     * @return int|string|bool
     */
    public function search(mixed $value, bool $strict = true): int|string|bool;

    /**
     * @link https://github.com/upside/collection#shift
     *
     * @return mixed
     */
    public function shift(): mixed;

    /**
     * @link https://github.com/upside/collection#shuffle
     *
     * @return self
     */
    public function shuffle(): self;

    /**
     * @link https://github.com/upside/collection#skip
     *
     * @param int $skip
     *
     * @return static
     */
    public function skip(int $skip): static;

    /**
     * @link https://github.com/upside/collection#skipUntil
     *
     * @param callable|int|string $value
     *
     * @return static
     */
    public function skipUntil(callable|int|string $value): static;

    /**
     * @link https://github.com/upside/collection#skipWhile
     *
     * @param callable $callback
     *
     * @return static
     */
    public function skipWhile(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#slice
     *
     * @param int $offset
     * @param int|null $length
     *
     * @return static
     */
    public function slice(int $offset, int|null $length = null): static;

    /**
     * @link https://github.com/upside/collection#sole
     *
     * @param callable|int|string|null $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return mixed
     */
    public function sole(callable|int|string|null $key = null, Operator|null $operator = null, mixed $value = null): mixed;

    /**
     * @link https://github.com/upside/collection#some
     *
     * @param mixed $value
     * @param callable|int|string|null $key
     *
     * @return bool
     */
    public function some(mixed $value, callable|int|string|null $key = null): bool;

    /**
     * @link https://github.com/upside/collection#sort
     *
     * @param callable|null $callback
     *
     * @return static
     */
    public function sort(callable|null $callback = null): static;

    /**
     * @link https://github.com/upside/collection#sortBy
     *
     * @param callable $callback
     * @param int $options
     * @param bool $descending
     *
     * @return static
     */
    public function sortBy(callable $callback, int $options = SORT_REGULAR, bool $descending = false): static;

    /**
     * @link https://github.com/upside/collection#sortByDesc
     *
     * @param callable $callback
     * @param int $options
     *
     * @return static
     */
    public function sortByDesc(callable $callback, int $options = SORT_REGULAR): static;

    /**
     * @link https://github.com/upside/collection#sortDesc
     *
     * @param int $options
     *
     * @return static
     */
    public function sortDesc(int $options = SORT_REGULAR): static;

    /**
     * @link https://github.com/upside/collection#sortKeys
     *
     * @param int $options
     * @param bool $descending
     *
     * @return static
     */
    public function sortKeys(int $options = SORT_REGULAR, bool $descending = false): static;

    /**
     * @link https://github.com/upside/collection#sortKeysDesc
     *
     * @param int $options
     *
     * @return static
     */
    public function sortKeysDesc(int $options = SORT_REGULAR): static;

    /**
     * @link https://github.com/upside/collection#splice
     *
     * @param int $offset
     * @param int|null $length
     * @param array $replacement
     *
     * @return static
     */
    public function splice(int $offset, ?int $length = null, array $replacement = []): static;

    /**
     * @link https://github.com/upside/collection#split
     *
     * @param int $numberOfGroups
     *
     * @return static
     */
    public function split(int $numberOfGroups): static;

    /**
     * @link https://github.com/upside/collection#splitIn
     *
     * @param int $numberOfGroups
     *
     * @return static
     */
    public function splitIn(int $numberOfGroups): static;

    /**
     * @link https://github.com/upside/collection#sum
     *
     * @param callable|int|string|null $key
     *
     * @return mixed
     */
    public function sum(callable|int|string|null $key = null): mixed;

    /**
     * @link https://github.com/upside/collection#take
     *
     * @param int $limit
     *
     * @return static
     */
    public function take(int $limit): static;

    /**
     * @link https://github.com/upside/collection#takeUntil
     *
     * @param mixed $value
     *
     * @return static
     */
    public function takeUntil(mixed $value): static;

    /**
     * @link https://github.com/upside/collection#takeWhile
     *
     * @param mixed $value
     *
     * @return static
     */
    public function takeWhile(mixed $value): static;

    /**
     * @link https://github.com/upside/collection#tap
     *
     * @param callable $callback
     *
     * @return static
     */
    public function tap(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#toArray
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * @link https://github.com/upside/collection#toJson
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * @link https://github.com/upside/collection#transform
     *
     * @param callable $callback
     *
     * @return static
     */
    public function transform(callable $callback): static;

    /**
     * @link https://github.com/upside/collection#union
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function union(CollectionInterface|array $items): static;

    /**
     * @link https://github.com/upside/collection#unique
     *
     * @param int|string|callable|null $key
     * @param bool $strict
     *
     * @return static
     */
    public function unique(int|string|callable|null $key = null, bool $strict = false): static;

    /**
     * @link https://github.com/upside/collection#uniqueStrict
     *
     * @param callable|int|string|null $key
     *
     * @return static
     */
    public function uniqueStrict(callable|int|string|null $key = null): static;

    /**
     * @link https://github.com/upside/collection#unless
     *
     * @param bool $condition
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function unless(bool $condition, callable $callback, callable|null $default = null): mixed;

    /**
     * @link https://github.com/upside/collection#unlessEmpty
     *
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function unlessEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @link https://github.com/upside/collection#unlessNotEmpty
     *
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function unlessNotEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @link https://github.com/upside/collection#values
     *
     * @return static
     */
    public function values(): static;

    /**
     * @link https://github.com/upside/collection#when
     *
     * @param bool $condition
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function when(bool $condition, callable $callback, callable|null $default = null): mixed;

    /**
     * @link https://github.com/upside/collection#whenEmpty
     *
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function whenEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @link https://github.com/upside/collection#whenNotEmpty
     *
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function whenNotEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @link https://github.com/upside/collection#where
     *
     * @param callable|int|string $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return static
     */
    public function where(callable|int|string $key, Operator|null $operator = null, mixed $value = null): static;

    /**
     * @link https://github.com/upside/collection#whereStrict
     *
     * @param callable|int|string $key
     * @param mixed $value
     *
     * @return static
     */
    public function whereStrict(callable|int|string $key, mixed $value = null): static;

    /**
     * @link https://github.com/upside/collection#whereBetween
     *
     * @param callable|int|string $key
     * @param mixed $from
     * @param mixed $to
     *
     * @return static
     */
    public function whereBetween(callable|int|string $key, mixed $from, mixed $to): static;

    /**
     * @link https://github.com/upside/collection#whereIn
     *
     * @param callable|int|string $key
     * @param CollectionInterface|array $values
     * @param bool $strict
     *
     * @return static
     */
    public function whereIn(callable|int|string $key, CollectionInterface|array $values, bool $strict = false): static;

    /**
     * @link https://github.com/upside/collection#whereInStrict
     *
     * @param callable|int|string $key
     * @param array $values
     *
     * @return static
     */
    public function whereInStrict(callable|int|string $key, array $values): static;

    /**
     * @link https://github.com/upside/collection#whereInstanceOf
     *
     * @param string|string[] $class
     *
     * @return static
     */
    public function whereInstanceOf(array|string $class): static;

    /**
     * @link https://github.com/upside/collection#whereNotBetween
     *
     * @param callable|int|string $key
     * @param mixed $from
     * @param mixed $to
     *
     * @return static
     */
    public function whereNotBetween(callable|int|string $key, mixed $from, mixed $to): static;

    /**
     * @link https://github.com/upside/collection#whereNotIn
     *
     * @param callable|int|string $key
     * @param CollectionInterface|array $values
     * @param bool $strict
     *
     * @return static
     */
    public function whereNotIn(callable|int|string $key, CollectionInterface|array $values, bool $strict = false): static;

    /**
     * @link https://github.com/upside/collection#whereNotInStrict
     *
     * @param callable|int|string $key
     * @param CollectionInterface|array $values
     *
     * @return static
     */
    public function whereNotInStrict(callable|int|string $key, CollectionInterface|array $values): static;

    /**
     * @link https://github.com/upside/collection#whereNotNull
     *
     * @param callable|int|string $key
     *
     * @return static
     */
    public function whereNotNull(callable|int|string $key): static;

    /**
     * @link https://github.com/upside/collection#whereNull
     *
     * @param callable|int|string $key
     *
     * @return static
     */
    public function whereNull(callable|int|string $key): static;

    /**
     * @link https://github.com/upside/collection#zip
     *
     * @param array ...$items
     *
     * @return static
     */
    public function zip(array ...$items): static;
}
