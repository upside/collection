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
    public function testMake()
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
    public function testAll()
    {
        $collection = Collection::make([1, 2, 3]);
        $this->assertSame([1, 2, 3], $collection->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testAvg()
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
        $this->assertSame(20.0, $collection->avg(function ($item) { return $item['foo']; }));

    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testAverage()
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
        $this->assertSame(20.0, $collection->average(function ($item) { return $item['foo']; }));
    }

    /**
     * @depends testMake
     * @depends testToArray
     */
    public function testChunk()
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
    public function testChunkWhile()
    {
        $this->markTestIncomplete('Отсутствует реализация');

        $collection = Collection::make(str_split('AABBCCCD'));

        $chunks = $collection->chunkWhile(function ($value, $key, $chunk) {
            return $value === $chunk->last();
        });

        $this->assertSame([['A', 'A'], ['B', 'B'], ['C', 'C', 'C'], ['D']], $chunks->toArray());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCollapse()
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
    public function testCollect()
    {
        $collectionA = Collection::make([1, 2, 3]);

        $collectionB = $collectionA->collect();

        $this->assertSame([1, 2, 3], $collectionB->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCombine()
    {
        $collection = Collection::make(['name', 'age']);

        $combined = $collection->combine(['George', 29]);

        $this->assertSame(['name' => 'George', 'age' => 29], $combined->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testConcat()
    {
        $this->markTestIncomplete('Реализация только на PHP 8.1 и выше + недоделана работа не с массивом а с коллекцией');

        $collection = Collection::make(['John Doe']);

        $concatenated = $collection->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);

        $this->assertSame(['John Doe', 'Jane Doe', 'Johnny Doe'], $concatenated->all());
    }

    /**
     * @depends testMake
     */
    public function testContains()
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
    public function testContainsStrict()
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);
        $this->assertTrue($collection->containsStrict(3));
        $this->assertFalse($collection->containsStrict('3'));
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCountBy()
    {
        $collection = Collection::make([1, 2, 2, 2, 3]);

        $this->assertSame([1 => 1, 2 => 3, 3 => 1], $collection->countBy()->all());

        $collection = Collection::make(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com']);

        $counted = $collection->countBy(function ($email) {
            return substr(strrchr($email, '@'), 1);
        });

        $this->assertSame(['gmail.com' => 2, 'yahoo.com' => 1], $counted->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testCrossJoin()
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
    public function testDiff()
    {
        $collection = Collection::make([1, 2, 3, 4, 5]);

        $diff = $collection->diff([2, 4, 6, 8]);

        $this->assertSame([0 => 1, 2 => 3, 4 => 5], $diff->all());
    }

    /**
     * @depends testMake
     * @depends testAll
     */
    public function testDiffAssoc()
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
    public function testDiffKeys()
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
    public function testDuplicates()
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

    public function testDuplicatesStrict()
    {
        $this->markTestIncomplete();
    }

    public function testEach()
    {
        $this->markTestIncomplete();
    }

    public function testEachSpread()
    {
        $this->markTestIncomplete();
    }

    public function testEvery()
    {
        $this->markTestIncomplete();
    }

    public function testExcept()
    {
        $this->markTestIncomplete();
    }

    public function testFilter()
    {
        $this->markTestIncomplete();
    }

    public function testFirst()
    {
        $this->markTestIncomplete();
    }

    public function testFirstWhere()
    {
        $this->markTestIncomplete();
    }

    public function testFlatMap()
    {
        $this->markTestIncomplete();
    }

    public function testFlatten()
    {
        $this->markTestIncomplete();
    }

    public function testFlip()
    {
        $this->markTestIncomplete();
    }

    public function testForget()
    {
        $this->markTestIncomplete();
    }

    public function testForPage()
    {
        $this->markTestIncomplete();

    }

    public function testGet()
    {
        $this->markTestIncomplete();

    }

    public function testGroupBy()
    {
        $this->markTestIncomplete();

    }

    public function testHas()
    {
        $this->markTestIncomplete();

    }

    public function testImplode()
    {
        $this->markTestIncomplete();

    }

    public function testIntersect()
    {
        $this->markTestIncomplete();

    }

    public function testIntersectByKeys()
    {
        $this->markTestIncomplete();

    }

    public function testIsEmpty()
    {
        $this->markTestIncomplete();

    }

    public function testIsNotEmpty()
    {
        $this->markTestIncomplete();

    }

    public function testJoin()
    {
        $this->markTestIncomplete();

    }

    public function testKeyBy()
    {
        $this->markTestIncomplete();

    }

    public function testKeys()
    {

        $this->markTestIncomplete();
    }

    public function testLast()
    {
        $this->markTestIncomplete();
    }

    public function testMap()
    {
        $this->markTestIncomplete();

    }

    public function testMapInto()
    {

        $this->markTestIncomplete();
    }

    public function testMapSpread()
    {
        $this->markTestIncomplete();
    }

    public function testMapToGroups()
    {
        $this->markTestIncomplete();
    }

    public function testMapToDictionary()
    {
        $this->markTestIncomplete();
    }

    public function testMapWithKeys()
    {
        $this->markTestIncomplete();
    }

    public function testMax()
    {
        $this->markTestIncomplete();
    }

    public function testMedian()
    {
        $this->markTestIncomplete();
    }

    public function testMerge()
    {
        $this->markTestIncomplete();
    }

    public function testMergeRecursive()
    {
        $this->markTestIncomplete();
    }

    public function testMin()
    {
        $this->markTestIncomplete();
    }

    public function testMode()
    {
        $this->markTestIncomplete();
    }

    public function testNth()
    {
        $this->markTestIncomplete();
    }

    public function testOnly()
    {
        $this->markTestIncomplete();
    }

    public function testPad()
    {
        $this->markTestIncomplete();
    }

    public function testPartition()
    {
        $this->markTestIncomplete();
    }

    public function testPipe()
    {
        $this->markTestIncomplete();
    }

    public function testPipeInto()
    {
        $this->markTestIncomplete();
    }

    public function testPluck()
    {
        $this->markTestIncomplete();
    }

    public function testPop()
    {
        $this->markTestIncomplete();
    }

    public function testPrepend()
    {
        $this->markTestIncomplete();

    }

    public function testPull()
    {
        $this->markTestIncomplete();
    }

    public function testPush()
    {
        $this->markTestIncomplete();

    }

    public function testPut()
    {
        $this->markTestIncomplete();
    }

    public function testRandom()
    {
        $this->markTestIncomplete();
    }

    public function testReduce()
    {
        $this->markTestIncomplete();
    }

    public function testReject()
    {
        $this->markTestIncomplete();
    }

    public function testReplace()
    {
        $this->markTestIncomplete();
    }

    public function testReplaceRecursive()
    {
        $this->markTestIncomplete();
    }

    public function testReverse()
    {
        $this->markTestIncomplete();
    }

    public function testSearch()
    {
        $this->markTestIncomplete();
    }

    public function testShift()
    {
        $this->markTestIncomplete();
    }

    public function testShuffle()
    {
        $this->markTestIncomplete();
    }

    public function testSkip()
    {
        $this->markTestIncomplete();
    }

    public function testSkipUntil()
    {
        $this->markTestIncomplete();
    }

    public function testSkipWhile()
    {
        $this->markTestIncomplete();
    }

    public function testSlice()
    {
        $this->markTestIncomplete();
    }

    public function testSole()
    {
        $this->markTestIncomplete();
    }

    public function testSome()
    {
        $this->markTestIncomplete();
    }

    public function testSort()
    {
        $this->markTestIncomplete();
    }

    public function testSortBy()
    {
        $this->markTestIncomplete();
    }

    public function testSortByDesc()
    {
        $this->markTestIncomplete();
    }

    public function testSortDesc()
    {
        $this->markTestIncomplete();
    }

    public function testSortKeys()
    {
        $this->markTestIncomplete();
    }

    public function testSortKeysDesc()
    {
        $this->markTestIncomplete();
    }

    public function testSplice()
    {
        $this->markTestIncomplete();
    }

    public function testSplit()
    {
        $this->markTestIncomplete();
    }

    public function testSplitIn()
    {
        $this->markTestIncomplete();
    }

    public function testSum()
    {
        $this->markTestIncomplete();
    }

    public function testTake()
    {
        $this->markTestIncomplete();
    }

    public function testTakeUntil()
    {
        $this->markTestIncomplete();
    }

    public function testTakeWhile()
    {
        $this->markTestIncomplete();
    }

    public function testTap()
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testToArray()
    {
        $collection = Collection::make(['name' => 'Desk', 'price' => 200]);

        $this->assertSame(['name' => 'Desk', 'price' => 200], $collection->toArray());
    }

    public function testToJson()
    {
        $this->markTestIncomplete();
    }

    public function testTransform()
    {
        $this->markTestIncomplete();
    }

    public function testUnion()
    {
        $this->markTestIncomplete();
    }

    public function testUnique()
    {
        $this->markTestIncomplete();
    }

    public function testUniqueStrict()
    {
        $this->markTestIncomplete();
    }

    public function testUnless()
    {
        $this->markTestIncomplete();
    }

    public function testUnlessEmpty()
    {
        $this->markTestIncomplete();
    }

    public function testUnlessNotEmpty()
    {
        $this->markTestIncomplete();
    }

    public function testValues()
    {
        $this->markTestIncomplete();
    }

    public function testWhen()
    {
        $this->markTestIncomplete();
    }

    public function testWhenEmpty()
    {
        $this->markTestIncomplete();
    }

    public function testWhenNotEmpty()
    {
        $this->markTestIncomplete();
    }

    public function testWhere()
    {
        $this->markTestIncomplete();
    }

    public function testWhereStrict()
    {
        $this->markTestIncomplete();
    }

    public function testWhereBetween()
    {
        $this->markTestIncomplete();
    }

    public function testWhereIn()
    {
        $this->markTestIncomplete();
    }

    public function testWhereInStrict()
    {
        $this->markTestIncomplete();
    }

    public function testWhereInstanceOf()
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotBetween()
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotIn()
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotInStrict()
    {
        $this->markTestIncomplete();
    }

    public function testWhereNotNull()
    {
        $this->markTestIncomplete();
    }

    public function testWhereNull()
    {
        $this->markTestIncomplete();
    }

    public function testZip()
    {
        $this->markTestIncomplete();
    }

    /**
     * @depends testMake
     */
    public function testCount()
    {
        $collection = Collection::make([1, 2, 3, 4]);

        $this->assertSame(4, $collection->count());
    }

    public function testOffsetSet()
    {
        $this->markTestIncomplete();
    }

    public function testOffsetExists()
    {
        $this->markTestIncomplete();
    }

    public function testOffsetGet()
    {
        $this->markTestIncomplete();
    }

    public function testOffsetUnset()
    {
        $this->markTestIncomplete();
    }

    public function testGetIterator()
    {
        $this->markTestIncomplete();
    }
}
