<?php
declare(strict_types=1);

namespace Upside\Collection;

/**
 * @template TValue
 * @template TKey
 */
class Collection implements \ArrayAccess
{
    /**
     * @var array<TKey, TValue>
     */
    private array $items;

    /**
     * @psalm-param array<TKey, TValue> $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * The all method returns the underlying array represented by the collection
     * https://laravel.com/docs/8.x/collections#method-all
     *
     * @return array<TKey, TValue>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * The avg method returns the average value of a given key
     * https://laravel.com/docs/8.x/collections#method-avg
     *
     * @param int|string|callable $key
     * @return float
     */
    public function avg(int|string|callable $key): float
    {
        // TODO: Implement avg() method.
    }

    /**
     * Alias for the avg method.
     * https://laravel.com/docs/8.x/collections#method-average
     *
     * @param int|string|callable $key
     * @return float
     */
    public function average(int|string|callable $key): float
    {
        return $this->avg($key);
    }

    /**
     * The chunk method breaks the collection into multiple, smaller collections of a given size
     * https://laravel.com/docs/8.x/collections#method-chunk
     *
     * @param int $size
     * @return $this
     */
    public function chunk(int $size): static
    {
        $chunks = array_chunk($this->items, $size);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = new static($chunk);
        }

        return new static($result);
    }

    /**
     * The chunkWhile method breaks the collection into multiple, smaller collections
     * based on the evaluation of the given callback. The $chunk variable passed to the
     * closure may be used to inspect the previous element
     * https://laravel.com/docs/8.x/collections#method-chunkwhile
     */
    public function chunkWhile()
    {
        // TODO: Implement chunkWhile() method.
    }


    /**
     * The collapse method collapses a collection of arrays into a single, flat collection
     * https://laravel.com/docs/8.x/collections#method-collapse
     */
    public function collapse()
    {
        // TODO: Implement collapse() method.
    }

    /**
     * The combine method combines the values of the collection, as keys, with the values of another array or collection
     * https://laravel.com/docs/8.x/collections#method-combine
     */
    public function combine()
    {
        // TODO: Implement combine() method.
    }

    /**
     * The collect method returns a new Collection instance with the items currently in the collection
     * https://laravel.com/docs/8.x/collections#method-collect
     */
    public function collect()
    {
        // TODO: Implement collect() method.
    }

    /**
     *  The concat method appends the given array or collection's values onto the end of another collection
     * https://laravel.com/docs/8.x/collections#method-concat
     */
    public function concat()
    {
        // TODO: Implement concat() method.
    }

    /**
     * You may also pass a closure to the contains to determine if an element exists in the collection matching a given truth test
     * https://laravel.com/docs/8.x/collections#method-contains
     */
    public function contains()
    {
        // TODO: Implement contains() method.
    }

    /**
     * This method has the same signature as the contains method; however, all values are compared using "strict" comparisons.
     * https://laravel.com/docs/8.x/collections#method-containsstrict
     */
    public function containsStrict()
    {
        // TODO: Implement containsStrict() method.
    }

    /**
     * The count method returns the total number of items in the collection
     * https://laravel.com/docs/8.x/collections#method-count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * The countBy method counts the occurrences of values in the collection.
     * By default, the method counts the occurrences of every element, allowing you to count certain "types" of elements in the collection
     * https://laravel.com/docs/8.x/collections#method-countBy
     *
     */
    public function countBy()
    {
        // TODO: Implement countBy() method.
    }

    /**
     * The crossJoin method cross joins the collection's values among the given arrays or collections,
     * returning a Cartesian product with all possible permutations
     * https://laravel.com/docs/8.x/collections#method-crossjoin
     *
     */
    public function crossJoin()
    {
        // TODO: Implement crossJoin() method.
    }

    /**
     * The diff method compares the collection against another collection or a plain PHP array based on its values.
     * This method will return the values in the original collection that are not present in the given collection
     * https://laravel.com/docs/8.x/collections#method-diff
     */
    public function diff()
    {
        // TODO: Implement diff() method.
    }

    /**
     * The diffAssoc method compares the collection against another collection or a plain PHP array based on its keys and values.
     * This method will return the key / value pairs in the original collection that are not present in the given collection
     * https://laravel.com/docs/8.x/collections#method-diffassoc
     */
    public function diffAssoc()
    {
        // TODO: Implement diffAssoc() method.
    }

    /**
     * The diffKeys method compares the collection against another collection or a plain PHP array based on its keys.
     * This method will return the key / value pairs in the original collection that are not present in the given collection
     * https://laravel.com/docs/8.x/collections#method-diffkeys
     */
    public function diffKeys()
    {
        // TODO: Implement diffKeys() method.
    }

    /**
     * The duplicates method retrieves and returns duplicate values from the collection
     * https://laravel.com/docs/8.x/collections#method-duplicates
     */
    public function duplicates()
    {
        // TODO: Implement duplicates() method.
    }

    /**
     * This method has the same signature as the duplicates method; however, all values are compared using "strict" comparisons.
     * https://laravel.com/docs/8.x/collections#method-duplicatesstrict
     */
    public function duplicatesStrict()
    {
        // TODO: Implement duplicatesStrict() method.
    }

    /**
     * The each method iterates over the items in the collection and passes each item to a closure
     * https://laravel.com/docs/8.x/collections#method-each
     */
    public function each()
    {
        // TODO: Implement each() method.
    }

    /**
     * The eachSpread method iterates over the collection's items, passing each nested item value into the given callback
     * https://laravel.com/docs/8.x/collections#method-eachspread
     */
    public function eachSpread()
    {
        // TODO: Implement eachSpread() method.
    }

    /**
     * The every method may be used to verify that all elements of a collection pass a given truth test
     * https://laravel.com/docs/8.x/collections#method-every
     */
    public function every()
    {
        // TODO: Implement every() method.
    }

    /**
     * The except method returns all items in the collection except for those with the specified keys
     * https://laravel.com/docs/8.x/collections#method-except
     */
    public function except()
    {
        // TODO: Implement except() method.
    }

    /**
     * The filter method filters the collection using the given callback, keeping only those items that pass a given truth test
     * https://laravel.com/docs/8.x/collections#method-filter
     */
    public function filter()
    {
        // TODO: Implement filter() method.
    }

    /**
     * The first method returns the first element in the collection that passes a given truth test
     * https://laravel.com/docs/8.x/collections#method-first
     */
    public function first()
    {
        // TODO: Implement first() method.
    }

    /**
     * The firstWhere method returns the first element in the collection with the given key / value pair
     * https://laravel.com/docs/8.x/collections#method-first-where
     */
    public function firstWhere()
    {
        // TODO: Implement firstWhere() method.
    }

    /**
     * The flatMap method iterates through the collection and passes each value to the given closure.
     * The closure is free to modify the item and return it, thus forming a new collection of modified items.
     * Then, the array is flattened by one level
     * https://laravel.com/docs/8.x/collections#method-flatmap
     */
    public function flatMap()
    {
        // TODO: Implement flatMap() method.
    }

    /**
     * The flatten method flattens a multi-dimensional collection into a single dimension
     * https://laravel.com/docs/8.x/collections#method-flatten
     */
    public function flatten()
    {
        // TODO: Implement flatten() method.
    }

    /**
     * The flip method swaps the collection's keys with their corresponding values
     * https://laravel.com/docs/8.x/collections#method-flip
     */
    public function flip()
    {
        // TODO: Implement flip() method.
    }

    /**
     * The forget method removes an item from the collection by its key
     * https://laravel.com/docs/8.x/collections#method-forget
     */
    public function forget()
    {
        // TODO: Implement forget() method.
    }

    /**
     * The forPage method returns a new collection containing the items that would be present on a given page number.
     * The method accepts the page number as its first argument and the number of items to show per page as its second argument
     * https://laravel.com/docs/8.x/collections#method-forpage
     */
    public function forPage()
    {
        // TODO: Implement forPage() method.
    }

    /**
     * The get method returns the item at a given key. If the key does not exist, null is returned
     * https://laravel.com/docs/8.x/collections#method-get
     */
    public function get()
    {
        // TODO: Implement get() method.
    }

    /**
     * The groupBy method groups the collection's items by a given key
     * https://laravel.com/docs/8.x/collections#method-groupby
     */
    public function groupBy()
    {
        // TODO: Implement groupBy() method.
    }

    /**
     * The has method determines if a given key exists in the collection
     * https://laravel.com/docs/8.x/collections#method-has
     */
    public function has()
    {
        // TODO: Implement has() method.
    }

    /**
     * The implode method joins items in a collection.
     * Its arguments depend on the type of items in the collection.
     * If the collection contains arrays or objects, you should pass the key of the attributes you wish to join, and
     * the "glue" string you wish to place between the values
     */
    public function implode()
    {
        // TODO: Implement implode() method.
    }

    /**
     * The intersect method removes any values from the original collection that are not present in the given array or collection.
     * The resulting collection will preserve the original collection's keys
     * https://laravel.com/docs/8.x/collections#method-intersect
     */
    public function intersect()
    {
        // TODO: Implement intersect() method.
    }

    /**
     * The intersectByKeys method removes any keys and their corresponding values from the original collection
     * that are not present in the given array or collection
     * https://laravel.com/docs/8.x/collections#method-intersectbykeys
     */
    public function intersectByKeys()
    {
        // TODO: Implement intersectByKeys() method.
    }

    /**
     * The isEmpty method returns true if the collection is empty; otherwise, false is returned
     * https://laravel.com/docs/8.x/collections#method-isempty
     */
    public function isEmpty()
    {
        // TODO: Implement isEmpty() method.
    }

    /**
     * The isNotEmpty method returns true if the collection is not empty; otherwise, false is returned
     * https://laravel.com/docs/8.x/collections#method-isnotempty
     */
    public function isNotEmpty()
    {
        // TODO: Implement isNotEmpty() method.
    }

    /**
     * The join method joins the collection's values with a string.
     * Using this method's second argument, you may also specify how the final element should be appended to the string
     * https://laravel.com/docs/8.x/collections#method-join
     */
    public function join()
    {
        // TODO: Implement join() method.
    }

    /**
     * The keyBy method keys the collection by the given key.
     * If multiple items have the same key, only the last one will appear in the new collection
     * https://laravel.com/docs/8.x/collections#method-keyby
     *
     * @param int|string|callable $key
     * @return static
     */
    public function keyBy(int|string|callable $key): static
    {
        $result = [];

        $retriever = $this->valueRetriever($key);
        foreach ($this->items as $item) {
            $result[$retriever($item)] = $item;
        }

        return new static($result);
    }

    /**
     * The keys method returns all of the collection's keys
     * https://laravel.com/docs/8.x/collections#method-keys
     */
    public function keys()
    {
        // TODO: Implement keys() method.
    }

    /**
     * The last method returns the last element in the collection that passes a given truth test
     * https://laravel.com/docs/8.x/collections#method-last
     */
    public function last()
    {
        // TODO: Implement last() method.
    }

    /**
     * The map method iterates through the collection and passes each value to the given callback.
     * The callback is free to modify the item and return it, thus forming a new collection of modified items
     * https://laravel.com/docs/8.x/collections#method-map
     */
    public function map()
    {
        // TODO: Implement map() method.
    }

    /**
     * The mapInto() method iterates over the collection,
     * creating a new instance of the given class by passing the value into the constructor
     * https://laravel.com/docs/8.x/collections#method-mapinto
     */
    public function mapInto()
    {
        // TODO: Implement mapInto() method.
    }

    /**
     * The mapSpread method iterates over the collection's items, passing each nested item value into the given closure.
     * The closure is free to modify the item and return it, thus forming a new collection of modified items
     * https://laravel.com/docs/8.x/collections#method-mapspread
     */
    public function mapSpread()
    {
        // TODO: Implement mapSpread() method.
    }

    /**
     * The mapToGroups method groups the collection's items by the given closure.
     * The closure should return an associative array containing a single
     * key / value pair, thus forming a new collection of grouped values
     * https://laravel.com/docs/8.x/collections#method-maptogroups
     */
    public function mapToGroups()
    {
        // TODO: Implement mapToGroups() method.
    }

    /**
     * The mapWithKeys method iterates through the collection and passes each value to the given callback.
     * The callback should return an associative array containing a single key / value pair
     * https://laravel.com/docs/8.x/collections#method-mapwithkeys
     */
    public function mapWithKeys()
    {
        // TODO: Implement mapWithKeys() method.
    }

    /**
     * The max method returns the maximum value of a given key
     * https://laravel.com/docs/8.x/collections#method-max
     */
    public function max()
    {
        // TODO: Implement max() method.
    }

    /**
     * The median method returns the median value of a given key
     * https://laravel.com/docs/8.x/collections#method-median
     */
    public function median()
    {
        // TODO: Implement median() method.
    }

    /**
     * The merge method merges the given array or collection with the original collection.
     * If a string key in the given items matches a string key in the original collection,
     * the given items's value will overwrite the value in the original collection
     * https://laravel.com/docs/8.x/collections#method-merge
     */
    public function merge()
    {
        // TODO: Implement merge() method.
    }

    /**
     * The mergeRecursive method merges the given array or collection recursively with the original collection.
     * If a string key in the given items matches a string key in the original collection,
     * then the values for these keys are merged together into an array, and this is done recursively
     * https://laravel.com/docs/8.x/collections#method-mergerecursive
     */
    public function mergeRecursive()
    {
        // TODO: Implement mergeRecursive() method.
    }

    /**
     * The min method returns the minimum value of a given key
     * https://laravel.com/docs/8.x/collections#method-min
     */
    public function min()
    {
        // TODO: Implement min() method.
    }

    /**
     * The mode method returns the mode value of a given key
     * https://laravel.com/docs/8.x/collections#method-mode
     */
    public function mode()
    {
        // TODO: Implement mode() method.
    }

    /**
     * The nth method creates a new collection consisting of every n-th element
     * https://laravel.com/docs/8.x/collections#method-nth
     */
    public function nth()
    {
        // TODO: Implement nth() method.
    }

    /**
     * The only method returns the items in the collection with the specified keys
     * https://laravel.com/docs/8.x/collections#method-only
     */
    public function only()
    {
        // TODO: Implement only() method.
    }

    /**
     * The pad method will fill the array with the given value until the array reaches the specified size.
     * This method behaves like the array_pad PHP function.
     *
     * To pad to the left, you should specify a negative size.
     * No padding will take place if the absolute value of the given size is less than or equal to the length of the array
     */
    public function pad()
    {
        // TODO: Implement pad() method.
    }

    /**
     * The partition method may be combined with PHP array destructuring to separate elements that pass a given truth test from those that do not
     * https://laravel.com/docs/8.x/collections#method-partition
     */
    public function partition()
    {
        // TODO: Implement partition() method.
    }

    /**
     * The pipe method passes the collection to the given closure and returns the result of the executed closure
     * https://laravel.com/docs/8.x/collections#method-pipe
     */
    public function pipe()
    {
        // TODO: Implement pipe() method.
    }

    /**
     * The pipeInto method creates a new instance of the given class and passes the collection into the constructor
     * https://laravel.com/docs/8.x/collections#method-pipeinto
     */
    public function pipeInto()
    {
        // TODO: Implement pipeInto() method.
    }

    /**
     * The pluck method retrieves all of the values for a given key
     * https://laravel.com/docs/8.x/collections#method-pluck
     */
    public function pluck()
    {
        // TODO: Implement pluck() method.
    }

    /**
     * The pop method removes and returns the last item from the collection
     * https://laravel.com/docs/8.x/collections#method-pop
     */
    public function pop()
    {
        // TODO: Implement pop() method.
    }

    /**
     * The prepend method adds an item to the beginning of the collection
     * https://laravel.com/docs/8.x/collections#method-prepend
     */
    public function prepend()
    {
        // TODO: Implement prepend() method.
    }

    /**
     * The pull method removes and returns an item from the collection by its key
     * https://laravel.com/docs/8.x/collections#method-pull
     */
    public function pull()
    {
        // TODO: Implement pull() method.
    }


    /**
     * The push method appends an item to the end of the collection
     * https://laravel.com/docs/8.x/collections#method-push
     *
     * @param TValue $item
     * @return $this
     */
    public function push(mixed $item): static
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * The put method sets the given key and value in the collection
     * https://laravel.com/docs/8.x/collections#method-put
     */
    public function put()
    {
        // TODO: Implement put() method.
    }

    /**
     * The random method returns a random item from the collection
     * https://laravel.com/docs/8.x/collections#method-random
     */
    public function random()
    {
        // TODO: Implement random() method.
    }

    /**
     * The reduce method reduces the collection to a single value,
     * passing the result of each iteration into the subsequent iteration
     * https://laravel.com/docs/8.x/collections#method-reduce
     */
    public function reduce()
    {
        // TODO: Implement reduce() method.
    }

    /**
     * The reject method filters the collection using the given closure.
     * The closure should return true if the item should be removed from the resulting collection
     * https://laravel.com/docs/8.x/collections#method-reject
     */
    public function reject()
    {
        // TODO: Implement reject() method.
    }

    /**
     * The replace method behaves similarly to merge; however,
     * in addition to overwriting matching items that have string keys,
     * the replace method will also overwrite items in the collection that have matching numeric keys
     * https://laravel.com/docs/8.x/collections#method-replace
     */
    public function replace()
    {
        // TODO: Implement replace() method.
    }

    /**
     * This method works like replace, but it will recur into arrays and apply the same replacement process to the inner values
     * https://laravel.com/docs/8.x/collections#method-replacerecursive
     */
    public function replaceRecursive()
    {
        // TODO: Implement replaceRecursive() method.
    }

    /**
     * The reverse method reverses the order of the collection's items, preserving the original keys
     * https://laravel.com/docs/8.x/collections#method-reverse
     */
    public function reverse()
    {
        // TODO: Implement reverse() method.
    }

    /**
     * The search method searches the collection for the given value and returns its key if found.
     * If the item is not found, false is returned
     * https://laravel.com/docs/8.x/collections#method-search
     */
    public function search()
    {
        // TODO: Implement search() method.
    }

    /**
     * The shift method removes and returns the first item from the collection
     * https://laravel.com/docs/8.x/collections#method-shift
     */
    public function shift()
    {
        // TODO: Implement shift() method.
    }

    /**
     * The shuffle method randomly shuffles the items in the collection
     * https://laravel.com/docs/8.x/collections#method-shuffle
     */
    public function shuffle()
    {
        // TODO: Implement shuffle() method.
    }

    /**
     * The skip method returns a new collection, with the given number of elements removed from the beginning of the collection
     * https://laravel.com/docs/8.x/collections#method-skip
     */
    public function skip()
    {
        // TODO: Implement skip() method.
    }

    /**
     * The skipUntil method skips over items from the collection until the given callback returns true and
     * then returns the remaining items in the collection as a new collection instance
     * https://laravel.com/docs/8.x/collections#method-skipuntil
     */
    public function skipUntil()
    {
        // TODO: Implement skipUntil() method.
    }

    /**
     * The skipWhile method skips over items from the collection while the given callback returns true and
     * then returns the remaining items in the collection as a new collection
     * https://laravel.com/docs/8.x/collections#method-skipwhile
     */
    public function skipWhile()
    {
        // TODO: Implement skipWhile() method.
    }

    /**
     * The slice method returns a slice of the collection starting at the given index
     * https://laravel.com/docs/8.x/collections#method-slice
     */
    public function slice()
    {
        // TODO: Implement slice() method.
    }

    /**
     * The sole method returns the first element in the collection that passes a given truth test,
     * but only if the truth test matches exactly one element
     * https://laravel.com/docs/8.x/collections#method-sole
     */
    public function sole()
    {
        // TODO: Implement sole() method.
    }

    /**
     * Alias for the contains method.
     * https://laravel.com/docs/8.x/collections#method-some
     */
    public function some()
    {
        return $this->contains();
    }

    /**
     * The sort method sorts the collection.
     * The sorted collection keeps the original array keys,
     * so in the following example we will use the values method to reset the keys to consecutively numbered indexes
     * https://laravel.com/docs/8.x/collections#method-sort
     */
    public function sort()
    {
        // TODO: Implement sort() method.
    }

    /**
     * The sortBy method sorts the collection by the given key.
     * The sorted collection keeps the original array keys,
     * so in the following example we will use the values method to reset the keys to consecutively numbered indexes
     * https://laravel.com/docs/8.x/collections#method-sortby
     */
    public function sortBy()
    {
        // TODO: Implement sortBy() method.
    }

    /**
     * This method has the same signature as the sortBy method, but will sort the collection in the opposite order.
     */
    public function sortByDesc()
    {
        // TODO: Implement sortByDesc() method.
    }

    /**
     * This method will sort the collection in the opposite order as the sort method
     * https://laravel.com/docs/8.x/collections#method-sortdesc
     */
    public function sortDesc()
    {
        // TODO: Implement sortDesc() method.
    }

    /**
     * The sortKeys method sorts the collection by the keys of the underlying associative array
     * https://laravel.com/docs/8.x/collections#method-sortkeys
     */
    public function sortKeys()
    {
        // TODO: Implement sortKeys() method.
    }

    /**
     * This method has the same signature as the sortKeys method, but will sort the collection in the opposite order.
     * https://laravel.com/docs/8.x/collections#method-sortkeysdesc
     */
    public function sortKeysDesc()
    {
        // TODO: Implement sortKeysDesc() method.
    }

    /**
     * The splice method removes and returns a slice of items starting at the specified index
     * https://laravel.com/docs/8.x/collections#method-splice
     */
    public function splice()
    {
        // TODO: Implement splice() method.
    }

    /**
     * The split method breaks a collection into the given number of groups
     * https://laravel.com/docs/8.x/collections#method-split
     */
    public function split()
    {
        // TODO: Implement split() method.
    }

    /**
     * The splitIn method breaks a collection into the given number of groups,
     * filling non-terminal groups completely before allocating the remainder to the final group
     * https://laravel.com/docs/8.x/collections#method-splitin
     */
    public function splitIn()
    {
        // TODO: Implement splitIn() method.
    }

    /**
     * The sum method returns the sum of all items in the collection
     * https://laravel.com/docs/8.x/collections#method-sum
     */
    public function sum()
    {
        // TODO: Implement sum() method.
    }

    /**
     * The take method returns a new collection with the specified number of items
     * https://laravel.com/docs/8.x/collections#method-take
     */
    public function take()
    {
        // TODO: Implement take() method.
    }

    /**
     * The takeUntil method returns items in the collection until the given callback returns true
     * https://laravel.com/docs/8.x/collections#method-takeuntil
     */
    public function takeUntil()
    {
        // TODO: Implement takeUntil() method.
    }

    /**
     * The takeWhile method returns items in the collection until the given callback returns false
     * https://laravel.com/docs/8.x/collections#method-takewhile
     */
    public function takeWhile()
    {
        // TODO: Implement takeWhile() method.
    }

    /**
     * The tap method passes the collection to the given callback, allowing you to "tap" into the collection at a specific point
     * and do something with the items while not affecting the collection itself.
     * The collection is then returned by the tap method
     * https://laravel.com/docs/8.x/collections#method-tap
     */
    public function tap()
    {
        // TODO: Implement tap() method.
    }

    /**
     * The static times method creates a new collection by invoking the given closure a specified number of times
     * https://laravel.com/docs/8.x/collections#method-times
     */
    public static function times()
    {
        // TODO: Implement times() method.
    }

    /**
     * The toArray method converts the collection into a plain PHP array.
     * If the collection's values are Eloquent models, the models will also be converted to arrays
     * https://laravel.com/docs/8.x/collections#method-toarray
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * The toJson method converts the collection into a JSON serialized string
     * https://laravel.com/docs/8.x/collections#method-tojson
     */
    public function toJson()
    {
        // TODO: Implement toJson() method.
    }

    /**
     * The transform method iterates over the collection and calls the given callback with each item in the collection.
     * The items in the collection will be replaced by the values returned by the callback
     * https://laravel.com/docs/8.x/collections#method-transform
     */
    public function transform()
    {
        // TODO: Implement transform() method.
    }

    /**
     * The union method adds the given array to the collection.
     * If the given array contains keys that are already in the original collection,
     * the original collection's values will be preferred
     * https://laravel.com/docs/8.x/collections#method-union
     */
    public function union()
    {
        // TODO: Implement union() method.
    }

    /**
     * The unique method returns all of the unique items in the collection.
     * The returned collection keeps the original array keys, so in the following example
     * we will use the values method to reset the keys to consecutively numbered indexes
     * https://laravel.com/docs/8.x/collections#method-unique
     */
    public function unique()
    {
        // TODO: Implement unique() method.
    }

    /**
     * This method has the same signature as the unique method; however, all values are compared using "strict" comparisons.
     * https://laravel.com/docs/8.x/collections#method-uniquestrict
     */
    public function uniqueStrict()
    {
        // TODO: Implement uniqueStrict() method.
    }

    /**
     * The unless method will execute the given callback unless the first argument given to the method evaluates to true
     * https://laravel.com/docs/8.x/collections#method-unless
     */
    public function unless()
    {
        // TODO: Implement unless() method.
    }

    /**
     * Alias for the whenNotEmpty method.
     * https://laravel.com/docs/8.x/collections#method-unlessempty
     */
    public function unlessEmpty()
    {
        return $this->whenNotEmpty();
    }

    /**
     * Alias for the whenEmpty method.
     * https://laravel.com/docs/8.x/collections#method-unlessnotempty
     */
    public function unlessNotEmpty()
    {
        return $this->whenEmpty();
    }

    /**
     * The static unwrap method returns the collection's underlying items from the given value when applicable
     * https://laravel.com/docs/8.x/collections#method-unwrap
     */
    public function unwrap()
    {
        // TODO: Implement unwrap() method.
    }

    /**
     * The values method returns a new collection with the keys reset to consecutive integers
     * https://laravel.com/docs/8.x/collections#method-values
     */
    public function values()
    {
        // TODO: Implement values() method.
    }

    /**
     * The when method will execute the given callback when the first argument given to the method evaluates to true
     * https://laravel.com/docs/8.x/collections#method-when
     */
    public function when()
    {
        // TODO: Implement when() method.
    }

    /**
     * The whenEmpty method will execute the given callback when the collection is empty
     * https://laravel.com/docs/8.x/collections#method-whenempty
     */
    public function whenEmpty()
    {
        // TODO: Implement whenEmpty() method.
    }

    /**
     * The whenNotEmpty method will execute the given callback when the collection is not empty
     * https://laravel.com/docs/8.x/collections#method-whennotempty
     */
    public function whenNotEmpty()
    {
        // TODO: Implement whenNotEmpty() method.
    }

    /**
     * The where method filters the collection by a given key / value pair
     * https://laravel.com/docs/8.x/collections#method-where
     */
    public function where()
    {
        // TODO: Implement where() method.
    }

    /**
     * This method has the same signature as the where method; however, all values are compared using "strict" comparisons.
     * https://laravel.com/docs/8.x/collections#method-wherestrict
     */
    public function whereStrict()
    {
        // TODO: Implement whereStrict() method.
    }

    /**
     * The whereBetween method filters the collection by determining if a specified item value is within a given range
     * https://laravel.com/docs/8.x/collections#method-wherebetween
     */
    public function whereBetween()
    {
        // TODO: Implement whereBetween() method.
    }

    /**
     * The whereIn method removes elements from the collection that do not have a specified item value that is contained within the given array
     * https://laravel.com/docs/8.x/collections#method-wherein
     */
    public function whereIn()
    {
        // TODO: Implement whereIn() method.
    }

    /**
     * This method has the same signature as the whereIn method; however, all values are compared using "strict" comparisons.
     * https://laravel.com/docs/8.x/collections#method-whereinstrict
     */
    public function whereInStrict()
    {
        // TODO: Implement whereInStrict() method.
    }

    /**
     * The whereInstanceOf method filters the collection by a given class type
     * https://laravel.com/docs/8.x/collections#method-whereinstanceof
     */
    public function whereInstanceOf()
    {
        // TODO: Implement whereInstanceOf() method.
    }

    /**
     * The whereNotBetween method filters the collection by determining if a specified item value is outside of a given range
     * https://laravel.com/docs/8.x/collections#method-wherenotbetween
     */
    public function whereNotBetween()
    {
        // TODO: Implement whereNotBetween() method.
    }

    /**
     * The whereNotIn method removes elements from the collection that have a specified item value that is not contained within the given array
     * https://laravel.com/docs/8.x/collections#method-wherenotin
     */
    public function whereNotIn()
    {
        // TODO: Implement whereNotIn() method.
    }

    /**
     * This method has the same signature as the whereNotIn method; however, all values are compared using "strict" comparisons.
     * https://laravel.com/docs/8.x/collections#method-wherenotinstrict
     */
    public function whereNotInStrict()
    {
        // TODO: Implement whereNotInStrict() method.
    }

    /**
     * The whereNotNull method returns items from the collection where the given key is not null
     * https://laravel.com/docs/8.x/collections#method-wherenotnull
     */
    public function whereNotNull()
    {
        // TODO: Implement whereNotNull() method.
    }

    /**
     * The whereNull method returns items from the collection where the given key is null
     * https://laravel.com/docs/8.x/collections#method-wherenull
     */
    public function whereNull()
    {
        // TODO: Implement whereNull() method.
    }

    /**
     * The static wrap method wraps the given value in a collection when applicable
     * https://laravel.com/docs/8.x/collections#method-wrap
     */
    public function wrap()
    {
        // TODO: Implement wrap() method.
    }

    /**
     * The zip method merges together the values of the given array with the values of the original collection at their corresponding index
     * https://laravel.com/docs/8.x/collections#method-zip
     */
    public function zip()
    {
        // TODO: Implement zip() method.
    }


    private function valueRetriever(int|string|callable $value): callable
    {
        if (is_callable($value)) {
            return $value;
        }

        return static function ($item) use ($value) {
            return is_object($item) ? $item->{$value} : $item[$value];
        };

    }

    /**
     * @param TKey $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param TKey $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * @param TKey $offset
     * @param TValue $value
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param TKey $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }
}
