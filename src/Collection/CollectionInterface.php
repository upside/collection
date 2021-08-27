<?php

declare(strict_types=1);

namespace Upside\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface CollectionInterface
 *
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
interface CollectionInterface extends Countable, ArrayAccess, IteratorAggregate
{
    /**
     * The all method returns the underlying array represented by the collection
     *
     * Возвращает базовый массив коллекции
     *
     * collect([1, 2, 3])->all();
     *
     * [1, 2, 3]
     *
     * @return array
     * @psalm-return T[]
     */
    public function all(): array;

    /**
     * The avg method returns the average value of a given key
     *
     * Возвращает среднее значение по ключу
     *
     * $average = collect([
     *      ['foo' => 10],
     *      ['foo' => 10],
     *      ['foo' => 20],
     *      ['foo' => 40]
     *  ])->avg('foo');
     *
     * 20
     *
     * $average = collect([1, 1, 2, 4])->avg();
     *
     * 2
     *
     * @param callable|int|string|null $key
     *
     * @psalm-param TKey|callable|null $key
     *
     * @return float
     */
    public function avg(callable|int|string|null $key = null): float;

    /**
     * Alias for the avg method.
     *
     * Псевдоним avg
     *
     * @param callable|int|string|null $key
     *
     * @psalm-param TKey|callable|null $key
     *
     * @return float
     */
    public function average(callable|int|string|null $key = null): float;

    /**
     * The chunk method breaks the collection into multiple, smaller collections of a given size
     *
     * Возвращает коллекцию разбитую на несколько меньших коллекций заданного размера
     *
     * $collection = collect([1, 2, 3, 4, 5, 6, 7]);
     *
     * $chunks = $collection->chunk(4);
     *
     * $chunks->all();
     *
     * [[1, 2, 3, 4], [5, 6, 7]]
     *
     * @param int $size
     *
     * @return static
     */
    public function chunk(int $size): static;

    /**
     * The chunkWhile method breaks the collection into multiple,
     * smaller collections based on the evaluation of the given callback.
     * The $chunk variable passed to the closure may be used to inspect the previous element
     *
     * @param callable $callback
     *
     * @return static
     */
    public function chunkWhile(callable $callback): static;

    /**
     * The collapse method collapses a collection of arrays into a single, flat collection
     *
     * Возвращает плоскую коллекцию
     *
     * $collection = collect([
     *     [1, 2, 3],
     *     [4, 5, 6],
     *     [7, 8, 9],
     * ]);
     *
     * $collapsed = $collection->collapse();
     *
     * $collapsed->all();
     *
     * [1, 2, 3, 4, 5, 6, 7, 8, 9]
     *
     * @return static
     */
    public function collapse(): static;

    /**
     * The collect method returns a new Collection instance with the items currently in the collection
     *
     * @return static
     */
    public function collect(): static;

    /**
     * The combine method combines the values of the collection, as keys, with the values of another array or collection
     *
     * Возвращает коллекцию используя значения коллекции в качестве ключей со значениями массива
     *
     * $collection = collect(['name', 'age']);
     *
     * $combined = $collection->combine(['George', 29]);
     *
     * $combined->all();
     *
     * ['name' => 'George', 'age' => 29]
     *
     * @param $values
     *
     * @return static
     */
    public function combine($values): static;

    /**
     * Возвращает коллекцию с добавленными в конец значениями $source
     *
     * $collection = collect(['John Doe']);
     *
     * $concatenated = $collection->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);
     *
     * $concatenated->all();
     *
     * ['John Doe', 'Jane Doe', 'Johnny Doe']
     *
     * @param CollectionInterface|array $source
     *
     * @return static
     */
    public function concat(CollectionInterface|array $source): static;

    /**
     * Проверяет наличие значения в коллекции
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
     * @param mixed $value
     *
     * @param callable|int|string|null $key
     *
     * @return bool
     */
    public function containsStrict(mixed $value, callable|int|string|null $key = null): bool;

    /**
     * @param callable|null $callback
     *
     * @return static
     */
    public function countBy(callable|null $callback = null): static;

    /**
     * @param array ...$lists
     *
     * @return static
     */
    public function crossJoin(array ...$lists): static;

