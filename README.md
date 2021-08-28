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
#### <a name="avg">#</a> avg
#### <a name="chunk">#</a> chunk
#### <a name="chunkWhile">#</a> chunkWhile
#### <a name="collapse">#</a> collapse
#### <a name="collect">#</a> collect
#### <a name="combine">#</a> combine
#### <a name="concat">#</a> concat
#### <a name="contains">#</a> contains
#### <a name="containsStrict">#</a> containsStrict
#### <a name="count">#</a> count
#### <a name="countBy">#</a> countBy
#### <a name="crossJoin">#</a> crossJoin
#### <a name="dd">#</a> dd
#### <a name="diff">#</a> diff
#### <a name="diffAssoc">#</a> diffAssoc
#### <a name="diffKeys">#</a> diffKeys
#### <a name="dump">#</a> dump
#### <a name="duplicates">#</a> duplicates
#### <a name="duplicatesStrict">#</a> duplicatesStrict
#### <a name="each">#</a> each
#### <a name="eachSpread">#</a> eachSpread
#### <a name="every">#</a> every
#### <a name="except">#</a> except
#### <a name="filter">#</a> filter
#### <a name="first">#</a> first
#### <a name="firstWhere">#</a> firstWhere
#### <a name="flatMap">#</a> flatMap
#### <a name="flatten">#</a> flatten
#### <a name="flip">#</a> flip
#### <a name="forget">#</a> forget
#### <a name="forPage">#</a> forPage
#### <a name="get">#</a> get
#### <a name="groupBy">#</a> groupBy
#### <a name="has">#</a> has
#### <a name="implode">#</a> implode
#### <a name="intersect">#</a> intersect
#### <a name="intersectByKeys">#</a> intersectByKeys
#### <a name="isEmpty">#</a> isEmpty
#### <a name="isNotEmpty">#</a> isNotEmpty
#### <a name="join">#</a> join
#### <a name="keyBy">#</a> keyBy
#### <a name="keys">#</a> keys
#### <a name="last">#</a> last
#### <a name="macro">#</a> macro
#### <a name="make">#</a> make
#### <a name="map">#</a> map
#### <a name="mapInto">#</a> mapInto
#### <a name="mapSpread">#</a> mapSpread
#### <a name="mapToGroups">#</a> mapToGroups
#### <a name="mapWithKeys">#</a> mapWithKeys
#### <a name="max">#</a> max
#### <a name="median">#</a> median
#### <a name="merge">#</a> merge
#### <a name="mergeRecursive">#</a> mergeRecursive
#### <a name="min">#</a> min
#### <a name="mode">#</a> mode
#### <a name="nth">#</a> nth
#### <a name="only">#</a> only
#### <a name="pad">#</a> pad
#### <a name="partition">#</a> partition
#### <a name="pipe">#</a> pipe
#### <a name="pipeInto">#</a> pipeInto
#### <a name="pluck">#</a> pluck
#### <a name="pop">#</a> pop
#### <a name="prepend">#</a> prepend
#### <a name="pull">#</a> pull
#### <a name="push">#</a> push
#### <a name="put">#</a> put
#### <a name="random">#</a> random
#### <a name="reduce">#</a> reduce
#### <a name="reject">#</a> reject
#### <a name="replace">#</a> replace
#### <a name="replaceRecursive">#</a> replaceRecursive
#### <a name="reverse">#</a> reverse
#### <a name="search">#</a> search
#### <a name="shift">#</a> shift
#### <a name="shuffle">#</a> shuffle
#### <a name="sliding">#</a> sliding
#### <a name="skip">#</a> skip
#### <a name="skipUntil">#</a> skipUntil
#### <a name="skipWhile">#</a> skipWhile
#### <a name="slice">#</a> slice
#### <a name="sole">#</a> sole
#### <a name="some">#</a> some
#### <a name="sort">#</a> sort
#### <a name="sortBy">#</a> sortBy
#### <a name="sortByDesc">#</a> sortByDesc
#### <a name="sortDesc">#</a> sortDesc
#### <a name="sortKeys">#</a> sortKeys
#### <a name="sortKeysDesc">#</a> sortKeysDesc
#### <a name="splice">#</a> splice
#### <a name="split">#</a> split
#### <a name="splitIn">#</a> splitIn
#### <a name="sum">#</a> sum
#### <a name="take">#</a> take
#### <a name="takeUntil">#</a> takeUntil
#### <a name="takeWhile">#</a> takeWhile
#### <a name="tap">#</a> tap
#### <a name="times">#</a> times
#### <a name="toArray">#</a> toArray
#### <a name="toJson">#</a> toJson
#### <a name="transform">#</a> transform
#### <a name="union">#</a> union
#### <a name="unique">#</a> unique
#### <a name="uniqueStrict">#</a> uniqueStrict
#### <a name="unless">#</a> unless
#### <a name="unlessEmpty">#</a> unlessEmpty
#### <a name="unlessNotEmpty">#</a> unlessNotEmpty
#### <a name="unwrap">#</a> unwrap
#### <a name="values">#</a> values
#### <a name="when">#</a> when
#### <a name="whenEmpty">#</a> whenEmpty
#### <a name="whenNotEmpty">#</a> whenNotEmpty
#### <a name="where">#</a> where
#### <a name="whereStrict">#</a> whereStrict
#### <a name="whereBetween">#</a> whereBetween
#### <a name="whereIn">#</a> whereIn
#### <a name="whereInStrict">#</a> whereInStrict
#### <a name="whereInstanceOf">#</a> whereInstanceOf
#### <a name="whereNotBetween">#</a> whereNotBetween
#### <a name="whereNotIn">#</a> whereNotIn
#### <a name="whereNotInStrict">#</a> whereNotInStrict
#### <a name="whereNotNull">#</a> whereNotNull
#### <a name="whereNull">#</a> whereNull
#### <a name="wrap">#</a> wrap
#### <a name="zip">#</a> zip
