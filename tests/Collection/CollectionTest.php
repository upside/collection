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

        $this->assertFalse($collection->contains(fn($value) => $value > 5));
        $this->assertTrue($collection->contains(3));
        $this->assertFalse($collection->contains('3', null, true));
        $this->assertFalse($collection->contains('New York'));

        $collection = Collection::make([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
        ]);

        $this->assertFalse($collection->contains('Bookcase', 'product'));
        $this->assertTrue($collection->contains('Chair', 'product'));
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

    public function testMapSpread(): void
    {
        $this->markTestIncomplete();
    }

    public function testMapToGroups(): void
    {
        $this->markTestIncomplete();
    }

    public function testMapToDictionary(): void
    {
        $this->markTestIncomplete();
    }

    public function testMapWithKeys(): void
    {
        $this->markTestIncomplete();
    }

    public function testMax(): void
    {
        $this->markTestIncomplete();
    }

    public function testMedian(): void
    {
        $this->markTestIncomplete();
    }

    public function testMerge(): void
    {
        $this->markTestIncomplete();
    }

    public function testMergeRecursive(): void
    {
        $this->markTestIncomplete();
    }

    public function testMin(): void
    {
        $this->markTestIncomplete();
    }

    public function testMode(): void
    {
        $this->markTestIncomplete();
    }

    public function testNth(): void
    {
        $this->markTestIncomplete();
    }

    public function testOnly(): void
    {
        $this->markTestIncomplete();
    }

    public function testPad(): void
    {
        $this->markTestIncomplete();
    }

    public function testPartition(): void
    {
        $this->markTestIncomplete();
    }

    public function testPipe(): void
    {
        $this->markTestIncomplete();
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

    public function testPush(): void
    {
        $this->markTestIncomplete();

    }

    public function testPut(): void
    {
        $this->markTestIncomplete();
    }

    public function testRandom(): void
    {
        $this->markTestIncomplete();
    }

    public function testReduce(): void
    {
        $this->markTestIncomplete();
    }

    public function testReject(): void
    {
        $this->markTestIncomplete();
    }

    public function testReplace(): void
    {
        $this->markTestIncomplete();
    }

    public function testReplaceRecursive(): void
    {
        $this->markTestIncomplete();
    }

    public function testReverse(): void
    {
        $this->markTestIncomplete();
    }

    public function testSearch(): void
    {
        $this->markTestIncomplete();
    }

    public function testShift(): void
    {
        $this->markTestIncomplete();
    }

    public function testShuffle(): void
    {
        $this->markTestIncomplete();
    }

    public function testSkip(): void
    {
        $this->markTestIncomplete();
    }

    public function testSkipUntil(): void
    {
        $this->markTestIncomplete();
    }

    public function testSkipWhile(): void
    {
        $this->markTestIncomplete();
    }

    public function testSlice(): void
    {
        $this->markTestIncomplete();
    }

    public function testSole(): void
    {
        $this->markTestIncomplete();
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

    public function testSum(): void
    {
        $this->markTestIncomplete();
    }

    public function testTake(): void
    {
        $this->markTestIncomplete();
    }

    public function testTakeUntil(): void
    {
        $this->markTestIncomplete();
    }

    public function testTakeWhile(): void
    {
        $this->markTestIncomplete();
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

    public function testToJson(): void
    {
        $this->markTestIncomplete();
    }

    public function testTransform(): void
    {
        $this->markTestIncomplete();
    }

    public function testUnion(): void
    {
        $this->markTestIncomplete();
    }

    public function testUnique(): void
    {
        $this->markTestIncomplete();
    }

    public function testUniqueStrict(): void
    {
        $this->markTestIncomplete();
    }

    public function testUnless(): void
    {
        $this->markTestIncomplete();
    }

    public function testUnlessEmpty(): void
    {
        $this->markTestIncomplete();
    }

    public function testUnlessNotEmpty(): void
    {
        $this->markTestIncomplete();
    }

    public function testValues(): void
    {
        $this->markTestIncomplete();
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