    /**
     * Возвращает коллекцию с элементами которых нет в $items
     *
     * $collection = collect([1, 2, 3, 4, 5]);
     *
     * $diff = $collection->diff([2, 4, 6, 8]);
     *
     * $diff->all();
     *
     * [1, 3, 5]
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function diff(CollectionInterface|array $items): static;

    /**
     * Возвращает коллекцию с элементами которых нет в $items c учётом ключей
     *
     * $collection = collect([
     *     'color' => 'orange',
     *     'type' => 'fruit',
     *     'remain' => 6,
     * ]);
     *
     * $diff = $collection->diffAssoc([
     *     'color' => 'yellow',
     *     'type' => 'fruit',
     *     'remain' => 3,
     *     'used' => 6,
     * ]);
     *
     * $diff->all();
     *
     * ['color' => 'orange', 'remain' => 6]
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function diffAssoc(CollectionInterface|array $items): static;

    /**
     * Возвращает коллекцию с элементами которых нет в $items на основе ключей
     *
     * $collection = collect([
     *     'one' => 10,
     *     'two' => 20,
     *     'three' => 30,
     *     'four' => 40,
     *     'five' => 50,
     * ]);
     *
     * $diff = $collection->diffKeys([
     *     'two' => 2,
     *     'four' => 4,
     *     'six' => 6,
     *     'eight' => 8,
     * ]);
     *
     * $diff->all();
     *
     * ['one' => 10, 'three' => 30, 'five' => 50]
     *
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function diffKeys(CollectionInterface|array $items): static;

    /**
     * Возвращает коллекцию повторяющихся значений
     *
     * $collection = collect(['a', 'b', 'a', 'c', 'b']);
     *
     * $collection->duplicates();
     *
     * [2 => 'a', 4 => 'b']
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
     * @param callable|string|null $callback
     *
     * @return static
     */
    public function duplicatesStrict(callable|string|null $callback = null): static;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function each(callable $callback): static;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function eachSpread(callable $callback): static;

    /**
     * Проверяет все ли элементы проходят тест
     *
     * $collection = collect([1, 2, 3, 4]);
     *
     * $collection->every(function ($value, $key) {
     *     return $value > 2;
     * });
     *
     * false
     *
     * @param callable $callback
     *
     * @return bool
     */
    public function every(callable $callback): bool;

    /**
     * @param CollectionInterface|array $keys
     *
     * @return static
     */
    public function except(CollectionInterface|array $keys): static;

    /**
     * @param callable|null $callback
     *
     * @return static
     */
    public function filter(callable|null $callback = null): static;

    /**
     * @param callable|null $callback
     * @param mixed $default
     *
     * @return mixed
     */
    public function first(callable|null $callback = null, mixed $default = null): mixed;

    /**
     * @param callable|int|string|null $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return mixed
     */
    public function firstWhere(callable|int|string|null $key, Operator|null $operator = null, mixed $value = null): mixed;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function flatMap(callable $callback): static;

    /**
     * @param int|null $depth
     *
     * @return static
     */
    public function flatten(int|null $depth = null): static;

    /**
     * @return static
     */
    public function flip(): static;

    /**
     * @param $key
     *
     * @return static
     */
    public function forget($key): static;

    /**
     * @param int $page
     * @param int $size
     *
     * @return static
     */
    public function forPage(int $page, int $size): static;

    /**
     * Возвращает элемент по заданному ключу. Если ключ не найден возвращает null
     *
     * $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);
     *
     * $value = $collection->get('name');
     *
     * taylor
     *
     * Можно передать значение по умолчанию вторым аргументом
     *
     * $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);
     *
     * $value = $collection->get('age', 34);
     *
     * 34
     *
     * $collection->get('email', function () {
     *    return 'taylor@example.com';
     * });
     *
     * taylor@example.com
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
     * $collection = new Collection([
     *     ['account_id' => 'account-x10', 'product' => 'Chair'],
     *     ['account_id' => 'account-x10', 'product' => 'Bookcase'],
     *     ['account_id' => 'account-x11', 'product' => 'Desk'],
     * ]);
     *
     * $grouped = $collection->groupBy('account_id');
     *
     * $grouped->all();
     *
     * [
     *     'account-x10' => [
     *         ['account_id' => 'account-x10', 'product' => 'Chair'],
     *         ['account_id' => 'account-x10', 'product' => 'Bookcase'],
     *     ],
     *     'account-x11' => [
     *         ['account_id' => 'account-x11', 'product' => 'Desk'],
     *     ],
     * ]
     *
     * =======================================================================
     *
     * $grouped = $collection->groupBy(function ($item, $key) {
     *     return substr($item['account_id'], -3);
     * });
     *
     * $grouped->all();
     *
     * [
     *     'x10' => [
     *         ['account_id' => 'account-x10', 'product' => 'Chair'],
     *         ['account_id' => 'account-x10', 'product' => 'Bookcase'],
     *     ],
     *     'x11' => [
     *         ['account_id' => 'account-x11', 'product' => 'Desk'],
     *     ],
     * ]
     *
     * ========================================================================
     *
     * $data = new Collection([
     *     10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
     *     20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
     *     30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
     *     40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
     * ]);
     *
     * $result = $data->groupBy(['skill', function ($item) {
     *     return $item['roles'];
     * }], $preserveKeys = true);
     *
     * [
     *     1 => [
     *         'Role_1' => [
     *             10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
     *             20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
     *         ],
     *         'Role_2' => [
     *             20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
     *         ],
     *         'Role_3' => [
     *             10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
     *         ],
     *     ],
     *     2 => [
     *         'Role_1' => [
     *             30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
     *         ],
     *         'Role_2' => [
     *             40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
     *         ],
     *     ],
     * ];
     *
     * @param $groupBy
     * @param bool $preserveKeys
     *
     * @return static
     */
    public function groupBy($groupBy, bool $preserveKeys = false): static;

