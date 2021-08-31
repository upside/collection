<?php

declare(strict_types=1);

namespace Upside\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Upside\Collection\Collection;

class CollectionTest extends TestCase
{
    /**
     * TODO: Реализовать тест
     */
    public function testMake(): void
    {
        $this->assertSame(1, 1);
    }

    public function test__construct()
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testAll(): void
    {
        $collection = Collection::make([1, 2, 3]);
        $this->assertSame([1, 2, 3], $collection->all());
    }

    /**
     * @depends testMake
     */
    public function testAvg(): void
    {
        $collection = Collection::make([1, 1, 2, 4]);

        $this->assertSame(2.0, $collection->avg());

        $collection = Collection::make([
            ['foo' => 10],
            ['foo' => 10],
            ['foo' => 20],
            ['foo' => 40],
        ]);

        $this->assertSame(20.0, $collection->avg('foo'));
        $this->assertSame(20.0, $collection->avg(fn($item) => $item['foo']));

    }

    /**
     * @depends testMake
     */
    public function testAverage(): void
    {
        $collection = Collection::make([1, 1, 2, 4]);

        $this->assertSame(2.0, $collection->average());

        $collection = Collection::make([
            ['foo' => 10],
            ['foo' => 10],
            ['foo' => 20],
            ['foo' => 40],
        ]);

        $this->assertSame(20.0, $collection->average('foo'));
        $this->assertSame(20.0, $collection->average(fn($item) => $item['foo']));
    }

