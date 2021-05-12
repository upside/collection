<?php

namespace Upside\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Upside\Collection\Collection;

class CollectionTest extends TestCase
{
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

    public function avgDataProvider()
    {
        return [
            [new Collection([1, 1, 2, 4]), 2, null],
            [new Collection([['foo' => 10], ['foo' => 10], ['foo' => 20], ['foo' => 40]]), 20, 'foo'],
        ];
    }

    public function testSearch()
    {
        $collection = new Collection([2, 4, 6, 8]);

        self::assertEquals(1, $collection->search(4));
        self::assertFalse($collection->search(10));
    }

    public function testCountBy()
    {

    }

    public function testGroupBy()
    {

    }

    public function testExcept()
    {

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

    public function testMapInto()
    {

    }

    public function testSum()
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
     * @depends testAll
     */
    public function testChunk()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7]);
        $chunks = $collection->chunk(4);

        self::assertEquals([[1, 2, 3, 4], [5, 6, 7]], $chunks->all());
    }

    public function testCrossJoin()
    {

    }

    public function testFilter()
    {

    }

    public function testLast()
    {
        $collection = new Collection([1, 2, 3, 4]);
        $last = $collection->last(function ($value, $key) {
            return $value < 3;
        });
        self::assertEquals(2, $last);
    }

    public function testTake()
    {

    }

    public function testDuplicates()
    {

    }

    public function testWhere()
    {

    }

    public function testChunkWhile()
    {

    }

    public function testCount()
    {
        $collection = new Collection([1, 2, 3, 4]);
        self::assertEquals(4, $collection->count());
    }

    public function testOnly()
    {

    }

    public function testSortDesc()
    {

    }

    public function testSort()
    {

    }

    public function testReject()
    {

    }

    public function testSplit()
    {

    }

    /**
     * @depends testAll
     */
    public function testKeyBy()
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

    public function testJoin()
    {

    }

    /**
     * @depends testAll
     */
    public function testZip()
    {
        $collection = new Collection(['Chair', 'Desk']);
        $zipped = $collection->zip([100, 200]);
        self::assertEquals([['Chair', 100], ['Desk', 200]], $zipped->all());
        self::assertEquals(['Chair', 'Desk'], $collection->all());
    }

    public function testAverage()
    {

    }

    /**
     * @depends testAll
     */
    public function testPut()
    {
        $collection = new Collection(['product_id' => 1, 'name' => 'Desk']);
        $collection->put('price', 100);
        self::assertEquals(['product_id' => 1, 'name' => 'Desk', 'price' => 100], $collection->all());
    }

    public function testUnlessEmpty()
    {

    }

    public function testTimes()
    {

    }

    /**
     * @depends testAll
     */
    public function testSkipUntil()
    {
        $collection = new Collection([1, 2, 3, 4]);
        $subset = $collection->skipUntil(function ($item) {
            return $item >= 3;
        });
        self::assertEquals([3, 4], $subset->all());
        self::assertEquals([1, 2, 3, 4], $collection->all());
    }

    /**
     * @depends testAll
     */
    public function testSkipWhile()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $subset = $collection->skipWhile(function ($item) {
            return $item <= 3;
        });

        self::assertEquals([4], $subset->all());
        self::assertEquals([1, 2, 3, 4], $collection->all());
    }

    /**
     * @depends testAll
     */
    public function testUnion()
    {
        $collection = new Collection([1 => ['a'], 2 => ['b']]);
        $union = $collection->union([3 => ['c'], 1 => ['b']]);

        self::assertEquals([1 => ['a'], 2 => ['b'], 3 => ['c']], $union->all());
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

    public function testSortKeys()
    {

    }

    public function testSole()
    {

    }

    public function testForget()
    {

    }

    public function testMode()
    {

    }

    public function testMapWithKeys()
    {

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

    public function testEachSpread()
    {

    }

    public function testImplode()
    {

    }

    public function testMapToGroups()
    {

    }

    public function testEach()
    {

    }

    /**
     * @depends testAll
     */
    public function testMap()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $multiplied = $collection->map(function ($item, $key) {
            return $item * 2;
        });

        self::assertEquals([1, 2, 3, 4, 5], $collection->all());
        self::assertEquals([2, 4, 6, 8, 10], $multiplied->all());
    }

    public function testWhenNotEmpty()
    {

    }

    public function testMerge()
    {

    }

    /**
     * @depends testAll
     */
    public function testPop()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals(5, $collection->pop());
        self::assertEquals([1, 2, 3, 4], $collection->all());
    }

    public function testWhereNotIn()
    {

    }

    public function testPipeInto()
    {

    }

    /**
     * @depends testAll
     */
    public function testPluck()
    {
        $collection = new Collection([
            ['product_id' => 'prod-100', 'name' => 'Desk'],
            ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $plucked = $collection->pluck('name');

        self::assertEquals(['Desk', 'Chair'], $plucked->all());
    }

    /**
     * @depends testAll
     */
    public function testPluckIndex()
    {
        $collection = new Collection([
            ['product_id' => 'prod-100', 'name' => 'Desk'],
            ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $plucked = $collection->pluck('name', 'product_id');

        self::assertEquals(['prod-100' => 'Desk', 'prod-200' => 'Chair'], $plucked->all());
    }

    /**
     * @depends testAll
     */
    public function testPluckDot()
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

        self::assertEquals(['Rosa', 'Judith'], $plucked->all());
    }

    public function testWhereNotBetween()
    {

    }

    public function testUnlessNotEmpty()
    {

    }

    public function testTakeWhile()
    {

    }

    public function testTap()
    {

    }

    public function testDiff()
    {

    }

    /**
     * @depends testAll
     */
    public function testReverse()
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e']);
        $reversed = $collection->reverse();
        self::assertEquals([4 => 'e', 3 => 'd', 2 => 'c', 1 => 'b', 0 => 'a'], $reversed->all());
    }

    /**
     * @depends testAll
     * @depends testValues
     */
    public function testNth()
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f']);
        self::assertEquals(['a', 'e'], $collection->nth(4)->values()->all());
    }

    public function testWhereInstanceOf()
    {

    }

    public function testWrap()
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

    public function testIsNotEmpty()
    {
        $collection = new Collection([]);
        self::assertFalse($collection->isNotEmpty());
        $collection = new Collection([1]);
        self::assertTrue($collection->isNotEmpty());
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray(Collection $collection, array $actual)
    {
        self::assertEquals($collection->toArray(), $actual);
    }

    public function testIntersect()
    {

    }

    /**
     * @depends testAll
     */
    public function testPush()
    {
        $collection = new Collection([1, 2, 3, 4]);
        $collection->push(5);

        self::assertEquals([1, 2, 3, 4, 5], $collection->all());
    }

    /**
     * @depends testAll
     */
    public function testSlice()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $slice = $collection->slice(4);
        $slice2 = $collection->slice(4, 2);

        self::assertEquals([5, 6, 7, 8, 9, 10], $slice->all());
        self::assertEquals([5, 6], $slice2->all());
        self::assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $collection->all());
    }

    public function testForPage()
    {

    }

    public function testTakeUntil()
    {

    }

    public function testUnwrap()
    {

    }

    public function testMapSpread()
    {

    }

    public function testSplice()
    {

    }

    public function testMergeRecursive()
    {

    }

    public function testContains()
    {

    }

    public function testWhereInStrict()
    {

    }

    public function testSortByDesc()
    {

    }

    public function testEvery()
    {

    }

    public function testWhereNotNull()
    {

    }

    /**
     * @depends testAll
     */
    public function testCollect()
    {
        $collectionA = new Collection([1, 2, 3]);

        $collectionB = $collectionA->collect();

        self::assertEquals([1, 2, 3], $collectionA->all());
        self::assertEquals([1, 2, 3], $collectionB->all());
        self::assertEquals($collectionA->all(), $collectionB->all());
    }

    public function testWhereStrict()
    {

    }

    public function testFirstWhere()
    {

    }

    public function testDiffKeys()
    {

    }

    /**
     * @depends testAll
     */
    public function testPad()
    {
        $collection = new Collection(['A', 'B', 'C']);

        $filtered = $collection->pad(5, 0);
        self::assertEquals(['A', 'B', 'C', 0, 0], $filtered->all());

        $filtered = $collection->pad(-5, 0);
        self::assertEquals([0, 0, 'A', 'B', 'C'], $filtered->all());
    }

    public function testToJson()
    {
        $collection = new Collection(['name' => 'Desk', 'price' => 200]);
        self::assertEquals('{"name":"Desk","price":200}', $collection->toJson());
    }

    public function testUnless()
    {

    }

    public function testIsEmpty()
    {
        $collection = new Collection([]);
        self::assertTrue($collection->isEmpty());
        $collection = new Collection([1]);
        self::assertFalse($collection->isEmpty());
    }

    public function testSortKeysDesc()
    {

    }

    public function testSome()
    {

    }

    public function testSplitIn()
    {

    }

    public function testFlip()
    {

    }

    public function testReduce()
    {

    }

    public function testFlatten()
    {

    }

    public function testWhereBetween()
    {

    }

    public function testMax()
    {

    }

    public function testFirst()
    {

    }

    /**
     * @depends testAll
     */
    public function testTransform()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $collection->transform(function ($item, $key) {
            return $item * 2;
        });

        self::assertEquals([2, 4, 6, 8, 10], $collection->all());
    }

    public function testWhereNull()
    {

    }

    /**
     * @depends testAll
     */
    public function testKeys()
    {
        $collection = new Collection([
            'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
            'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

        $keys = $collection->keys();

        self::assertEquals(['prod-100', 'prod-200'], $keys->all());
    }

    public function testShuffle()
    {

    }

    public function testFlatMap()
    {

    }

    public function testMin()
    {

    }

    /**
     * @depends testAll
     * @depends testPush
     */
    public function testWhen()
    {
        $collection = new Collection([1, 2, 3]);

        $collection->when(false, function (Collection $collection) {
            return $collection->push(5);
        });

        self::assertEquals([1, 2, 3], $collection->all());

        $collection->when(true, function (Collection $collection) {
            return $collection->push(4);
        });

        self::assertEquals([1, 2, 3, 4], $collection->all());
    }

    /**
     * @depends testAll
     * @depends testValues
     */
    public function testUnique()
    {
        $collection = new Collection([1, 1, 2, 2, 3, 4, 2]);
        $unique = $collection->unique();
        self::assertEquals([1, 2, 3, 4], $unique->values()->all());
    }

    /**
     * @dataProvider allDataProvider
     */
    public function testAll(Collection $collection, array $actual)
    {
        self::assertEquals($collection->all(), $actual);
    }

    public function testWhereNotInStrict()
    {

    }

    public function testSortBy()
    {

    }

    public function testRandom()
    {

    }

    public function testWhereIn()
    {

    }

    public function testPull()
    {
        $collection = new Collection(['product_id' => 'prod-100', 'name' => 'Desk']);
        self::assertEquals('Desk', $collection->pull('name'));
        self::assertEquals(['product_id' => 'prod-100'], $collection->all());
    }

    public function testPrepend()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $collection->prepend(0);
        self::assertEquals([0, 1, 2, 3, 4, 5], $collection->all());
    }

    public function testShift()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals(1, $collection->shift());
        self::assertEquals([2, 3, 4, 5], $collection->all());
    }

    public function testIntersectByKeys()
    {

    }

    public function testMedian()
    {

    }

    public function testSkip()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $skipped = $collection->skip(4);
        self::assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $collection->all());
        self::assertEquals([5, 6, 7, 8, 9, 10], $skipped->all());
    }

    public function testDuplicatesStrict()
    {

    }

    public function testGet()
    {
        $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);
        self::assertEquals('taylor', $collection->get('name'));
        self::assertEquals('taylor', $collection->get('test', 'taylor'));
        self::assertEquals(34, $collection->get('age', 34));
    }

    public function testReplace()
    {
        $collection = new Collection(['Taylor', 'Abigail', 'James']);
        $replaced = $collection->replace([1 => 'Victoria', 3 => 'Finn']);
        self::assertEquals(['Taylor', 'Victoria', 'James', 'Finn'], $replaced->all());
    }

    public function testHas()
    {

    }

    public function testPartition()
    {

    }

    /**
     * @depends testAll
     */
    public function testValues()
    {
        $collection = new Collection([10 => ['product' => 'Desk', 'price' => 200], 11 => ['product' => 'Desk', 'price' => 200]]);
        $values = $collection->values();

        self::assertEquals([0 => ['product' => 'Desk', 'price' => 200], 1 => ['product' => 'Desk', 'price' => 200]], $values->all());
        self::assertEquals([10 => ['product' => 'Desk', 'price' => 200], 11 => ['product' => 'Desk', 'price' => 200]], $collection->all());
    }

    public function testUniqueStrict()
    {

    }

    /**
     * @depends testAll
     * @depends testPush
     */
    public function testWhenEmpty()
    {
        $collection = new Collection(['Michael', 'Tom']);

        $collection->whenEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        });
        self::assertEquals(['Michael', 'Tom'], $collection->all());

        $collection = new Collection();
        $collection->whenEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        });
        self::assertEquals(['Adam'], $collection->all());

        $collection = new Collection(['Michael', 'Tom']);
        $collection->whenEmpty(function (Collection $collection) {
            return $collection->push('Adam');
        }, function (Collection $collection) {
            return $collection->push('Taylor');
        });
        self::assertEquals(['Michael', 'Tom', 'Taylor'], $collection->all());
    }

    public function testReplaceRecursive()
    {

    }

    public function testContainsStrict()
    {

    }

    public function testDiffAssoc()
    {

    }

    /**
     * @dataProvider avgDataProvider
     */
    public function testAvg(Collection $collection, float $actual, string|null $key)
    {
        self::assertEquals($actual, $collection->avg($key));
        self::assertEquals($actual, $collection->average($key));
    }

    public function testPipe()
    {

    }
}