    /**
     * @param callable|int|string $key
     *
     * @return bool
     */
    public function has(callable|int|string $key): bool;

    /**
     * @param string $separator
     * @param callable|int|string|null $key
     *
     * @return string
     */
    public function implode(string $separator, callable|int|string|null $key = null): string;

    /**
     * @param CollectionInterface|array $values
     *
     * @return static
     */
    public function intersect(CollectionInterface|array $values): static;

    /**
     * @param CollectionInterface|array $values
     *
     * @return static
     */
    public function intersectByKeys(CollectionInterface|array $values): static;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return bool
     */
    public function isNotEmpty(): bool;

    /**
     * @param string $glue
     * @param string $finalGlue
     *
     * @return string
     */
    public function join(string $glue, string $finalGlue = ''): string;

    /**
     * @param callable|int|string|null $key
     *
     * @return static
     */
    public function keyBy(callable|int|string|null $key): static;

    /**
     * @return static
     */
    public function keys(): static;

    /**
     * @param callable|null $callback
     *
     * @return mixed
     */
    public function last(callable|null $callback = null): mixed;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function map(callable $callback): static;

    /**
     * @param string $class
     *
     * @return static
     */
    public function mapInto(string $class): static;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function mapSpread(callable $callback): static;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function mapToGroups(callable $callback): static;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function mapWithKeys(callable $callback): static;

    /**
     * @param callable|int|string|null $key
     *
     * @return mixed
     */
    public function max(callable|int|string|null $key = null): mixed;

    /**
     * @param callable|int|string|null $key
     *
     * @return mixed
     */
    public function median(callable|int|string|null $key = null): mixed;

    /**
     * @param iterable $items
     *
     * @return static
     */
    public function merge(iterable $items): static;

    /**
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function mergeRecursive(CollectionInterface|array $items): static;

    /**
     * @param callable|int|string|null $key
     */
    public function min(callable|int|string|null $key = null): mixed;

    /**
     * @param int|string|null $key
     *
     * @psalm-param TKey|null $key
     *
     * @return array|null
     */
    public function mode(int|string|null $key = null): array|null;

    /**
     * @param int $step
     * @param int $offset
     *
     * @return static
     */
    public function nth(int $step, int $offset = 0): static;

    /**
     * @param int[]|string[] $keys
     *
     * @psalm-param TKey[] $keys
     *
     * @return static
     */
    public function only(array $keys): static;

    /**
     * @param int $size
     * @param mixed $value
     *
     * @return static
     */
    public function pad(int $size, mixed $value): static;

    /**
     * @param callable|int|string|null $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return static
     */
    public function partition(callable|int|string|null $key, Operator|null $operator = null, mixed $value = null): static;

    /**
     * @param callable $callback
     *
     * @return mixed
     */
    public function pipe(callable $callback): mixed;

    /**
     * @param string $class
     *
     * @return object
     */
    public function pipeInto(string $class): object;

    /**
     * @param callable|int|string|null $key
     *
     * @return static
     */
    public function pluck(callable|int|string|null $key = null): static;

    /**
     * @return mixed
     * @psalm-return T|null
     */
    public function pop(): mixed;

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function prepend(mixed $value): static;

    /**
     * @param callable|int|string $key
     *
     * @return mixed
     */
    public function pull(callable|int|string $key): mixed;

    /**
     * Добавляет элемент в конец коллекции
     *
     * $collection = new Collection([1, 2, 3, 4]);
     *
     * $collection->push(5);
     *
     * $collection->all();
     *
     * [1, 2, 3, 4, 5]
     *
     * @param mixed $item
     *
     * @psalm-param T $item
     *
     * @return static
     */
    public function push(mixed $item): static;

