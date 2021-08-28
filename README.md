# Collections

* [**Introduction**](#introduction)
    * [Creating Collections](#creating_collections)
    * [Extending Collections](#extending_collections)
* [**Available Methods**](#available_methods)
* [**Higher Order Messages**](#higher_order_messages)

### <a name="introduction">#</a> Introduction

### <a name="creating_collections">#</a> Creating Collections

### <a name="extending_collections">#</a> Extending Collections

### <a name="available_methods">#</a> Available Methods

For the majority of the remaining collection documentation, we'll discuss each method available on the Collection class. 
Remember, all of these methods may be chained to fluently manipulate the underlying array. 
Furthermore, almost every method returns a new Collection instance, 
allowing you to preserve the original copy of the collection when necessary

[all](#all)

[average](#average)

[avg](#avg)

[chunk](#chunk)

[chunkWhile](#chunkWhile)

[collapse](#collapse)

[collect](#collect)

[combine](#combine)

[concat](#concat)

[contains](#contains)

[containsStrict](#containsStrict)

[count](#count)

[countBy](#countBy)

[crossJoin](#crossJoin)

[dd](#dd)

[diff](#diff)

[diffAssoc](#diffAssoc)

[diffKeys](#diffKeys)

[dump](#dump)

[duplicates](#duplicates)

[duplicatesStrict](#duplicatesStrict)

[each](#each)

[eachSpread](#eachSpread)

[every](#every)

[except](#except)

[filter](#filter)

[first](#first)

[firstWhere](#firstWhere)

[flatMap](#flatMap)

[flatten](#flatten)

[flip](#flip)

[forget](#forget)

[forPage](#forPage)

[get](#get)

[groupBy](#groupBy)

[has](#has)

[implode](#implode)

[intersect](#intersect)

[intersectByKeys](#intersectByKeys)

[isEmpty](#isEmpty)

[isNotEmpty](#isNotEmpty)

[join](#join)

[keyBy](#keyBy)

[keys](#keys)

[last](#last)

[macro](#macro)

[make](#make)

[map](#map)

[mapInto](#mapInto)

[mapSpread](#mapSpread)

[mapToGroups](#mapToGroups)

[mapWithKeys](#mapWithKeys)

[max](#max)

[median](#median)

[merge](#merge)

[mergeRecursive](#mergeRecursive)

[min](#min)

[mode](#mode)

[nth](#nth)

[only](#only)

[pad](#pad)

[partition](#partition)

[pipe](#pipe)

[pipeInto](#pipeInto)

[pluck](#pluck)

[pop](#pop)

[prepend](#prepend)

[pull](#pull)

[push](#push)

[put](#put)

[random](#random)

[reduce](#reduce)

[reject](#reject)

[replace](#replace)

[replaceRecursive](#replaceRecursive)

[reverse](#reverse)

[search](#search)

[shift](#shift)

[shuffle](#shuffle)

[sliding](#sliding)

[skip](#skip)

[skipUntil](#skipUntil)

[skipWhile](#skipWhile)

[slice](#slice)

[sole](#sole)

[some](#some)

[sort](#sort)

[sortBy](#sortBy)

[sortByDesc](#sortByDesc)

[sortDesc](#sortDesc)

[sortKeys](#sortKeys)

[sortKeysDesc](#sortKeysDesc)

[splice](#splice)

[split](#split)

[splitIn](#splitIn)

[sum](#sum)

[take](#take)

[takeUntil](#takeUntil)

[takeWhile](#takeWhile)

[tap](#tap)

[times](#times)

[toArray](#toArray)

[toJson](#toJson)

[transform](#transform)

[union](#union)

[unique](#unique)

[uniqueStrict](#uniqueStrict)

[unless](#unless)

[unlessEmpty](#unlessEmpty)

[unlessNotEmpty](#unlessNotEmpty)

[unwrap](#unwrap)

[values](#values)

[when](#when)

[whenEmpty](#whenEmpty)

[whenNotEmpty](#whenNotEmpty)

[where](#where)

[whereStrict](#whereStrict)

[whereBetween](#whereBetween)

[whereIn](#whereIn)

[whereInStrict](#whereInStrict)

[whereInstanceOf](#whereInstanceOf)

[whereNotBetween](#whereNotBetween)

[whereNotIn](#whereNotIn)

[whereNotInStrict](#whereNotInStrict)

[whereNotNull](#whereNotNull)

[whereNull](#whereNull)

[wrap](#wrap)

[zip](#zip)


### <a name="higher_order_messages"></a>Higher Order Messages


### Method Listing

#### <a name="all">#</a> all
The **all** method returns the underlying array represented by the collection:
```php
use Upside\Collection;
$collection = new Collection();
$collection->collect([1, 2, 3])->all();

// [1, 2, 3]
```

#### <a name="average">#</a> average
Alias for the **avg** method.

#### <a name="avg">#</a> avg
The **avg** method returns the [average value](https://en.wikipedia.org/wiki/Average) of a given key:
```php
use Upside\Collection;
$collection = new Collection([
    ['foo' => 10],
    ['foo' => 10],
    ['foo' => 20],
    ['foo' => 40]
]);
$average = $collection->avg('foo');

// 20

$collection = new Collection([1, 1, 2, 4]);
$average = $collection->avg();

// 2
```

#### <a name="chunk">#</a> chunk
```php
use Upside\Collection;
```

#### <a name="chunkWhile">#</a> chunkWhile
```php
use Upside\Collection;
```

#### <a name="collapse">#</a> collapse
```php
use Upside\Collection;
```

#### <a name="collect">#</a> collect
```php
use Upside\Collection;
```

#### <a name="combine">#</a> combine
```php
use Upside\Collection;
```

#### <a name="concat">#</a> concat
```php
use Upside\Collection;
```

#### <a name="contains">#</a> contains
```php
use Upside\Collection;
```

#### <a name="containsStrict">#</a> containsStrict
```php
use Upside\Collection;
```

#### <a name="count">#</a> count
```php
use Upside\Collection;
```

#### <a name="countBy">#</a> countBy
```php
use Upside\Collection;
```

#### <a name="crossJoin">#</a> crossJoin
```php
use Upside\Collection;
```

#### <a name="dd">#</a> dd
```php
use Upside\Collection;
```

#### <a name="diff">#</a> diff
```php
use Upside\Collection;
```

#### <a name="diffAssoc">#</a> diffAssoc
```php
use Upside\Collection;
```

#### <a name="diffKeys">#</a> diffKeys
```php
use Upside\Collection;
```

#### <a name="dump">#</a> dump
```php
use Upside\Collection;
```

#### <a name="duplicates">#</a> duplicates
```php
use Upside\Collection;
```

#### <a name="duplicatesStrict">#</a> duplicatesStrict
```php
use Upside\Collection;
```

#### <a name="each">#</a> each
```php
use Upside\Collection;
```

#### <a name="eachSpread">#</a> eachSpread
```php
use Upside\Collection;
```

#### <a name="every">#</a> every
```php
use Upside\Collection;
```

#### <a name="except">#</a> except
```php
use Upside\Collection;
```

#### <a name="filter">#</a> filter
```php
use Upside\Collection;
```

#### <a name="first">#</a> first
```php
use Upside\Collection;
```

#### <a name="firstWhere">#</a> firstWhere
```php
use Upside\Collection;
```

#### <a name="flatMap">#</a> flatMap
```php
use Upside\Collection;
```

#### <a name="flatten">#</a> flatten
```php
use Upside\Collection;
```

#### <a name="flip">#</a> flip
```php
use Upside\Collection;
```

#### <a name="forget">#</a> forget
```php
use Upside\Collection;
```

#### <a name="forPage">#</a> forPage
```php
use Upside\Collection;
```

#### <a name="get">#</a> get
```php
use Upside\Collection;
```

#### <a name="groupBy">#</a> groupBy
```php
use Upside\Collection;
```

#### <a name="has">#</a> has
```php
use Upside\Collection;
```

#### <a name="implode">#</a> implode
```php
use Upside\Collection;
```

#### <a name="intersect">#</a> intersect
```php
use Upside\Collection;
```

#### <a name="intersectByKeys">#</a> intersectByKeys
```php
use Upside\Collection;
```

#### <a name="isEmpty">#</a> isEmpty
```php
use Upside\Collection;
```

#### <a name="isNotEmpty">#</a> isNotEmpty
```php
use Upside\Collection;
```

#### <a name="join">#</a> join
```php
use Upside\Collection;
```

#### <a name="keyBy">#</a> keyBy
```php
use Upside\Collection;
```

#### <a name="keys">#</a> keys
```php
use Upside\Collection;
```

#### <a name="last">#</a> last
```php
use Upside\Collection;
```

#### <a name="macro">#</a> macro
```php
use Upside\Collection;
```

#### <a name="make">#</a> make
```php
use Upside\Collection;
```

#### <a name="map">#</a> map
```php
use Upside\Collection;
```

#### <a name="mapInto">#</a> mapInto
```php
use Upside\Collection;
```

#### <a name="mapSpread">#</a> mapSpread
```php
use Upside\Collection;
```

#### <a name="mapToGroups">#</a> mapToGroups
```php
use Upside\Collection;
```

#### <a name="mapWithKeys">#</a> mapWithKeys
```php
use Upside\Collection;
```

#### <a name="max">#</a> max
```php
use Upside\Collection;
```

#### <a name="median">#</a> median
```php
use Upside\Collection;
```

#### <a name="merge">#</a> merge
```php
use Upside\Collection;
```

#### <a name="mergeRecursive">#</a> mergeRecursive
```php
use Upside\Collection;
```

#### <a name="min">#</a> min
```php
use Upside\Collection;
```

#### <a name="mode">#</a> mode
```php
use Upside\Collection;
```

#### <a name="nth">#</a> nth
```php
use Upside\Collection;
```

#### <a name="only">#</a> only
```php
use Upside\Collection;
```

#### <a name="pad">#</a> pad
```php
use Upside\Collection;
```

#### <a name="partition">#</a> partition
```php
use Upside\Collection;
```

#### <a name="pipe">#</a> pipe
```php
use Upside\Collection;
```

#### <a name="pipeInto">#</a> pipeInto
```php
use Upside\Collection;
```

#### <a name="pluck">#</a> pluck
```php
use Upside\Collection;
```

#### <a name="pop">#</a> pop
```php
use Upside\Collection;
```

#### <a name="prepend">#</a> prepend
```php
use Upside\Collection;
```

#### <a name="pull">#</a> pull
```php
use Upside\Collection;
```

#### <a name="push">#</a> push
```php
use Upside\Collection;
```

#### <a name="put">#</a> put
```php
use Upside\Collection;
```

#### <a name="random">#</a> random
```php
use Upside\Collection;
```

#### <a name="reduce">#</a> reduce
```php
use Upside\Collection;
```

#### <a name="reject">#</a> reject
```php
use Upside\Collection;
```

#### <a name="replace">#</a> replace
```php
use Upside\Collection;
```

#### <a name="replaceRecursive">#</a> replaceRecursive
```php
use Upside\Collection;
```

#### <a name="reverse">#</a> reverse
```php
use Upside\Collection;
```

#### <a name="search">#</a> search
```php
use Upside\Collection;
```

#### <a name="shift">#</a> shift
```php
use Upside\Collection;
```

#### <a name="shuffle">#</a> shuffle
```php
use Upside\Collection;
```

#### <a name="sliding">#</a> sliding
```php
use Upside\Collection;
```

#### <a name="skip">#</a> skip
```php
use Upside\Collection;
```

#### <a name="skipUntil">#</a> skipUntil
```php
use Upside\Collection;
```

#### <a name="skipWhile">#</a> skipWhile
```php
use Upside\Collection;
```

#### <a name="slice">#</a> slice
```php
use Upside\Collection;
```

#### <a name="sole">#</a> sole
```php
use Upside\Collection;
```

#### <a name="some">#</a> some
```php
use Upside\Collection;
```

#### <a name="sort">#</a> sort
```php
use Upside\Collection;
```

#### <a name="sortBy">#</a> sortBy
```php
use Upside\Collection;
```

#### <a name="sortByDesc">#</a> sortByDesc
```php
use Upside\Collection;
```

#### <a name="sortDesc">#</a> sortDesc
```php
use Upside\Collection;
```

#### <a name="sortKeys">#</a> sortKeys
```php
use Upside\Collection;
```

#### <a name="sortKeysDesc">#</a> sortKeysDesc
```php
use Upside\Collection;
```

#### <a name="splice">#</a> splice
```php
use Upside\Collection;
```

#### <a name="split">#</a> split
```php
use Upside\Collection;
```

#### <a name="splitIn">#</a> splitIn
```php
use Upside\Collection;
```

#### <a name="sum">#</a> sum
```php
use Upside\Collection;
```

#### <a name="take">#</a> take
```php
use Upside\Collection;
```

#### <a name="takeUntil">#</a> takeUntil
```php
use Upside\Collection;
```

#### <a name="takeWhile">#</a> takeWhile
```php
use Upside\Collection;
```

#### <a name="tap">#</a> tap
```php
use Upside\Collection;
```

#### <a name="times">#</a> times
```php
use Upside\Collection;
```

#### <a name="toArray">#</a> toArray
```php
use Upside\Collection;
```

#### <a name="toJson">#</a> toJson
```php
use Upside\Collection;
```

#### <a name="transform">#</a> transform
```php
use Upside\Collection;
```

#### <a name="union">#</a> union
```php
use Upside\Collection;
```

#### <a name="unique">#</a> unique
```php
use Upside\Collection;
```

#### <a name="uniqueStrict">#</a> uniqueStrict
```php
use Upside\Collection;
```

#### <a name="unless">#</a> unless
```php
use Upside\Collection;
```

#### <a name="unlessEmpty">#</a> unlessEmpty
```php
use Upside\Collection;
```

#### <a name="unlessNotEmpty">#</a> unlessNotEmpty
```php
use Upside\Collection;
```

#### <a name="unwrap">#</a> unwrap
```php
use Upside\Collection;
```

#### <a name="values">#</a> values
```php
use Upside\Collection;
```

#### <a name="when">#</a> when
```php
use Upside\Collection;
```

#### <a name="whenEmpty">#</a> whenEmpty
```php
use Upside\Collection;
```

#### <a name="whenNotEmpty">#</a> whenNotEmpty
```php
use Upside\Collection;
```

#### <a name="where">#</a> where
```php
use Upside\Collection;
```

#### <a name="whereStrict">#</a> whereStrict
```php
use Upside\Collection;
```

#### <a name="whereBetween">#</a> whereBetween
```php
use Upside\Collection;
```

#### <a name="whereIn">#</a> whereIn
```php
use Upside\Collection;
```

#### <a name="whereInStrict">#</a> whereInStrict
```php
use Upside\Collection;
```

#### <a name="whereInstanceOf">#</a> whereInstanceOf
```php
use Upside\Collection;
```

#### <a name="whereNotBetween">#</a> whereNotBetween
```php
use Upside\Collection;
```

#### <a name="whereNotIn">#</a> whereNotIn
```php
use Upside\Collection;
```

#### <a name="whereNotInStrict">#</a> whereNotInStrict
```php
use Upside\Collection;
```

#### <a name="whereNotNull">#</a> whereNotNull
```php
use Upside\Collection;
```

#### <a name="whereNull">#</a> whereNull
```php
use Upside\Collection;
```

#### <a name="wrap">#</a> wrap
```php
use Upside\Collection;
```

#### <a name="zip">#</a> zip
```php
use Upside\Collection;
```
