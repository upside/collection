<?php

namespace Upside\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Upside\Collection\Collection;

class CollectionTest extends TestCase
{
    /**
     * @dataProvider allDataProvider
     */
    public function testAll(Collection $collection, array $actual): void
    {
        self::assertEquals($collection->all(), $actual);
    }

    /**
     * @dataProvider avgDataProvider
     */
    public function testAvg(Collection $collection, float $actual, string|null $key): void
    {
        self::assertEquals($actual, $collection->avg($key));
    }

    /**
     * @dataProvider avgDataProvider
     */
    public function testAverage(Collection $collection, float $actual, string|null $key): void
    {
        self::assertEquals($actual, $collection->average($key));
    }

    /**
     * @depends testToArray
     */
    public function testChunk()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7]);
        $chunks = $collection->chunk(4);

        self::assertEquals([[1, 2, 3, 4], [5, 6, 7]], $chunks->toArray());
    }

    public function testChunkWhile()
    {

    }

    public function testCollapse()
    {
        $collection = new Collection([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $collapsed = $collection->collapse();

        self::assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $collapsed->all());
    }

    /**
     * @depends testAll
     */
    public function testCombine()
    {
        $collection = new Collection(['name', 'age']);
        $combined = $collection->combine(['George', 29]);
        self::assertEquals(['name' => 'George', 'age' => 29], $combined->all());
    }

    /**
     * @depends testAll
     */
    public function testCollect(): void
    {
        $collectionA = new Collection([1, 2, 3]);

        $collectionB = $collectionA->collect();

        self::assertEquals([1, 2, 3], $collectionA->all());
        self::assertEquals([1, 2, 3], $collectionB->all());
        self::assertEquals($collectionA->all(), $collectionB->all());
    }

    /**
     * @depends testAll
     */
    public function testConcat()
    {
        $collection = new Collection(['John Doe']);
        $concatenated = $collection->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);
        self::assertEquals(['John Doe'], $collection->all());
        self::assertEquals(['John Doe', 'Jane Doe', 'Johnny Doe'], $concatenated->all());
    }

    public function testContains()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertFalse($collection->contains(function ($value, $key) {
            return $value > 5;
        }));

        $collection = new Collection(['name' => 'Desk', 'price' => 100]);
        self::assertTrue($collection->contains('Desk'));
        self::assertFalse($collection->contains('New York'));

        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
        ]);

        self::assertFalse($collection->contains('product', 'Bookcase'));
    }

    public function testContainsStrict(): void
    {

    }

    public function testCount()
    {
        $collection = new Collection([1, 2, 3, 4]);
        self::assertEquals(4, $collection->count());
    }

    /**
     * @depends testToArray
     */
    public function testCountBy()
    {
        $collection = new Collection([1, 2, 2, 2, 3]);
        $counted = $collection->countBy();
        self::assertEquals([1 => 1, 2 => 3, 3 => 1], $counted->toArray());

        $collection = new Collection(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com']);
        $counted = $collection->countBy(function ($email) {
            return substr(strrchr($email, "@"), 1);
        });
        self::assertEquals(['gmail.com' => 2, 'yahoo.com' => 1], $counted->toArray());
    }

    public function testCrossJoin(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testDiff(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $diff = $collection->diff([2, 4, 6, 8]);
        self::assertEquals([1, 3, 5], $diff->toArray());
    }

    public function testDiffAssoc(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testDiffKeys(): void
    {
        $collection = new Collection([
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

        self::assertEquals(['one' => 10, 'three' => 30, 'five' => 50], $diff->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testDuplicates()
    {
        $collection = new Collection(['a', 'b', 'a', 'c', 'b']);
        $duplicates = $collection->duplicates();
        self::assertEquals([2 => 'a', 4 => 'b'], $duplicates->toArray());

        $collection = new Collection([
            ['email' => 'abigail@example.com', 'position' => 'Developer'],
            ['email' => 'james@example.com', 'position' => 'Designer'],
            ['email' => 'victoria@example.com', 'position' => 'Developer'],
        ]);

        $duplicates = $collection->duplicates('position');

        self::assertEquals([2 => 'Developer'], $duplicates->toArray());
    }

    public function testDuplicatesStrict(): void
    {

    }

    public function testEach(): void
    {
        $test1 = 0;
        $test2 = 0;
        $collection = new Collection([1, 2, 3]);

        $collection->each(function ($item, $key) use (&$test1) {
            $test1 += $item;
            return true;
        });

        $collection->each(function ($item, $key) use (&$test2) {
            if ($key === 2) {
                return false;
            }
            $test2 += $item;
            return true;
        });

        self::assertEquals(6, $test1);
        self::assertEquals(3, $test2);
    }

    public function testEachSpread(): void
    {

    }

    public function testEvery(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        self::assertFalse($collection->every(function ($value, $key) {
            return $value > 2;
        }));

        self::assertTrue($collection->every(function ($value, $key) {
            return $value > 0;
        }));
    }

    /**
     * @depends testToArray
     */
    public function testExcept(): void
    {
        $collection = new Collection(['product_id' => 1, 'price' => 100, 'discount' => false]);
        $filtered = $collection->except(['price', 'discount']);
        self::assertEquals(['product_id' => 1], $filtered->toArray());
    }

    public function testFilter(): void
    {

    }

    public function testFirst(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        $collection->first(function ($value, $key) {
            return $value > 2;
        });

        self::assertEquals(3, $collection->first(function ($value, $key) {
            return $value > 2;
        }));
        self::assertEquals(1, $collection->first());
    }

    public function testFirstWhere(): void
    {
        $collection = new Collection([
            ['name' => 'Regena', 'age' => null],
            ['name' => 'Linda', 'age' => 14],
            ['name' => 'Diego', 'age' => 23],
            ['name' => 'Linda', 'age' => 84],
        ]);

        self::assertEquals(['name' => 'Linda', 'age' => 14], $collection->firstWhere('name', 'Linda'));
        self::assertEquals(['name' => 'Diego', 'age' => 23], $collection->firstWhere('age', 18, '>='));
        self::assertEquals(['name' => 'Linda', 'age' => 14], $collection->firstWhere('age'));
    }

    public function testFlatMap(): void
    {

    }

    public function testFlatten(): void
    {

    }

    public function testFlip(): void
    {

    }

    public function testForget(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testForPage(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $chunk = $collection->forPage(2, 3);
        self::assertEquals([4, 5, 6], $chunk->toArray());
    }

    public function testGet(): void
    {
        $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);
        self::assertEquals('taylor', $collection->get('name'));
        self::assertEquals('taylor', $collection->get('test', 'taylor'));
        self::assertEquals(34, $collection->get('age', 34));
        self::assertNull($collection->get('test'));
    }

    /**
     * @depends testToArray
     */
    public function testGroupBy(): void
    {
        $collection = new Collection([
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ]);

        $grouped = $collection->groupBy('account_id');

        self::assertEquals(
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

        $grouped = $collection->groupBy(function ($item, $key) {
            return substr($item['account_id'], -3);
        });

        self::assertEquals(
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

        $result = $data->groupBy(['skill', function ($item) {
            return $item['roles'];
        }], $preserveKeys = true);

        self::assertEquals(
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

    public function testHas(): void
    {
        $collection = new Collection(['account_id' => 1, 'product' => 'Desk', 'amount' => 5]);
        self::assertTrue($collection->has('product'));
        self::assertTrue($collection->has(['product', 'amount']));
        self::assertFalse($collection->has(['amount', 'price']));
    }

    public function testImplode(): void
    {
        $collection = new Collection([
            ['account_id' => 1, 'product' => 'Desk'],
            ['account_id' => 2, 'product' => 'Chair'],
        ]);
        self::assertEquals('Desk, Chair', $collection->implode(', ', 'product'));

        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals('1-2-3-4-5', $collection->implode('-'));
    }

    /**
     * @depends testToArray
     */
    public function testIntersect(): void
    {
        $collection = new Collection(['Desk', 'Sofa', 'Chair']);
        $intersect = $collection->intersect(['Desk', 'Chair', 'Bookcase']);

        self::assertEquals([0 => 'Desk', 2 => 'Chair'], $intersect->toArray());
    }

    public function testIntersectByKeys(): void
    {

    }

    public function testIsEmpty(): void
    {
        $collection = new Collection();
        self::assertTrue($collection->isEmpty());
        $collection = new Collection([1]);
        self::assertFalse($collection->isEmpty());
    }

    public function testIsNotEmpty(): void
    {
        $collection = new Collection();
        self::assertFalse($collection->isNotEmpty());

        $collection = new Collection([1]);
        self::assertTrue($collection->isNotEmpty());
    }

    public function testJoin(): void
    {

    }

    /**
     * @depends testAll
     */
    public function testKeyBy(): void
    {
        $collection = new Collection([
            ['product_id' => 'prod-100', 'name' => 'Desk'],
            ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $keyed = $collection->keyBy('product_id');
        self::assertEquals(
            [
                'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
                'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair']
            ],
            $keyed->all()
        );
    }

    /**
     * @depends testAll
     */
    public function testKeys(): void
    {
        $collection = new Collection([
            'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
            'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $keys = $collection->keys();

        self::assertEquals(['prod-100', 'prod-200'], $keys->all());
    }

    public function testLast(): void
    {
        $collection = new Collection([1, 2, 3, 4]);
        $last = $collection->last(function ($value, $key) {
            return $value < 3;
        });
        self::assertEquals(2, $last);
    }

    /**
     * @depends testToArray
     */
    public function testMake(): void
    {
        $collection = new Collection([1, 2, 3]);
        self::assertEquals([1, 2, 3], $collection->toArray());
    }

    /**
     * @depends testAll
     */
    public function testMap(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $multiplied = $collection->map(function ($item, $key) {
            return $item * 2;
        });

        self::assertEquals([1, 2, 3, 4, 5], $collection->all());
        self::assertEquals([2, 4, 6, 8, 10], $multiplied->all());
    }

    public function testMapInto(): void
    {

    }

    /**
     * @depends testChunk
     * @depends testToArray
     */
    public function testMapSpread(): void
    {
        $collection = new Collection([0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $chunks = $collection->chunk(2);
        $sequence = $chunks->mapSpread(function ($even, $odd) {
            return $even + $odd;
        });

        self::assertEquals([1, 5, 9, 13, 17], $sequence->toArray());
    }

    public function testMapToGroups(): void
    {

    }

    public function testMapWithKeys(): void
    {

    }

    public function testMax(): void
    {
        $collection = new Collection([
            ['foo' => 10],
            ['foo' => 20]
        ]);
        self::assertEquals(20, $collection->max('foo'));

        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals(5, $collection->max());
    }

    public function testMedian(): void
    {

    }

    public function testMerge(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testMergeRecursive(): void
    {
        $collection = new Collection(['product_id' => 1, 'price' => 100]);

        $merged = $collection->mergeRecursive([
            'product_id' => 2,
            'price' => 200,
            'discount' => false
        ]);

        self::assertEquals(['product_id' => [1, 2], 'price' => [100, 200], 'discount' => false], $merged->toArray());
    }

    public function testMin(): void
    {

    }

    public function testMode(): void
    {

    }

    /**
     * @depends testAll
     * @depends testValues
     */
    public function testNth(): void
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f']);
        self::assertEquals(['a', 'e'], $collection->nth(4)->values()->all());
    }

    public function testOnly(): void
    {

    }

    /**
     * @depends testAll
     */
    public function testPad(): void
    {
        $collection = new Collection(['A', 'B', 'C']);

        $filtered = $collection->pad(5, 0);
        self::assertEquals(['A', 'B', 'C', 0, 0], $filtered->all());

        $filtered = $collection->pad(-5, 0);
        self::assertEquals([0, 0, 'A', 'B', 'C'], $filtered->all());
    }

    public function testPartition(): void
    {

    }

    public function testPipe(): void
    {

    }

    public function testPipeInto(): void
    {

    }

    /**
     * @depends testAll
     */
    public function testPluck(): void
    {
        $collection = new Collection([
            ['product_id' => 'prod-100', 'name' => 'Desk'],
            ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $plucked = $collection->pluck('name');

        self::assertEquals(['Desk', 'Chair'], $plucked->all());
    }

    /**
     * @depends testToArray
     */
    public function testPluckDot(): void
    {
        $collection = new Collection([
            [
                'speakers' => [
                    'first_day' => ['Rosa', 'Judith'],
                    'second_day' => ['Angela', 'Kathleen'],
                ],
            ],
        ]);

        $plucked = $collection->pluck('speakers.first_day');

        self::assertEquals(['Rosa', 'Judith'], $plucked->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testPluckIndex(): void
    {
        $collection = new Collection([
            ['product_id' => 'prod-100', 'name' => 'Desk'],
            ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $plucked = $collection->pluck('name', 'product_id');

        self::assertEquals(['prod-100' => 'Desk', 'prod-200' => 'Chair'], $plucked->toArray());
    }

    /**
     * @depends testAll
     */
    public function testPop(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals(5, $collection->pop());
        self::assertEquals([1, 2, 3, 4], $collection->all());
    }

    public function testPrepend(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $collection->prepend(0);
        self::assertEquals([0, 1, 2, 3, 4, 5], $collection->all());
    }

    public function testPull(): void
    {
        $collection = new Collection(['product_id' => 'prod-100', 'name' => 'Desk']);
        self::assertEquals('Desk', $collection->pull('name'));
        self::assertEquals(['product_id' => 'prod-100'], $collection->all());
    }

    /**
     * @depends testAll
     */
    public function testPush(): void
    {
        $collection = new Collection([1, 2, 3, 4]);
        $collection->push(5);

        self::assertEquals([1, 2, 3, 4, 5], $collection->all());
    }

    /**
     * @depends testAll
     */
    public function testPut(): void
    {
        $collection = new Collection(['product_id' => 1, 'name' => 'Desk']);
        $collection->put('price', 100);
        self::assertEquals(['product_id' => 1, 'name' => 'Desk', 'price' => 100], $collection->all());
    }

    public function testRandom(): void
    {

    }

    public function testReduce(): void
    {

    }

    public function testReject(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testReplace(): void
    {
        $collection = new Collection(['Taylor', 'Abigail', 'James']);
        $replaced = $collection->replace([1 => 'Victoria', 3 => 'Finn']);
        self::assertEquals(['Taylor', 'Victoria', 'James', 'Finn'], $replaced->toArray());
        self::assertEquals(['Taylor', 'Abigail', 'James'], $collection->toArray());
    }

    public function testReplaceRecursive(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testReverse(): void
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e']);
        $reversed = $collection->reverse();
        self::assertEquals([4 => 'e', 3 => 'd', 2 => 'c', 1 => 'b', 0 => 'a'], $reversed->toArray());
    }

    public function testSearch(): void
    {
        $collection = new Collection([2, 4, 6, 8]);

        self::assertEquals(1, $collection->search(4));
        self::assertFalse($collection->search('4', true));
        self::assertFalse($collection->search(10));
        self::assertEquals(2, $collection->search(function ($item, $key) {
            return $item > 5;
        }));
    }

    public function testShift(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals(1, $collection->shift());
        self::assertEquals([2, 3, 4, 5], $collection->all());
    }

    public function testShuffle(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testSkip(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $skipped = $collection->skip(4);
        self::assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $collection->toArray());
        self::assertEquals([5, 6, 7, 8, 9, 10], $skipped->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testSkipUntil(): void
    {
        $collection = new Collection([1, 2, 3, 4]);
        $subset = $collection->skipUntil(function ($item) {
            return $item >= 3;
        });
        self::assertEquals([3, 4], $subset->toArray());
        self::assertEquals([1, 2, 3, 4], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testSkipWhile(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        $subset = $collection->skipWhile(function ($item) {
            return $item <= 3;
        });

        self::assertEquals([4], $subset->toArray());
        self::assertEquals([1, 2, 3, 4], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testSlice(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $slice = $collection->slice(4);
        $slice2 = $collection->slice(4, 2);

        self::assertEquals([5, 6, 7, 8, 9, 10], $slice->toArray());
        self::assertEquals([5, 6], $slice2->toArray());
        self::assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $collection->toArray());
    }

    public function testSole(): void
    {

    }

    public function testSome(): void
    {

    }

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testSort(): void
    {
        $collection = new Collection([5, 3, 1, 2, 4]);
        $sorted = $collection->sort();
        $sorted->values()->toArray();

        self::assertEquals([1, 2, 3, 4, 5], $sorted->values()->toArray());
        self::assertEquals([5, 3, 1, 2, 4], $collection->toArray());
    }

    public function testSortBy(): void
    {

    }

    public function testSortByDesc(): void
    {

    }

    public function testSortDesc(): void
    {

    }

    public function testSortKeys(): void
    {

    }

    public function testSortKeysDesc(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testSplice(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $chunk = $collection->splice(2);
        self::assertEquals([3, 4, 5], $chunk->toArray());
        self::assertEquals([1, 2], $collection->toArray());

        $collection = new Collection([1, 2, 3, 4, 5]);
        $chunk = $collection->splice(2, 1);
        self::assertEquals([3], $chunk->toArray());
        self::assertEquals([1, 2, 4, 5], $collection->toArray());

        $collection = new Collection([1, 2, 3, 4, 5]);
        $chunk = $collection->splice(2, 1, [10, 11]);
        self::assertEquals([3], $chunk->toArray());
        self::assertEquals([1, 2, 10, 11, 4, 5], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testSplit(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $groups = $collection->split(3);
        self::assertEquals([[1, 2], [3, 4], [5]], $groups->toArray());

        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $groups = $collection->split(3);
        self::assertEquals([[1, 2, 3, 4], [5, 6, 7, 8], [9, 10]], $groups->toArray());
    }

    public function testSplitIn(): void
    {

    }

    public function testSum(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals(15, $collection->sum());

        $collection = new Collection([
            ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
            ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
        ]);
        self::assertEquals(1272, $collection->sum('pages'));
    }

    /**
     * @depends testToArray
     */
    public function testTake(): void
    {
        $collection = new Collection([0, 1, 2, 3, 4, 5]);
        $chunk = $collection->take(3);
        self::assertEquals([0, 1, 2], $chunk->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testTakeUntil(): void
    {
        $collection = new Collection([1, 2, 3, 4]);
        $subset1 = $collection->takeUntil(function ($item) {
            return $item >= 3;
        });
        $subset2 = $collection->takeUntil(3);

        self::assertEquals([1, 2], $subset1->toArray());
        self::assertEquals([1, 2], $subset2->toArray());
        self::assertEquals([1, 2, 3, 4], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testTakeWhile(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        $subset = $collection->takeWhile(function ($item) {
            return $item < 3;
        });
        self::assertEquals([1, 2], $subset->toArray());
    }

    /**
     * @depends testToArray
     * @depends testSort
     * @depends testShift
     * @depends testValues
     */
    public function testTap(): void
    {
        $collection = new Collection([2, 4, 3, 1, 5]);
        self::assertEquals(
            1,
            $collection
                ->sort()
                ->tap(function (Collection $collection) {
                    self::assertEquals([1, 2, 3, 4, 5], $collection->values()->toArray());
                })
                ->shift()
        );
    }

    /**
     * @depends testToArray
     */
    public function testTimes(): void
    {
        $collection = Collection::times(10, function ($number) {
            return $number * 9;
        });
        self::assertEquals([9, 18, 27, 36, 45, 54, 63, 72, 81, 90], $collection->toArray());
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray(Collection $collection, array $actual): void
    {
        self::assertEquals($collection->toArray(), $actual);
    }

    public function testToJson(): void
    {
        $collection = new Collection(['name' => 'Desk', 'price' => 200]);
        self::assertEquals('{"name":"Desk","price":200}', $collection->toJson());
    }

    /**
     * @depends testToArray
     */
    public function testTransform(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $collection->transform(function ($item, $key) {
            return $item * 2;
        });

        self::assertEquals([2, 4, 6, 8, 10], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testUnion(): void
    {
        $collection = new Collection([1 => ['a'], 2 => ['b']]);
        $union = $collection->union([3 => ['c'], 1 => ['b']]);

        self::assertEquals([1 => ['a'], 2 => ['b'], 3 => ['c']], $union->toArray());
    }

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testUnique(): void
    {
        $collection = new Collection([1, 1, 2, 2, 3, 4, 2]);
        $unique = $collection->unique();
        self::assertEquals([1, 2, 3, 4], $unique->values()->toArray());
    }

    public function testUniqueStrict(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testUnless(): void
    {
        $collection = new Collection([1, 2, 3]);

        $collection->unless(true, function (Collection $collection) {
            return $collection->push(4);
        });

        $collection->unless(false, function (Collection $collection) {
            return $collection->push(5);
        });

        self::assertEquals([1, 2, 3, 5], $collection->toArray());
    }

    /**
     * @depends testToArray
     * @depends testPush
     */
    public function testUnlessEmpty(): void
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->unlessEmpty(function (Collection $collection) {
            return $collection->push('adam');
        });
        self::assertEquals(['michael', 'tom', 'adam'], $collection->toArray());

        $collection = new Collection();
        $collection->unlessEmpty(function (Collection $collection) {
            return $collection->push('adam');
        });
        self::assertEquals([], $collection->toArray());

        $collection = new Collection();
        $collection->unlessEmpty(function (Collection $collection) {
            return $collection->push('adam');
        }, function (Collection $collection) {
            return $collection->push('taylor');
        });
        self::assertEquals(['taylor'], $collection->toArray());
    }

    /**
     * @depends testToArray
     * @depends testPush
     */
    public function testUnlessNotEmpty(): void
    {
        $collection = new Collection(['Michael', 'Tom']);

        $collection->unlessNotEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        });
        self::assertEquals(['Michael', 'Tom'], $collection->toArray());

        $collection = new Collection();
        $collection->unlessNotEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        });
        self::assertEquals(['Adam'], $collection->toArray());

        $collection = new Collection(['Michael', 'Tom']);
        $collection->unlessNotEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        }, function (Collection $collection) {
            return $collection->push('Taylor');
        });
        self::assertEquals(['Michael', 'Tom', 'Taylor'], $collection->toArray());
    }

    public function testUnwrap(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testValues(): void
    {
        $collection = new Collection([10 => ['product' => 'Desk', 'price' => 200], 11 => ['product' => 'Desk', 'price' => 200]]);
        $values = $collection->values();

        self::assertEquals([0 => ['product' => 'Desk', 'price' => 200], 1 => ['product' => 'Desk', 'price' => 200]], $values->toArray());
        self::assertEquals([10 => ['product' => 'Desk', 'price' => 200], 11 => ['product' => 'Desk', 'price' => 200]], $collection->toArray());
    }

    /**
     * @depends testToArray
     * @depends testPush
     */
    public function testWhen(): void
    {
        $collection = new Collection([1, 2, 3]);

        $collection->when(false, function (Collection $collection) {
            return $collection->push(5);
        });

        self::assertEquals([1, 2, 3], $collection->toArray());

        $collection->when(true, function (Collection $collection) {
            return $collection->push(4);
        });

        self::assertEquals([1, 2, 3, 4], $collection->toArray());
    }

    /**
     * @depends testToArray
     * @depends testPush
     */
    public function testWhenEmpty(): void
    {
        $collection = new Collection(['Michael', 'Tom']);

        $collection->whenEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        });
        self::assertEquals(['Michael', 'Tom'], $collection->toArray());

        $collection = new Collection();
        $collection->whenEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        });
        self::assertEquals(['Adam'], $collection->toArray());

        $collection = new Collection(['Michael', 'Tom']);
        $collection->whenEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        }, function (Collection $collection) {
            return $collection->push('Taylor');
        });
        self::assertEquals(['Michael', 'Tom', 'Taylor'], $collection->toArray());
    }

    /**
     * @depends testToArray
     * @depends testPush
     */
    public function testWhenNotEmpty(): void
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->whenNotEmpty(function (Collection $collection) {
            return $collection->push('adam');
        });
        self::assertEquals(['michael', 'tom', 'adam'], $collection->toArray());

        $collection = new Collection();
        $collection->whenNotEmpty(function (Collection $collection) {
            return $collection->push('adam');
        });
        self::assertEquals([], $collection->toArray());


        $collection = new Collection();
        $collection->whenNotEmpty(function (Collection $collection) {
            return $collection->push('adam');
        }, function (Collection $collection) {
            return $collection->push('taylor');
        });
        self::assertEquals(['taylor'], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testWhere(): void
    {
        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Door', 'price' => 100],
        ]);

        $filtered = $collection->where('price', 100);

        self::assertEquals(
            [
                ['product' => 'Chair', 'price' => 100],
                ['product' => 'Door', 'price' => 100]
            ],
            $filtered->toArray()
        );

        $collection = new Collection([
            ['name' => 'Jim', 'deleted_at' => '2019-01-01 00:00:00'],
            ['name' => 'Sally', 'deleted_at' => '2019-01-02 00:00:00'],
            ['name' => 'Sue', 'deleted_at' => null],
        ]);

        $filtered = $collection->where('deleted_at', null, '!=');

        self::assertEquals(
            [
                ['name' => 'Jim', 'deleted_at' => '2019-01-01 00:00:00'],
                ['name' => 'Sally', 'deleted_at' => '2019-01-02 00:00:00']
            ],
            $filtered->toArray()
        );

    }

    public function testWhereStrict(): void
    {

    }

    public function testWhereBetween(): void
    {
        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 80],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Pencil', 'price' => 30],
            ['product' => 'Door', 'price' => 100],
        ]);

        $filtered = $collection->whereBetween('price', 100, 200);

        $filtered->toArray();

        self::assertEquals(
            [
                ['product' => 'Desk', 'price' => 200],
                ['product' => 'Bookcase', 'price' => 150],
                ['product' => 'Door', 'price' => 100],
            ],
            $filtered->toArray()
        );
    }

    public function testWhereIn(): void
    {

    }

    public function testWhereInStrict(): void
    {

    }

    public function testWhereInstanceOf(): void
    {

    }

    public function testWhereNotBetween(): void
    {

    }

    public function testWhereNotIn(): void
    {

    }

    public function testWhereNotInStrict(): void
    {

    }

    public function testWhereNotNull(): void
    {

    }

    public function testWhereNull(): void
    {

    }

    public function testWrap(): void
    {
        $collection = Collection::wrap('John Doe');
        self::assertEquals(['John Doe'], $collection->all());

        $collection = Collection::wrap(1);
        self::assertEquals([1], $collection->all());

        $collection = Collection::wrap(['John Doe']);
        self::assertEquals(['John Doe'], $collection->all());

        $collection = Collection::wrap($collection);
        self::assertEquals(['John Doe'], $collection->all());
    }

    /**
     * @depends testToArray
     */
    public function testZip(): void
    {
        $collection = new Collection(['Chair', 'Desk']);
        $zipped = $collection->zip([100, 200]);
        self::assertEquals([['Chair', 100], ['Desk', 200]], $zipped->toArray());
        self::assertEquals(['Chair', 'Desk'], $collection->toArray());
    }

    /**
     **********************************************************
     * Data Providers
     **********************************************************
     */

    public function allDataProvider(): array
    {
        return [
            [new Collection([1, 2, 3]), [1, 2, 3]],
            [new Collection([new Collection([1, 2, 3]), new Collection([1, 2, 3])]), [new Collection([1, 2, 3]), new Collection([1, 2, 3])]],
            [new Collection(['product_id' => 'prod-100', 'name' => 'Desk']), ['product_id' => 'prod-100', 'name' => 'Desk']],
            [new Collection(['product_id' => ['prod-100'], 'name' => ['Desk']]), ['product_id' => ['prod-100'], 'name' => ['Desk']]],
        ];
    }

    public function toArrayDataProvider(): array
    {
        return [
            [new Collection([1, 2, 3]), [1, 2, 3]],
            [new Collection([new Collection([1, 2, 3]), new Collection([1, 2, 3])]), [[1, 2, 3], [1, 2, 3]]],
            [new Collection(['product_id' => 'prod-100', 'name' => 'Desk']), ['product_id' => 'prod-100', 'name' => 'Desk']],
            [new Collection(['product_id' => ['prod-100'], 'name' => ['Desk']]), ['product_id' => ['prod-100'], 'name' => ['Desk']]],
        ];
    }

    public function avgDataProvider(): array
    {
        return [
            [new Collection([1, 1, 2, 4]), 2, null],
            [new Collection([['foo' => 10], ['foo' => 10], ['foo' => 20], ['foo' => 40]]), 20, 'foo'],
        ];
    }

}