    /**
     * Устанавливает заданный ключ и значение для в коллекции
     *
     * $collection = new Collection(['product_id' => 1, 'name' => 'Desk']);
     *
     * $collection->put('price', 100);
     *
     * $collection->all();
     *
     * ['product_id' => 1, 'name' => 'Desk', 'price' => 100]
     *
     * @param int|string $key
     * @param mixed $value
     *
     * @psalm-param TKey $key
     * @psalm-param T $value
     *
     * @return static
     */
    public function put(int|string $key, mixed $value): static;

    /**
     *
     * Возвращает случайный элемент коллекции
     *
     * $collection = new Collection([1, 2, 3, 4, 5]);
     *
     * $collection->random();
     *
     * 4 - (retrieved randomly)
     *
     * Вы можете передать число элементов которые вы хотите получить случайным образом
     *
     * $random = $collection->random(3);
     *
     * $random->all();
     *
     * [2, 4, 5] - (retrieved randomly)
     *
     * Если в коллекции меньше элементов чем запрошено метод вызовет исключение InvalidArgumentException
     *
     * @param int $items
     *
     * @return mixed
     * @psalm-return T|T[]
     */
    public function random(int $items = 1): mixed;

    /**
     * Сокращает коллекцию до одного значения передавая результат каждой итерации в следующую итерацию
     *
     * $collection = new Collection([1, 2, 3]);
     *
     * $total = $collection->reduce(function ($carry, $item) {
     *    return $carry + $item;
     * });
     *
     * 6
     *
     * По умолчанию $initial равно null но вы можете передать второй аргумент для установки значения $initial
     *
     * $collection->reduce(function ($carry, $item) {
     *    return $carry + $item;
     * }, 4);
     *
     * 10
     *
     * The reduce method also passes array keys in associative collections to the given callback
     *
     * $collection = new Collection([
     *    'usd' => 1400,
     *    'gbp' => 1200,
     *    'eur' => 1000,
     * ]);
     *
     * $ratio = [
     *    'usd' => 1,
     *    'gbp' => 1.37,
     *    'eur' => 1.22,
     * ];
     *
     * $collection->reduce(function ($carry, $value, $key) use ($ratio) {
     *    return $carry + ($value * $ratio[$key]);
     * });
     *
     * 4264
     *
     * @param callable $callback
     * @param mixed|null $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, mixed $initial = null): mixed;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function reject(callable $callback): static;

    /**
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function replace(CollectionInterface|array $items): static;

    /**
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function replaceRecursive(CollectionInterface|array $items): static;

    /**
     * @return static
     */
    public function reverse(): static;

    /**
     * Ищет значение в коллекции и возвращает его ключ если найдено и false если не найдено
     *
     * $collection = new Collection([2, 4, 6, 8]);
     *
     * $collection->search(4);
     *
     * 1
     *
     * $collection = new Collection([2, 4, 6, 8]);
     *
     * $collection->search('4', $strict = true);
     *
     * false
     *
     * @param mixed $value
     * @param bool $strict
     *
     * @return int|string|bool
     */
    public function search(mixed $value, bool $strict = true): int|string|bool;

    /**
     * @return mixed
     */
    public function shift(): mixed;

    /**
     * @return self
     */
    public function shuffle(): self;

    /**
     * @param int $skip
     *
     * @return static
     */
    public function skip(int $skip): static;

    /**
     * @param callable|int|string $value
     *
     * @return static
     */
    public function skipUntil(callable|int|string $value): static;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function skipWhile(callable $callback): static;

    /**
     * @param int $offset
     * @param int|null $length
     *
     * @return static
     */
    public function slice(int $offset, int|null $length = null): static;

    /**
     * @param callable|int|string|null $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return mixed
     */
    public function sole(callable|int|string|null $key = null, Operator|null $operator = null, mixed $value = null): mixed;

    /**
     * @param mixed $value
     * @param callable|int|string|null $key
     *
     * @return bool
     */
    public function some(mixed $value, callable|int|string|null $key = null): bool;

    /**
     * @param callable|null $callback
     *
     * @return static
     */
    public function sort(callable|null $callback = null): static;

    /**
     * @param callable $callback
     * @param int $options
     * @param bool $descending
     *
     * @return static
     */
    public function sortBy(callable $callback, int $options = SORT_REGULAR, bool $descending = false): static;