    /**
     * @depends testMake
     * @depends testToArray
     */
    public function testChunk(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5, 6, 7]);

        $chunks = $collection->chunk(4);

        $this->assertSame(
            [
                [0 => 1, 1 => 2, 2 => 3, 3 => 4],
                [4 => 5, 5 => 6, 6 => 7],
            ],
            $chunks->toArray()
        );

    }

    /**
     * @depends testMake
     * @depends testToArray
     */
    public function testChunkWhile(): void
    {
        $this->markTestIncomplete('Отсутствует реализация');

        $collection = Collection::make(str_split('AABBCCCD'));

        $chunks = $collection->chunkWhile(fn($value, $key, $chunk) => $value === $chunk->last());

        $this->assertSame([['A', 'A'], ['B', 'B'], ['C', 'C', 'C'], ['D']], $chunks->toArray());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCollapse(): void
    {
        $collection = Collection::make([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $collapsed = $collection->collapse();

        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9], $collapsed->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCollect(): void
    {
        $collectionA = Collection::make([1, 2, 3]);

        $collectionB = $collectionA->collect();

        $this->assertSame([1, 2, 3], $collectionB->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCombine(): void
    {
        $collection = Collection::make(['name', 'age']);

        $combined = $collection->combine(['George', 29]);

        $this->assertSame(['name' => 'George', 'age' => 29], $combined->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testConcat(): void
    {
        $this->markTestIncomplete('Реализация только на PHP 8.1 и выше + недоделана работа не с массивом а с коллекцией');

        $collection = Collection::make(['John Doe']);

        $concatenated = $collection->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);

        $this->assertSame(['John Doe', 'Jane Doe', 'Johnny Doe'], $concatenated->all());
    }

    /**
     * @depends testMake
     */
    public function testContains(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);

        $this->assertFalse($collection->contains(value: fn($value) => $value > 5));
        $this->assertTrue($collection->contains(value: 3));
        $this->assertFalse($collection->contains(value: '3', strict: true));
        $this->assertFalse($collection->contains(value: 'New York'));

        $collection = Collection::make([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
        ]);

        $this->assertFalse($collection->contains(value: 'Bookcase', key: 'product'));
        $this->assertTrue($collection->contains(value: 'Chair', key: 'product'));
    }

    /**
     * @depends testMake
     */
    public function testContainsStrict(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);
        $this->assertTrue($collection->containsStrict(3));
        $this->assertFalse($collection->containsStrict('3'));
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCountBy(): void
    {
        $collection = Collection::make([1, 2, 2, 2, 3]);

        $this->assertSame([1 => 1, 2 => 3, 3 => 1], $collection->countBy()->all());

        $collection = Collection::make(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com']);

        $counted = $collection->countBy(fn($email) => substr(strrchr($email, '@'), 1));

        $this->assertSame(['gmail.com' => 2, 'yahoo.com' => 1], $counted->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCrossJoin(): void
    {
        $collection = Collection::make([1, 2]);

        $matrix = $collection->crossJoin(['a', 'b']);

        $this->assertSame(
            [
                [1, 'a'],
                [1, 'b'],
                [2, 'a'],
                [2, 'b'],
            ],
            $matrix->all()
        );

        $collection = Collection::make([1, 2]);

        $matrix = $collection->crossJoin(['a', 'b'], ['I', 'II']);;

        $this->assertSame(
            [
                [1, 'a', 'I'],
                [1, 'a', 'II'],
                [1, 'b', 'I'],
                [1, 'b', 'II'],
                [2, 'a', 'I'],
                [2, 'a', 'II'],
                [2, 'b', 'I'],
                [2, 'b', 'II'],
            ],
            $matrix->all()
        );
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testDiff(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);

        $diff = $collection->diff([2, 4, 6, 8]);

        $this->assertSame([0 => 1, 2 => 3, 4 => 5], $diff->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testDiffAssoc(): void
    {
        $collection = Collection::make([
            'color' => 'orange',
            'type' => 'fruit',
            'remain' => 6,
        ]);

        $diff = $collection->diffAssoc([
            'color' => 'yellow',
            'type' => 'fruit',
            'remain' => 3,
            'used' => 6,
        ]);

        $this->assertSame(['color' => 'orange', 'remain' => 6], $diff->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testDiffKeys(): void
    {
        $collection = Collection::make([
            'one' => 10,
            'two' => 20,
            'three' => 30,
            'four' => 40,
            'five' => 50,
        ]);

        $diff = $collection->diffKeys([
            'two' => 2,
            'four' => 4,
            'six' => 6,
            'eight' => 8,
        ]);

        $this->assertSame(['one' => 10, 'three' => 30, 'five' => 50], $diff->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testDuplicates(): void
    {
        $collection = Collection::make(['a', 'b', 'a', 'c', 'b']);

        $this->assertSame([2 => 'a', 4 => 'b'], $collection->duplicates()->all());

        $employees = Collection::make([
            ['email' => 'abigail@example.com', 'position' => 'Developer'],
            ['email' => 'james@example.com', 'position' => 'Designer'],
            ['email' => 'victoria@example.com', 'position' => 'Developer'],
        ]);

        $employees->duplicates('position');

        $this->assertSame([2 => 'Developer'], $employees->duplicates('position')->all());
    }

    public function testDuplicatesStrict(): void
    {
        $this->markTestIncomplete();
    }

    public function testEach(): void
    {
        $this->markTestIncomplete();
    }

    public function testEachSpread(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testEvery(): void
    {
        $this->assertFalse(Collection::make([1, 2, 3, 4])->every(fn($value, $key) => $value > 2));
        $this->assertTrue(Collection::make([])->every(fn($value, $key) => $value > 2));
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testExcept(): void
    {
        $collection = Collection::make(['product_id' => 1, 'price' => 100, 'discount' => false]);

        $filtered = $collection->except(['price', 'discount']);

        $this->assertSame(['product_id' => 1], $filtered->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testFilter(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $this->assertSame([2 => 3, 3 => 4], $collection->filter(fn($value, $key) => $value > 2)->all());

        $collection = Collection::make([1, 2, 3, null, false, '', 0, []]);

        $this->assertSame([1, 2, 3], $collection->filter()->all());
    }

    /**
     * @depends testMake
     */
    public function testFirst(): void
    {
        $result = Collection::make([1, 2, 3, 4])->first(fn($value, $key) => $value > 2);

        $this->assertSame(3, $result);
        $this->assertSame(1, Collection::make([1, 2, 3, 4])->first());
    }

    public function testFirstWhere(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testFlatMap(): void
    {
        $collection = Collection::make([
            ['name' => 'Sally'],
            ['school' => 'Arkansas'],
            ['age' => 28],
        ]);

        $flattened = $collection->flatMap(fn($values) => array_map('strtoupper', $values));

        $this->assertSame(['name' => 'SALLY', 'school' => 'ARKANSAS', 'age' => '28'], $flattened->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testFlatten(): void
    {
        $collection = Collection::make([
            'name' => 'taylor',
            'languages' => [
                'php', 'javascript',
            ],
        ]);

        $flattened = $collection->flatten();

        $this->assertSame(['taylor', 'php', 'javascript'], $flattened->all());

        $collection = Collection::make([
            'Apple' => [
                [
                    'name' => 'iPhone 6S',
                    'brand' => 'Apple',
                ],
            ],
            'Samsung' => [
                [
                    'name' => 'Galaxy S7',
                    'brand' => 'Samsung',
                ],
            ],
        ]);

        $products = $collection->flatten(1);

        $this->assertSame(
            [
                ['name' => 'iPhone 6S', 'brand' => 'Apple'],
                ['name' => 'Galaxy S7', 'brand' => 'Samsung'],
            ],
            $products->values()->all()
        );
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testFlip(): void
    {
        $collection = Collection::make(['name' => 'taylor', 'framework' => 'laravel']);

        $this->assertSame(['taylor' => 'name', 'laravel' => 'framework'], $collection->flip()->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testForget(): void
    {
        $collection = Collection::make(['name' => 'taylor', 'framework' => 'laravel']);

        $collection->forget('name');

        $this->assertSame(['framework' => 'laravel'], $collection->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testForPage(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $chunk = $collection->forPage(2, 3);

        $this->assertSame([4, 5, 6], $chunk->all());
    }

    /**
     * @depends testMake
     */
    public function testGet(): void
    {
        $collection = Collection::make(['name' => 'taylor', 'framework' => 'laravel']);

        $this->assertSame('taylor', $collection->get('name'));
        $this->assertSame(34, $collection->get('age', 34));
        $this->assertSame('taylor@example.com', $collection->get('email', fn() => 'taylor@example.com'));
    }

    /**
     * @depends testMake
     * @depends testToArray
     */
    public function testGroupBy(): void
    {
        $collection = Collection::make([
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ]);

        $grouped = $collection->groupBy('account_id');

        $this->assertEquals(
            [
                'account-x10' => [
                    ['account_id' => 'account-x10', 'product' => 'Chair'],
                    ['account_id' => 'account-x10', 'product' => 'Bookcase'],
                ],
                'account-x11' => [
                    ['account_id' => 'account-x11', 'product' => 'Desk'],
                ],
            ],
            $grouped->toArray()
        );

        $grouped = $collection->groupBy(fn($item, $key) => substr($item['account_id'], -3));

        $this->assertEquals(
            [
                'x10' => [
                    ['account_id' => 'account-x10', 'product' => 'Chair'],
                    ['account_id' => 'account-x10', 'product' => 'Bookcase'],
                ],
                'x11' => [
                    ['account_id' => 'account-x11', 'product' => 'Desk'],
                ],
            ],
            $grouped->toArray()
        );

        $data = new Collection([
            10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
            20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
            30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
            40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
        ]);

        $result = $data->groupBy(['skill', fn($item) => $item['roles']], true);

        $this->assertEquals(
            [
                1 => [
                    'Role_1' => [
                        10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
                        20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
                    ],
                    'Role_2' => [
                        20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
                    ],
                    'Role_3' => [
                        10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
                    ],
                ],
                2 => [
                    'Role_1' => [
                        30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
                    ],
                    'Role_2' => [
                        40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
                    ],
                ],
            ],
            $result->toArray()
        );
    }

    /**
     * @depends testMake
     */
    public function testHas(): void
    {
        $collection = Collection::make(['account_id' => 1, 'product' => 'Desk', 'amount' => 5]);

        $this->assertTrue($collection->has('product'));
        $this->assertTrue($collection->has(['product', 'amount']));
        $this->assertFalse($collection->has(['amount', 'price']));
    }

    /**
     * @depends testMake
     */
    public function testImplode(): void
    {
        $collection = Collection::make([
            ['account_id' => 1, 'product' => 'Desk'],
            ['account_id' => 2, 'product' => 'Chair'],
        ]);

        $this->assertSame('Desk, Chair', $collection->implode(', ', 'product'));
        $this->assertSame('1-2-3-4-5', Collection::make([1, 2, 3, 4, 5])->implode('-'));
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testIntersect(): void
    {
        $collection = Collection::make(['Desk', 'Sofa', 'Chair']);

        $intersect = $collection->intersect(['Desk', 'Chair', 'Bookcase']);

        $this->assertSame([0 => 'Desk', 2 => 'Chair'], $intersect->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testIntersectByKeys(): void
    {
        $collection = Collection::make([
            'serial' => 'UX301', 'type' => 'screen', 'year' => 2009,
        ]);

        $intersect = $collection->intersectByKeys([
            'reference' => 'UX404', 'type' => 'tab', 'year' => 2011,
        ]);

        $this->assertSame(['type' => 'screen', 'year' => 2009], $intersect->all());
    }

    /**
     * @depends testMake
     */
    public function testIsEmpty(): void
    {
        $this->assertTrue(Collection::make([])->isEmpty());
        $this->assertFalse(Collection::make([1])->isEmpty());
    }

    /**
     * @depends testMake
     */
    public function testIsNotEmpty(): void
    {
        $this->assertFalse(Collection::make([])->isNotEmpty());
        $this->assertTrue(Collection::make([1])->isNotEmpty());
    }

    /**
     * @depends testMake
     */
    public function testJoin(): void
    {
        $this->assertSame('a, b, c', Collection::make(['a', 'b', 'c'])->join(', '));
        $this->assertSame('a, b, and c', Collection::make(['a', 'b', 'c'])->join(', ', ', and '));
        $this->assertSame('a and b', Collection::make(['a', 'b'])->join(', ', ' and '));
        $this->assertSame('a', Collection::make(['a'])->join(', ', ' and '));
        $this->assertSame('', Collection::make([])->join(', ', ' and '));
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testKeyBy(): void
    {
        $collection = Collection::make([
            ['product_id' => 'prod-100', 'name' => 'Desk'],
            ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $keyed = $collection->keyBy('product_id');

        $this->assertSame(
            [
                'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
                'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
            ],
            $keyed->all()
        );

        $keyed = $collection->keyBy(fn($item) => strtoupper($item['product_id']));

        $this->assertSame(
            [
                'PROD-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
                'PROD-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
            ],
            $keyed->all()
        );

    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testKeys(): void
    {
        $collection = Collection::make([
            'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
            'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $keys = $collection->keys();
        $this->assertSame(['prod-100', 'prod-200'], $keys->all());
    }

    /**
     * @depends testMake
     */
    public function testLast(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $this->assertSame(2, $collection->last(fn($value, $key) => $value < 3));
        $this->assertSame(4, $collection->last());
    }

    /**
     * @depends testMake
     */
    public function testMap(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);

        $this->assertSame([2, 4, 6, 8, 10], $collection->map(fn($item, $key) => $item * 2)->all());
    }

    public function testMapInto(): void
    {

        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testMapSpread(): void
    {
        $collection = Collection::make([0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $chunks = $collection->chunk(2);

        $sequence = $chunks->mapSpread(function ($even, $odd) {
            return $even + $odd;
        });

        $this->assertSame([1, 5, 9, 13, 17], $sequence->all());
    }

    /**
     * @depends testMake
     * @depends testToArray
     * @depends testGet
     */
    public function testMapToGroups(): void
    {
        $collection = Collection::make([
            [
                'name' => 'John Doe',
                'department' => 'Sales',
            ],
            [
                'name' => 'Jane Doe',
                'department' => 'Sales',
            ],
            [
                'name' => 'Johnny Doe',
                'department' => 'Marketing',
            ],
        ]);

        $grouped = $collection->mapToGroups(function ($item, $key) {
            return [$item['department'] => $item['name']];
        });

        $this->assertSame(
            [
                'Sales' => ['John Doe', 'Jane Doe'],
                'Marketing' => ['Johnny Doe'],
            ],
            $grouped->toArray()
        );

        $this->assertSame(['John Doe', 'Jane Doe'], $grouped->get('Sales')->toArray());
    }

    public function testMapToDictionary(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testMapWithKeys(): void
    {
        $collection = Collection::make([
            [
                'name' => 'John',
                'department' => 'Sales',
                'email' => 'john@example.com',
            ],
            [
                'name' => 'Jane',
                'department' => 'Marketing',
                'email' => 'jane@example.com',
            ],
        ]);

        $keyed = $collection->mapWithKeys(function ($item, $key) {
            return [$item['email'] => $item['name']];
        });

        $this->assertSame(
            [
                'john@example.com' => 'John',
                'jane@example.com' => 'Jane',
            ],
            $keyed->all()
        );
    }

    /**
     * @depends testMake
     */
    public function testMax(): void
    {
        $collection = Collection::make([
            ['foo' => 10],
            ['foo' => 20],
        ]);

        $this->assertSame(20, $collection->max('foo'));
        $this->assertSame(5, Collection::make([1, 2, 3, 4, 5])->max());
    }

    /**
     * @depends testMake
     */
    public function testMedian(): void
    {
        $collection = Collection::make([
            ['foo' => 10],
            ['foo' => 10],
            ['foo' => 20],
            ['foo' => 40],
        ]);

        $this->assertSame(15.0, $collection->median('foo'));
        $this->assertSame(1.5, Collection::make([1, 1, 2, 4])->median());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testMerge(): void
    {
        $collection = Collection::make(['product_id' => 1, 'price' => 100]);

        $merged = $collection->merge(['price' => 200, 'discount' => false]);

        $this->assertSame(['product_id' => 1, 'price' => 200, 'discount' => false], $merged->all());

        $collection = Collection::make(['Desk', 'Chair']);

        $merged = $collection->merge(['Bookcase', 'Door']);

        $this->assertSame(['Desk', 'Chair', 'Bookcase', 'Door'], $merged->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testMergeRecursive(): void
    {
        $collection = Collection::make(['product_id' => 1, 'price' => 100]);

        $merged = $collection->mergeRecursive([
            'product_id' => 2,
            'price' => 200,
            'discount' => false,
        ]);

        $this->assertSame(['product_id' => [1, 2], 'price' => [100, 200], 'discount' => false], $merged->all());
    }

    /**
     * @depends testMake
     */
    public function testMin(): void
    {
        $this->assertSame(10, Collection::make([['foo' => 10], ['foo' => 20]])->min('foo'));
        $this->assertSame(1, Collection::make([1, 2, 3, 4, 5])->min());
    }

    /**
     * @depends testMake
     */
    public function testMode(): void
    {
        $mode = Collection::make([
            ['foo' => 10],
            ['foo' => 10],
            ['foo' => 20],
            ['foo' => 40],
        ])->mode('foo');

        $this->assertSame([10], $mode);
        $this->assertSame([1], Collection::make([1, 1, 2, 4])->mode());
        $this->assertSame([1, 2], Collection::make([1, 1, 2, 2])->mode());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testNth(): void
    {
        $collection = Collection::make(['a', 'b', 'c', 'd', 'e', 'f']);
        $this->assertSame(['a', 'e'], $collection->nth(4)->all());
        $this->assertSame(['b', 'f'], $collection->nth(4, 1)->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testOnly(): void
    {
        $collection = Collection::make([
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 100,
            'discount' => false,
        ]);

        $filtered = $collection->only(['product_id', 'name']);

        $this->assertSame(['product_id' => 1, 'name' => 'Desk'], $filtered->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testPad(): void
    {
        $collection = Collection::make(['A', 'B', 'C']);

        $filtered = $collection->pad(5, 0);
        $this->assertSame(['A', 'B', 'C', 0, 0], $filtered->all());

        $filtered = $collection->pad(-5, 0);
        $this->assertSame([0, 0, 'A', 'B', 'C'], $filtered->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testPartition(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5, 6]);

        [$underThree, $equalOrAboveThree] = $collection->partition(fn($i) => $i < 3);

        $underThree->all();

        $this->assertSame([0 => 1, 1 => 2], $underThree->all());
        $this->assertSame([2 => 3, 3 => 4, 4 => 5, 5 => 6], $equalOrAboveThree->all());
    }

    /**
     * @depends testMake
     * @depends testSum
     */
    public function testPipe(): void
    {
        $collection = Collection::make([1, 2, 3]);

        $this->assertSame(6, $collection->pipe(fn($collection) => $collection->sum()));
    }

    public function testPipeInto(): void
    {
        $this->markTestIncomplete();
    }

    public function testPluck(): void
    {
        $this->markTestIncomplete();
    }

    public function testPop(): void
    {
        $this->markTestIncomplete();
    }

    public function testPrepend(): void
    {
        $this->markTestIncomplete();

    }

    public function testPull(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testPush(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $collection->push(5);

        $this->assertSame([1, 2, 3, 4, 5], $collection->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testPut(): void
    {
        $collection = Collection::make(['product_id' => 1, 'name' => 'Desk']);

        $collection->put('price', 100);

        $this->assertSame(['product_id' => 1, 'name' => 'Desk', 'price' => 100], $collection->all());
    }

    public function testRandom(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testReduce(): void
    {
        $collection = Collection::make([1, 2, 3]);

        $total = $collection->reduce(fn($carry, $item) => $carry + $item);

        $this->assertSame(6, $total);

        $total = $collection->reduce(fn($carry, $item) => $carry + $item, 4);

        $this->assertSame(10, $total);

        $collection = Collection::make([
            'usd' => 1400,
            'gbp' => 1200,
            'eur' => 1000,
        ]);

        $ratio = [
            'usd' => 1,
            'gbp' => 1.37,
            'eur' => 1.22,
        ];

        $total = $collection->reduce(fn($carry, $value, $key) => $carry + ($value * $ratio[$key]));

        $this->assertSame(4264.0, $total);
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testReject(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $filtered = $collection->reject(fn($value, $key) => $value > 2);

        $this->assertSame([1, 2], $filtered->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testReplace(): void
    {
        $collection = Collection::make(['Taylor', 'Abigail', 'James']);

        $replaced = $collection->replace([1 => 'Victoria', 3 => 'Finn']);

        $this->assertSame(['Taylor', 'Victoria', 'James', 'Finn'], $replaced->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testReplaceRecursive(): void
    {
        $collection = Collection::make([
            'Taylor',
            'Abigail',
            [
                'James',
                'Victoria',
                'Finn',
            ],
        ]);

        $replaced = $collection->replaceRecursive([
            'Charlie',
            2 => [1 => 'King'],
        ]);

        $this->assertSame(['Charlie', 'Abigail', ['James', 'King', 'Finn']], $replaced->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testReverse(): void
    {
        $collection = Collection::make(['a', 'b', 'c', 'd', 'e']);

        $reversed = $collection->reverse();

        $this->assertSame(
            [
                4 => 'e',
                3 => 'd',
                2 => 'c',
                1 => 'b',
                0 => 'a',
            ],
            $reversed->all()
        );
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testSearch(): void
    {
        $collection = Collection::make([2, 4, 6, 8]);

        $this->assertSame(1, $collection->search(4));
        $this->assertFalse(Collection::make([2, 4, 6, 8])->search('4', true));
        $this->assertSame(2, Collection::make([2, 4, 6, 8])->search(fn($item, $key) => $item > 5));
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testShift(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);

        $this->assertSame(1, $collection->shift());
        $this->assertSame([2, 3, 4, 5], $collection->all());

        $collection = Collection::make([1, 2, 3, 4, 5]);

        $shifted = $collection->shift(3);

        $this->assertSame([1, 2, 3], $shifted->all());
        $this->assertSame([4, 5], $collection->all());
    }

    public function testShuffle(): void
    {
        $this->markTestIncomplete();
    }

    public function testSliding(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testSkip(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $collection = $collection->skip(4);

        $this->assertSame([5, 6, 7, 8, 9, 10], $collection->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testSkipUntil(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $subset = $collection->skipUntil(fn($item) => $item >= 3);

        $this->assertSame([3, 4], $subset->all());

        $collection = Collection::make([1, 2, 3, 4]);

        $subset = $collection->skipUntil(3);

        $this->assertSame([3, 4], $subset->all());

    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testSkipWhile(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $subset = $collection->skipWhile(fn($item) => $item <= 3);

        $this->assertSame([4], $subset->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testSlice(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $slice = $collection->slice(4);

        $this->assertSame([5, 6, 7, 8, 9, 10], $slice->all());

        $slice = $collection->slice(4, 2);

        $this->assertSame([5, 6], $slice->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testSole(): void
    {
        $this->assertSame(2, Collection::make([1, 2, 3, 4])->sole(value: fn($value, $key) => $value === 2));

        $collection = Collection::make([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
        ]);
        $this->assertSame(['product' => 'Chair', 'price' => 100], $collection->sole(key: 'product', value: 'Chair'));

        $collection = Collection::make([
            ['product' => 'Desk', 'price' => 200],
        ]);

        $this->assertSame(['product' => 'Desk', 'price' => 200], $collection->sole());
    }

    public function testSome(): void
    {
        $this->markTestIncomplete();
    }

    public function testSort(): void
    {
        $this->markTestIncomplete();
    }

    public function testSortBy(): void
    {
        $this->markTestIncomplete();
    }

    public function testSortByDesc(): void
    {
        $this->markTestIncomplete();
    }

    public function testSortDesc(): void
    {
        $this->markTestIncomplete();
    }

    public function testSortKeys(): void
    {
        $this->markTestIncomplete();
    }

    public function testSortKeysDesc(): void
    {
        $this->markTestIncomplete();
    }

    public function testSplice(): void
    {
        $this->markTestIncomplete();
    }

    public function testSplit(): void
    {
        $this->markTestIncomplete();
    }

    public function testSplitIn(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testSum(): void
    {
        $this->assertSame(15, Collection::make([1, 2, 3, 4, 5])->sum());

        $collection = Collection::make([
            ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
            ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
        ]);

        $this->assertSame(1272, $collection->sum('pages'));

        $collection = Collection::make([
            ['name' => 'Chair', 'colors' => ['Black']],
            ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
            ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
        ]);

        $this->assertSame(6, $collection->sum(fn($product) => count($product['colors'])));
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testTake(): void
    {
        $collection = Collection::make([0, 1, 2, 3, 4, 5]);

        $chunk = $collection->take(3);

        $this->assertSame([0, 1, 2], $chunk->all());

        $collection = Collection::make([0, 1, 2, 3, 4, 5]);

        $chunk = $collection->take(-2);

        $this->assertSame([4, 5], $chunk->all());

    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testTakeUntil(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $subset = $collection->takeUntil(fn($item) => $item >= 3);

        $this->assertSame([1, 2], $subset->all());

        $collection = Collection::make([1, 2, 3, 4]);

        $subset = $collection->takeUntil(3);

        $this->assertSame([1, 2], $subset->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testTakeWhile(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $subset = $collection->takeWhile(fn($item) => $item < 3);

        $this->assertSame([1, 2], $subset->all());
    }

    public function testTap(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testToArray(): void
    {
        $collection = Collection::make(['name' => 'Desk', 'price' => 200]);

        $this->assertSame(['name' => 'Desk', 'price' => 200], $collection->toArray());
    }

    /**
     * @depends testMake
     */
    public function testToJson(): void
    {
        $collection = Collection::make(['name' => 'Desk', 'price' => 200]);

        $this->assertSame('{"name":"Desk","price":200}', $collection->toJson());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testTransform(): void
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);
        $collection->transform(fn($item, $key) => $item * 2);
        $this->assertSame([2, 4, 6, 8, 10], $collection->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testUnion(): void
    {
        $collection = Collection::make([1 => ['a'], 2 => ['b']]);

        $union = $collection->union([3 => ['c'], 1 => ['d']]);

        $this->assertSame([1 => ['a'], 2 => ['b'], 3 => ['c']], $union->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     * @depends testValues
     */
    public function testUnique(): void
    {
        $collection = Collection::make([1, 1, 2, 2, 3, 4, 2]);

        $unique = $collection->unique();

        $this->assertSame([1, 2, 3, 4], $unique->values()->all());

        $collection = Collection::make([
            ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
            ['name' => 'iPhone 5', 'brand' => 'Apple', 'type' => 'phone'],
            ['name' => 'Apple Watch', 'brand' => 'Apple', 'type' => 'watch'],
            ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
            ['name' => 'Galaxy Gear', 'brand' => 'Samsung', 'type' => 'watch'],
        ]);

        $unique = $collection->unique('brand');

        $this->assertSame(
            [
                ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
                ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
            ],
            $unique->values()->all()
        );

        $unique = $collection->unique(fn($item) => $item['brand'] . $item['type']);

        $this->assertSame(
            [
                ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
                ['name' => 'Apple Watch', 'brand' => 'Apple', 'type' => 'watch'],
                ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
                ['name' => 'Galaxy Gear', 'brand' => 'Samsung', 'type' => 'watch'],
            ],
            $unique->values()->all()
        );
    }

    public function testUniqueStrict(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     * @depends testAll
     * @depends testPush
     */
    public function testUnless(): void
    {
        $collection = Collection::make([1, 2, 3]);

        $collection->unless(true, fn(Collection $collection) => $collection->push(4));

        $collection->unless(false, fn(Collection $collection) => $collection->push(5));

        $this->assertSame([1, 2, 3, 5], $collection->all());
    }

    public function testUnlessEmpty(): void
    {
        $this->markTestIncomplete();
    }

    public function testUnlessNotEmpty(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testValues(): void
    {
        $collection = Collection::make([
            10 => ['product' => 'Desk', 'price' => 200],
            11 => ['product' => 'Desk', 'price' => 200],
        ]);

        $values = $collection->values();

        $this->assertSame(
            [
                0 => ['product' => 'Desk', 'price' => 200],
                1 => ['product' => 'Desk', 'price' => 200],
            ],
            $values->all()
        );
    }

    public function testWhen(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhenEmpty(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhenNotEmpty(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhere(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereStrict(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereBetween(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereIn(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereInStrict(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereInstanceOf(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotBetween(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotIn(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotInStrict(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotNull(): void
    {
        $this->markTestIncomplete();
    }

    public function testWhereNull(): void
    {
        $this->markTestIncomplete();
    }

    public function testZip(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testCount(): void
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $this->assertSame(4, $collection->count());
    }

    public function testOffsetSet(): void
    {
        $this->markTestIncomplete();
    }

    public function testOffsetExists(): void
    {
        $this->markTestIncomplete();
    }

    public function testOffsetGet(): void
    {
        $this->markTestIncomplete();
    }

    public function testOffsetUnset(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetIterator(): void
    {
        $this->markTestIncomplete();
    }
}
