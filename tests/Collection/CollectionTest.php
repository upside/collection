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
    public function testChunk(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7]);
        $chunks = $collection->chunk(4);

        self::assertEquals([[1, 2, 3, 4], [5, 6, 7]], $chunks->toArray());
    }

    /**
     * @depends testToArray
     * @depends testLast
     */
    public function testChunkWhile(): void
    {
        $collection = new Collection(str_split('AABBCCCD'));
        $chunks = $collection->chunkWhile(function ($value, $key, Collection $chunk) {
            return $value === $chunk->last();
        });
        self::assertEquals([['A', 'A'], ['B', 'B'], ['C', 'C', 'C'], ['D']], $chunks->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testCollapse(): void
    {
        $collection = new Collection([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $collapsed = $collection->collapse();
        self::assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $collapsed->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testCombine(): void
    {
        $collection = new Collection(['name', 'age']);
        $combined = $collection->combine(['George', 29]);
        self::assertEquals(['name' => 'George', 'age' => 29], $combined->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testCollect(): void
    {
        $collectionA = new Collection([1, 2, 3]);

        $collectionB = $collectionA->collect();

        self::assertEquals([1, 2, 3], $collectionA->toArray());
        self::assertEquals([1, 2, 3], $collectionB->toArray());
        self::assertEquals($collectionA->toArray(), $collectionB->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testConcat(): void
    {
        $collection = new Collection(['John Doe']);
        $concatenated = $collection->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);
        self::assertEquals(['John Doe'], $collection->toArray());
        self::assertEquals(['John Doe', 'Jane Doe', 'Johnny Doe'], $concatenated->toArray());
    }

    public function testContains(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertFalse($collection->contains(function ($value) {
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
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertFalse($collection->containsStrict(function ($value) {
            return $value > 5;
        }));

        $collection = new Collection(['name' => 'Desk', 'price' => 100]);
        self::assertTrue($collection->containsStrict('Desk'));
        self::assertFalse($collection->containsStrict('New York'));
        self::assertFalse($collection->containsStrict('100'));

        $collection = new Collection([
            [
                'product' => 'Desk',
                'price' => 200,
            ],
            [
                'product' => 'Chair',
                'price' => 100
            ],
        ]);

        self::assertFalse($collection->containsStrict('product', 'Bookcase'));
        self::assertFalse($collection->containsStrict('price', '100'));
    }

    public function testCount(): void
    {
        $collection = new Collection([1, 2, 3, 4]);
        self::assertEquals(4, $collection->count());
    }

    /**
     * @depends testToArray
     */
    public function testCountBy(): void
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

    /**
     * @depends testToArray
     */
    public function testCrossJoin(): void
    {
        $collection = new Collection([1, 2]);
        $matrix = $collection->crossJoin(['a', 'b']);
        self::assertEquals(
            [
                [1, 'a'],
                [1, 'b'],
                [2, 'a'],
                [2, 'b'],
            ],
            $matrix->toArray()
        );

        $collection = new Collection([1, 2]);
        $matrix = $collection->crossJoin(['a', 'b'], ['I', 'II']);
        self::assertEquals(
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
            $matrix->toArray()
        );
    }

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testDiff(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $diff = $collection->diff([2, 4, 6, 8]);
        self::assertEquals([1, 3, 5], $diff->values()->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testDiffAssoc(): void
    {
        $collection = new Collection([
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

        self::assertEquals(['color' => 'orange', 'remain' => 6], $diff->toArray());
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
    public function testDuplicates(): void
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

        $collection->each(function ($item) use (&$test1) {
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
        $collection = new Collection([['John Doe', 35], ['Jane Doe', 33]]);

        $test = [];
        $collection->eachSpread(function ($name, $age) use (&$test) {
            $test[] = [$name, $age];
        });

        self::assertEquals([['John Doe', 35], ['Jane Doe', 33]], $test);
    }

    public function testEvery(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        self::assertFalse($collection->every(function ($value) {
            return $value > 2;
        }));

        self::assertTrue($collection->every(function ($value) {
            return $value > 0;
        }));
    }

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testExcept(): void
    {
        $collection = new Collection(['product_id' => 1, 'price' => 100, 'discount' => false]);
        $filtered = $collection->except(['price', 'discount']);
        self::assertEquals(['product_id' => 1], $filtered->toArray());
    }

    public function testFilter(): void
    {
        $collection = new Collection([1, 2, 3, 4]);
        $filtered = $collection->filter(function ($value, $key) {
            return $value > 2;
        });
        self::assertEquals([3, 4], $filtered->values()->toArray());

        $collection = new Collection([1, 2, 3, null, false, '', 0, []]);
        self::assertEquals([1, 2, 3], $collection->filter()->values()->toArray());
    }

    public function testFirst(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        $collection->first(function ($value) {
            return $value > 2;
        });

        self::assertEquals(3, $collection->first(function ($value) {
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

    /**
     * @depends testToArray
     */
    public function testFlatMap(): void
    {
        $collection = new Collection([
            ['name' => 'Sally'],
            ['school' => 'Arkansas'],
            ['age' => 28]
        ]);
        $flattened = $collection->flatMap(function ($values) {
            return array_map('strtoupper', $values);
        });
        self::assertEquals(['name' => 'SALLY', 'school' => 'ARKANSAS', 'age' => '28'], $flattened->toArray());
    }

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testFlatten(): void
    {
        $collection = new Collection([
            'name' => 'taylor',
            'languages' => [
                'php', 'javascript'
            ]
        ]);
        $flattened = $collection->flatten();
        self::assertEquals(['taylor', 'php', 'javascript'], $flattened->toArray());

        $collection = new Collection([
            'Apple' => [
                [
                    'name' => 'iPhone 6S',
                    'brand' => 'Apple'
                ],
            ],
            'Samsung' => [
                [
                    'name' => 'Galaxy S7',
                    'brand' => 'Samsung'
                ],
            ],
        ]);
        $products = $collection->flatten(1);
        self::assertEquals(
            [
                ['name' => 'iPhone 6S', 'brand' => 'Apple'],
                ['name' => 'Galaxy S7', 'brand' => 'Samsung'],
            ],
            $products->values()->toArray()
        );
    }

    /**
     * @depends testToArray
     */
    public function testFlip(): void
    {
        $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);
        $flipped = $collection->flip();
        self::assertEquals(['taylor' => 'name', 'laravel' => 'framework'], $flipped->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testForget(): void
    {
        $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);
        $collection->forget('name');
        self::assertEquals(['framework' => 'laravel'], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testForPage(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $chunk = $collection->forPage(2, 3);
        self::assertEquals([4, 5, 6], $chunk->toArray());

        $chunk = $collection->forPage(1, 3);
        self::assertEquals([1, 2, 3], $chunk->toArray());

        $chunk = $collection->forPage(-1, 3);
        self::assertEquals([7, 8, 9], $chunk->toArray());

        $chunk = $collection->forPage(0, 3);
        self::assertEquals([1, 2, 3], $chunk->toArray());
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

        $grouped = $collection->groupBy(function ($item) {
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
        }], true);

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
        $collection = new Collection([
            'serial' => 'UX301', 'type' => 'screen', 'year' => 2009,
        ]);

        $intersect = $collection->intersectByKeys([
            'reference' => 'UX404', 'type' => 'tab', 'year' => 2011,
        ]);

        self::assertEquals(['type' => 'screen', 'year' => 2009], $intersect->toArray());
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
        $collection = new Collection(['a', 'b', 'c']);
        self::assertEquals('a, b, c', $collection->join(', '));

        $collection = new Collection(['a', 'b', 'c']);
        self::assertEquals('a, b, and c', $collection->join(', ', ', and '));

        $collection = new Collection(['a', 'b']);
        self::assertEquals('a and b', $collection->join(', ', ' and '));

        $collection = new Collection(['a']);
        self::assertEquals('a', $collection->join(', ', ' and '));

        $collection = new Collection([]);
        self::assertEquals('', $collection->join(', ', ' and '));
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
        $last = $collection->last(function ($value) {
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
        $multiplied = $collection->map(function ($item) {
            return $item * 2;
        });

        self::assertEquals([1, 2, 3, 4, 5], $collection->all());
        self::assertEquals([2, 4, 6, 8, 10], $multiplied->all());
    }

    /**
     * @depends testToArray
     */
    public function testMapInto(): void
    {
        $collection = new Collection(['USD', 'EUR', 'GBP']);
        $currencies = $collection->mapInto(Currency::class);
        self::assertEquals([new Currency('USD'), new Currency('EUR'), new Currency('GBP')], $currencies->toArray());
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
        $collection = new Collection([
            ['foo' => 10],
            ['foo' => 10],
            ['foo' => 20],
            ['foo' => 40]
        ]);
        self::assertEquals(15, $collection->median('foo'));

        $collection = new Collection([1, 1, 2, 4]);
        self::assertEquals(1.5, $collection->median());
    }

    /**
     * @depends testToArray
     */
    public function testMerge(): void
    {
        $collection = new Collection(['product_id' => 1, 'price' => 100]);
        $merged = $collection->merge(['price' => 200, 'discount' => false]);
        self::assertEquals(['product_id' => 1, 'price' => 200, 'discount' => false], $merged->toArray());

        $collection = new Collection(['Desk', 'Chair']);
        $merged = $collection->merge(['Bookcase', 'Door']);
        self::assertEquals(['Desk', 'Chair', 'Bookcase', 'Door'], $merged->toArray());
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
        $collection = new Collection([['foo' => 10], ['foo' => 20]]);
        self::assertEquals(10, $collection->min('foo'));

        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertEquals(1, $collection->min());
    }

    /**
     * @depends testToArray
     */
    public function testMode(): void
    {
        $collection = new Collection([
            ['foo' => 10],
            ['foo' => 10],
            ['foo' => 20],
            ['foo' => 40]
        ]);
        self::assertEquals([10], $collection->mode()->toArray());

        $collection = new Collection([1, 1, 2, 4]);
        self::assertEquals([1], $collection->mode()->toArray());

        $collection = new Collection([1, 1, 2, 2]);
        self::assertEquals([1, 2], $collection->mode()->toArray());
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

    /**
     * @depends testToArray
     */
    public function testOnly(): void
    {
        $collection = new Collection([
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 100,
            'discount' => false
        ]);
        $filtered = $collection->only(['product_id', 'name']);
        self::assertEquals(['product_id' => 1, 'name' => 'Desk'], $filtered->toArray());
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

    /**
     * @depends testToArray
     */
    public function testPartition(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6]);

        [$underThree, $equalOrAboveThree] = $collection->partition(function ($i) {
            return $i < 3;
        });

        self::assertEquals([1, 2], $underThree->toArray());
        self::assertEquals([3, 4, 5, 6], $equalOrAboveThree->toArray());
    }

    /**
     * @depends testSum
     */
    public function testPipe(): void
    {
        $collection = new Collection([1, 2, 3]);
        $piped = $collection->pipe(function (Collection $collection): int|float {
            return $collection->sum();
        });
        self::assertEquals(6, $piped);
    }

    /**
     * @depends testToArray
     */
    public function testPipeInto(): void
    {
        $collection = new Collection([1, 2, 3]);
        $resource = $collection->pipeInto(ResourceCollection::class);
        $resource->collection->toArray();

        self::assertEquals([1, 2, 3], $resource->collection->toArray());
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
     * @depends testToArray
     */
    public function testPush(): void
    {
        $collection = new Collection([1, 2, 3, 4]);
        $collection->push(5);

        self::assertEquals([1, 2, 3, 4, 5], $collection->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testPut(): void
    {
        $collection = new Collection(['product_id' => 1, 'name' => 'Desk']);
        $collection->put('price', 100);
        self::assertEquals(['product_id' => 1, 'name' => 'Desk', 'price' => 100], $collection->toArray());
    }

    /**
     *
     */
    public function testRandom(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        self::assertEquals(2, $collection->random(2)->count());
        self::assertIsInt($collection->random());
    }

    public function testReduce(): void
    {
        $collection = new Collection([1, 2, 3]);

        $total = $collection->reduce(function ($carry, $item) {
            return $carry + $item;
        });
        self::assertEquals(6, $total);

        $total = $collection->reduce(function ($carry, $item) {
            return $carry + $item;
        }, 4);
        self::assertEquals(10, $total);

        $collection = new Collection([
            'usd' => 1400,
            'gbp' => 1200,
            'eur' => 1000,
        ]);

        $ratio = [
            'usd' => 1,
            'gbp' => 1.37,
            'eur' => 1.22,
        ];

        $total = $collection->reduce(function ($carry, $value, $key) use ($ratio) {
            return $carry + ($value * $ratio[$key]);
        });
        self::assertEquals(4264, $total);
    }

    /**
     * @depends testToArray
     */
    public function testReject(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        $filtered = $collection->reject(function ($value, $key) {
            return $value > 2;
        });

        self::assertEquals([1, 2], $filtered->toArray());
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

    /**
     * @depends testToArray
     */
    public function testReplaceRecursive(): void
    {
        $collection = new Collection([
            'Taylor',
            'Abigail',
            [
                'James',
                'Victoria',
                'Finn'
            ]
        ]);

        $replaced = $collection->replaceRecursive([
            'Charlie',
            2 => [1 => 'King']
        ]);

        self::assertEquals(['Charlie', 'Abigail', ['James', 'King', 'Finn']], $replaced->toArray());
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
        self::assertEquals(2, $collection->search(function ($item) {
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
        $collection = new Collection([1, 2, 3, 4]);

        self::assertEquals(3, $collection->sole(function ($value, $key) {
            return $value === 2;
        }));

        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
        ]);

        self::assertEquals(['product' => 'Chair', 'price' => 100], $collection->sole('product', 'Chair'));

        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
        ]);

        self::assertEquals(['product' => 'Desk', 'price' => 200], $collection->sole());
    }

    public function testSome(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        self::assertFalse($collection->some(function ($value) {
            return $value > 5;
        }));

        $collection = new Collection(['name' => 'Desk', 'price' => 100]);
        self::assertTrue($collection->some('Desk'));
        self::assertFalse($collection->some('New York'));

        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
        ]);

        self::assertFalse($collection->some('product', 'Bookcase'));
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

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testSortBy(): void
    {
        $collection = new Collection([
            ['name' => 'Desk', 'price' => 200],
            ['name' => 'Chair', 'price' => 100],
            ['name' => 'Bookcase', 'price' => 150],
        ]);
        $sorted = $collection->sortBy('price');
        self::assertEquals(
            [
                ['name' => 'Chair', 'price' => 100],
                ['name' => 'Bookcase', 'price' => 150],
                ['name' => 'Desk', 'price' => 200],
            ],
            $sorted->values()->toArray()
        );

        $collection = new Collection([
            ['title' => 'Item 1'],
            ['title' => 'Item 12'],
            ['title' => 'Item 3'],
        ]);
        $sorted = $collection->sortBy('title', SORT_NATURAL);
        self::assertEquals(
            [
                ['title' => 'Item 1'],
                ['title' => 'Item 3'],
                ['title' => 'Item 12'],
            ],
            $sorted->values()->toArray()
        );

        $collection = new Collection([
            ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
            ['name' => 'Chair', 'colors' => ['Black']],
            ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
        ]);
        $sorted = $collection->sortBy(function ($product, $key) {
            return count($product['colors']);
        });
        self::assertEquals(
            [
                ['name' => 'Chair', 'colors' => ['Black']],
                ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
                ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
            ],
            $sorted->values()->toArray()
        );

        $collection = new Collection([
            ['name' => 'Taylor Otwell', 'age' => 34],
            ['name' => 'Abigail Otwell', 'age' => 30],
            ['name' => 'Taylor Otwell', 'age' => 36],
            ['name' => 'Abigail Otwell', 'age' => 32],
        ]);
        $sorted = $collection->sortBy([
            ['name', 'asc'],
            ['age', 'desc'],
        ]);
        self::assertEquals(
            [
                ['name' => 'Abigail Otwell', 'age' => 32],
                ['name' => 'Abigail Otwell', 'age' => 30],
                ['name' => 'Taylor Otwell', 'age' => 36],
                ['name' => 'Taylor Otwell', 'age' => 34],
            ],
            $sorted->values()->toArray()
        );

        $collection = new Collection([
            ['name' => 'Taylor Otwell', 'age' => 34],
            ['name' => 'Abigail Otwell', 'age' => 30],
            ['name' => 'Taylor Otwell', 'age' => 36],
            ['name' => 'Abigail Otwell', 'age' => 32],
        ]);
        $sorted = $collection->sortBy([
            fn($a, $b) => $a['name'] <=> $b['name'],
            fn($a, $b) => $b['age'] <=> $a['age'],
        ]);
        self::assertEquals(
            [
                ['name' => 'Abigail Otwell', 'age' => 32],
                ['name' => 'Abigail Otwell', 'age' => 30],
                ['name' => 'Taylor Otwell', 'age' => 36],
                ['name' => 'Taylor Otwell', 'age' => 34],
            ],
            $sorted->values()->toArray()
        );
    }

    public function testSortByDesc(): void
    {

    }

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testSortDesc(): void
    {
        $collection = new Collection([5, 3, 1, 2, 4]);
        $sorted = $collection->sortDesc();
        self::assertEquals([5, 4, 3, 2, 1], $sorted->values()->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testSortKeys(): void
    {
        $collection = new Collection([
            'id' => 22345,
            'first' => 'John',
            'last' => 'Doe',
        ]);
        $sorted = $collection->sortKeys();
        self::assertEquals(['first' => 'John', 'id' => 22345, 'last' => 'Doe'], $sorted->toArray());
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

    /**
     * @depends testToArray
     */
    public function testSplitIn(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $groups = $collection->splitIn(3);
        self::assertEquals([[1, 2, 3, 4], [5, 6, 7, 8], [9, 10]], $groups->toArray());
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

        $collection->transform(function ($item) {
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
        self::assertSame([1, 2, 3, 4], $unique->values()->toArray());

        $collection = new Collection([1, '1', '2', 2, 3, 4, 2]);
        $unique = $collection->unique();
        self::assertSame([1, '2', 3, 4], $unique->values()->toArray());
    }

    /**
     * @depends testToArray
     * @depends testValues
     */
    public function testUniqueStrict(): void
    {
        $collection = new Collection([1, 1, 2, 2, 3, 4, 2]);
        $unique = $collection->uniqueStrict();
        self::assertSame([1, 2, 3, 4], $unique->values()->toArray());

        $collection = new Collection([1, '1', '2', 2, 3, 4, 2]);
        $unique = $collection->uniqueStrict();
        self::assertSame([1, '1', '2', 2, 3, 4], $unique->values()->toArray());
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
        self::assertEquals(['John Doe'], Collection::unwrap(new Collection(['John Doe'])));
        self::assertEquals(['John Doe'], Collection::unwrap(['John Doe']));
        self::assertEquals('John Doe', Collection::unwrap('John Doe'));
        self::assertEquals(1, Collection::unwrap(1));
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

    /**
     * @depends testToArray
     */
    public function testWhereIn(): void
    {
        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Door', 'price' => 100],
        ]);

        $filtered = $collection->whereIn('price', [150, 200]);

        self::assertEquals(
            [
                ['product' => 'Desk', 'price' => 200],
                ['product' => 'Bookcase', 'price' => 150],
            ],
            $filtered->toArray()
        );
    }

    public function testWhereInStrict(): void
    {

    }

    public function testWhereInstanceOf(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testWhereNotBetween(): void
    {
        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 80],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Pencil', 'price' => 30],
            ['product' => 'Door', 'price' => 100],
        ]);

        $filtered = $collection->whereNotBetween('price', 100, 200);

        self::assertEquals(
            [
                ['product' => 'Chair', 'price' => 80],
                ['product' => 'Pencil', 'price' => 30],
            ],
            $filtered->toArray()
        );
    }

    /**
     * @depends testToArray
     */
    public function testWhereNotIn(): void
    {
        $collection = new Collection([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Door', 'price' => 100],
        ]);

        $filtered = $collection->whereNotIn('price', [150, 200]);

        self::assertEquals(
            [
                ['product' => 'Chair', 'price' => 100],
                ['product' => 'Door', 'price' => 100]
            ],
            $filtered->toArray()
        );
    }

    public function testWhereNotInStrict(): void
    {

    }

    /**
     * @depends testToArray
     */
    public function testWhereNotNull(): void
    {
        $collection = new Collection([
            ['name' => 'Desk'],
            ['name' => null],
            ['name' => 'Bookcase'],
        ]);
        $filtered = $collection->whereNotNull('name');
        self::assertEquals([['name' => 'Desk'], ['name' => 'Bookcase']], $filtered->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testWhereNull(): void
    {
        $collection = new Collection([
            ['name' => 'Desk'],
            ['name' => null],
            ['name' => 'Bookcase'],
        ]);
        $filtered = $collection->whereNull('name');
        self::assertEquals([['name' => null]], $filtered->toArray());
    }

    /**
     * @depends testToArray
     */
    public function testWrap(): void
    {
        $collection = Collection::wrap('John Doe');
        self::assertEquals(['John Doe'], $collection->toArray());

        $collection = Collection::wrap(1);
        self::assertEquals([1], $collection->toArray());

        $collection = Collection::wrap(['John Doe']);
        self::assertEquals(['John Doe'], $collection->toArray());

        $collection = Collection::wrap($collection);
        self::assertEquals(['John Doe'], $collection->toArray());
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

    /**
     * @noinspection PhpPureAttributeCanBeAddedInspection
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

    /**
     * @noinspection PhpPureAttributeCanBeAddedInspection
     */
    public function toArrayDataProvider(): array
    {
        return [
            [new Collection([1, 2, 3]), [1, 2, 3]],
            [new Collection([new Collection([1, 2, 3]), new Collection([1, 2, 3])]), [[1, 2, 3], [1, 2, 3]]],
            [new Collection(['product_id' => 'prod-100', 'name' => 'Desk']), ['product_id' => 'prod-100', 'name' => 'Desk']],
            [new Collection(['product_id' => ['prod-100'], 'name' => ['Desk']]), ['product_id' => ['prod-100'], 'name' => ['Desk']]],
        ];
    }

    /**
     * @noinspection PhpPureAttributeCanBeAddedInspection
     */
    public function avgDataProvider(): array
    {
        return [
            [new Collection([1, 1, 2, 4]), 2, null],
            [new Collection([['foo' => 10], ['foo' => 10], ['foo' => 20], ['foo' => 40]]), 20, 'foo'],
        ];
    }
}