    /**
     * @param callable $callback
     * @param int $options
     *
     * @return static
     */
    public function sortByDesc(callable $callback, int $options = SORT_REGULAR): static;

    /**
     * @param int $options
     *
     * @return static
     */
    public function sortDesc(int $options = SORT_REGULAR): static;

    /**
     * @param int $options
     * @param bool $descending
     *
     * @return static
     */
    public function sortKeys(int $options = SORT_REGULAR, bool $descending = false): static;

    /**
     * @param int $options
     *
     * @return static
     */
    public function sortKeysDesc(int $options = SORT_REGULAR): static;

    /**
     * @param int $offset
     * @param int|null $length
     * @param array $replacement
     *
     * @return static
     */
    public function splice(int $offset, ?int $length = null, array $replacement = []): static;

    /**
     * @param int $numberOfGroups
     *
     * @return static
     */
    public function split(int $numberOfGroups): static;

    /**
     * @param int $numberOfGroups
     *
     * @return static
     */
    public function splitIn(int $numberOfGroups): static;

    /**
     * @param callable|int|string|null $key
     *
     * @return mixed
     */
    public function sum(callable|int|string|null $key = null): mixed;

    /**
     * @param int $limit
     *
     * @return static
     */
    public function take(int $limit): static;

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function takeUntil(mixed $value): static;

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function takeWhile(mixed $value): static;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function tap(callable $callback): static;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return string
     */
    public function toJson(): string;

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function transform(callable $callback): static;

    /**
     * @param CollectionInterface|array $items
     *
     * @return static
     */
    public function union(CollectionInterface|array $items): static;

    /**
     * @param int|string|callable|null $key
     * @param bool $strict
     *
     * @return static
     */
    public function unique(int|string|callable|null $key = null, bool $strict = false): static;

    /**
     * @param callable|int|string|null $key
     *
     * @return static
     */
    public function uniqueStrict(callable|int|string|null $key = null): static;

    /**
     * @param bool $condition
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function unless(bool $condition, callable $callback, callable|null $default = null): mixed;

    /**
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function unlessEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function unlessNotEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @return static
     */
    public function values(): static;

    /**
     * @param bool $condition
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function when(bool $condition, callable $callback, callable|null $default = null): mixed;

    /**
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function whenEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @param callable $callback
     * @param callable|null $default
     *
     * @return mixed
     */
    public function whenNotEmpty(callable $callback, callable|null $default = null): mixed;

    /**
     * @param callable|int|string $key
     * @param Operator|null $operator
     * @param mixed $value
     *
     * @return static
     */
    public function where(callable|int|string $key, Operator|null $operator = null, mixed $value = null): static;

    /**
     * @param callable|int|string $key
     * @param mixed $value
     *
     * @return static
     */
    public function whereStrict(callable|int|string $key, mixed $value = null): static;

    /**
     * @param callable|int|string $key
     * @param mixed $from
     * @param mixed $to
     *
     * @return static
     */
    public function whereBetween(callable|int|string $key, mixed $from, mixed $to): static;

    /**
     * @param callable|int|string $key
     * @param CollectionInterface|array $values
     * @param bool $strict
     *
     * @return static
     */
    public function whereIn(callable|int|string $key, CollectionInterface|array $values, bool $strict = false): static;

    /**
     * @param callable|int|string $key
     * @param array $values
     *
     * @return static
     */
    public function whereInStrict(callable|int|string $key, array $values): static;

    /**
     * @param string|string[] $class
     *
     * @return static
     */
    public function whereInstanceOf(array|string $class): static;

    /**
     * @param callable|int|string $key
     * @param mixed $from
     * @param mixed $to
     *
     * @return static
     */
    public function whereNotBetween(callable|int|string $key, mixed $from, mixed $to): static;

    /**
     * @param callable|int|string $key
     * @param CollectionInterface|array $values
     * @param bool $strict
     *
     * @return static
     */
    public function whereNotIn(callable|int|string $key, CollectionInterface|array $values, bool $strict = false): static;

    /**
     * @param callable|int|string $key
     * @param CollectionInterface|array $values
     *
     * @return static
     */
    public function whereNotInStrict(callable|int|string $key, CollectionInterface|array $values): static;

    /**
     * @param callable|int|string $key
     *
     * @return static
     */
    public function whereNotNull(callable|int|string $key): static;

    /**
     * @param callable|int|string $key
     *
     * @return static
     */
    public function whereNull(callable|int|string $key): static;

    /**
     * @param array ...$items
     *
     * @return static
     */
    public function zip(array ...$items): static;
}
