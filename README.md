jitsu/array
-----------

The `Jitsu\ArrayUtil` class is a collection of static methods for dealing
with arrays in PHP.

Why? Because many of PHP's built-in array functions have confusing names and
awkward interfaces.

`ArrayUtil` addresses these issues, providing a more intuitive interface to
the standard PHP array functions while offering capabilities which are lacking
in the built-in library.

## Installation

```sh
composer require jitsu/array
```

## Testing

Run the unit test suite as follows:

```sh
composer install
./vendor/bin/phpunit test/
```

## API

### class Jitsu\\ArrayUtil

A collection of static methods for dealing with arrays.

#### ArrayUtil::size($array)

Return the number of key-value pairs in an array.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `int` |

#### ArrayUtil::length($array)

Alias of `size`. See `\jitsu\ArrayUtil::size()`.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `int` |

#### ArrayUtil::isEmpty($array)

Return whether an array is empty.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `bool` |

#### ArrayUtil::get($array, $key, $default = null)

Get an array element or a default value.

Retrieves the value stored under a key in an array, or a default
value if the key does not exist.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$key`** | `int|string` |
| **`$default`** | `mixed` |
| returns | `mixed` |

#### ArrayUtil::hasKey($array, $key)

Return whether an array contains a certain key.

Unlike `isset`, this works even if the value is `null`.

For integer keys, testing with the string equivalent will still
result in `true`.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$key`** | `int|string` |
| returns | `bool` |

#### ArrayUtil::normalizeKey($k)

Normalize an arbitrary string or integer to its PHP array key
equivalent.

PHP arrays normalize their keys by converting all strings of decimal
digits without superfluous leading 0's to their integer equivalents.
Integers are always left alone. The important thing to remember is
that inserting a key into an array may or may not change its type,
which has ramifications for performing strict comparisons on that
key if it is retrieved from the array later. For example:

    $a = array();
    $k = '123';
    $a[$k] = 456;
    $keys = array_keys($a);
    echo ($keys[0] === $k); // suprisingly, this is false

In such a situation, `normalizeKey` can be used to ensure that an
arbitrary string value can be compared strictly.

    echo ($keys[0] === \Jitsu\ArrayUtil::normalizeKey($k)); // true

|   | Type |
|---|------|
| **`$k`** | `int|string` |
| returns | `int|string` |

#### ArrayUtil::remove(&$array, $key)

Remove a key from an array.

It is not an error to remove a non-existent key.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$key`** | `int|string` |

#### ArrayUtil::keys($array)

List all of the keys in an array.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| returns | `(int|string)[]` | A sequential array. |

#### ArrayUtil::values($array)

List the values in an array as a sequential array.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `array` |

#### ArrayUtil::listValues($array, $keys, $default = null)

Get the values in an array under a given list of keys, in like
order.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$keys`** | `(int|string)[]` |  |
| **`$default`** | `mixed` | Default value used for missing keys. |
| returns | `array` |  |

#### ArrayUtil::requireValues($array, $keys)

Get the values is an array under a given list of mandatory keys, in
like order.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$keys`** | `(int|string)[]` |  |
| returns | `array` |  |
| throws | `\RuntimeException` | Thrown if the array is missing any of the given keys. |

#### ArrayUtil::first($array)

Get the first element in a sequential array.

Returns `null` if the array is empty.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `mixed|null` |

#### ArrayUtil::last($array)

Get the last element in a sequential array.

Returns `null` if the array is empty.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `mixed|null` |

#### ArrayUtil::append(&$array, $value)

Append a value to the end of a sequential array.

Note that this is equivalent to the more succinct
`$array[] = $value`.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$value`** | `mixed` |

#### ArrayUtil::appendMany(&$array1, $array2)

Append a list of values to an array.

|   | Type | Description |
|---|------|-------------|
| **`$array1`** | `array` | The array being modified. |
| **`$array2`** | `array` | The list of values to append. |

#### ArrayUtil::concat($array1, $array2)

Concatenate two sequential arrays.

This does *not* work as expected on associative arrays.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| returns | `array` |

#### ArrayUtil::push(&$array, $value)

Alias of `append`. See `\jitsu\ArrayUtil::append()`.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$value`** | `mixed` |

#### ArrayUtil::pop(&$array)

Pop a value off the end of an array.

Returns `null` if the array was empty.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `mixed|null` |

#### ArrayUtil::shift(&$array)

Shift an element off the beginning of an array.

Returns `null` if the array was empty. Re-indexes sequential
arrays.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `mixed|null` |

#### ArrayUtil::unshift(&$array, $value)

Prepend a value to the beginning of an array.

Re-indexes sequential arrays.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$value`** | `mixed` |

#### ArrayUtil::keyOf($array, $value)

Look up a key by its value using linear search.

Returns the first key whose value is strictly equal to the given
value. Returns `null` if the value does not exist in the array.
Works for both associative and sequential arrays.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$value`** | `mixed` |
| returns | `int|string|null` |

#### ArrayUtil::indexOf($array, $value)

See `\jitsu\ArrayUtil::keyOf()`.

#### ArrayUtil::keysOf($array, $value)

Find all keys in an array which map to a certain value.

Returns a sequential array of all keys in the array whose values
are strictly equal to the given value. Uses linear search.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$value`** | `mixed` |
| returns | `(int|string)[]` |

#### ArrayUtil::contains($array, $value)

Determine whether an array contains a value using linear search.

Uses strict comparison.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$value`** | `mixed` |
| returns | `bool` |

#### ArrayUtil::at($array, $i)

Get the value at a certain position in an array.

Note that this is completely different from looking up a value by
key. Rather, this looks up a value by its offset according to the
array's internal ordering of key-value pairs.

This discussion of "internal ordering" might require some
explanation. Despite its name, the `array` type in PHP really
implements an ordered dictionary data structure. All arrays, both
sequential and associative, record a mapping of keys to values, as
well as the order in which those key-value pairs were inserted. A
sequential array is just an array whose key values coincide exactly
with their ordering. This ordering determines the order in which
a `foreach` loop iterates over an array's elements, among other
behaviors. This function taps into that internal ordering and looks
up elements by position in constant time.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$i`** | `int` |  |
| returns | `mixed|null` | Returns `null` if the index was out of range. |

#### ArrayUtil::pairAt($array, $i)

Get the key-value pair at a certain position in an array.

See `\jitsu\ArrayUtil::at()`.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$i`** | `int` |  |
| returns | `array|null` | The pair `array($key, $value)`. |

#### ArrayUtil::keyAt($array, $i)

Get the key at a certain position in an array.

See `\jitsu\ArrayUtil::at()`.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$i`** | `int` |
| returns | `int|string|null` |

#### ArrayUtil::slice($array, $i, $j = null)

Get a slice of an array.

Negative indices indicate an offset from the end of the array.

Slices according to the array's ordering. Slices of sequential
arrays are re-indexed.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$i`** | `int` | The starting index. |
| **`$j`** | `int|null` | One past the last index, or `null` if all the rest of the array should be used. |
| returns | `array` |  |

#### ArrayUtil::pairSlice($array, $i, $j = null)

Get a slice of an array while preserving numeric keys.

Like `\jitsu\ArrayUtil::slice()`, but preserves keys even for
sequential arrays.

See `\jitsu\ArrayUtil::slice()`.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$i`** | `int` |
| **`$j`** | `int|null` |
| returns | `array` |

#### ArrayUtil::assignSlice(&$array, $sub, $i, $j = null)

Replace a slice of an array with a list of values.

See `\jitsu\ArrayUtil::slice()`.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$i`** | `int` |  |
| **`$j`** | `int|null` |  |
| **`$sub`** | `array` |  |
| returns | `array` | The replaced slice. |

#### ArrayUtil::removeSlice(&$array, $i, $j = null)

Remove a slice from an array.

See `\jitsu\ArrayUtil::slice()`.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$i`** | `int` |  |
| **`$j`** | `int|null` |  |
| returns | `array` | The removed slice. |

#### ArrayUtil::reverse($array)

Get a reversed and re-indexed copy of a sequential array.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `array` |

#### ArrayUtil::reversePairs($array)

Get a copy of an array with the order of its key-value pairs
reversed.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `array` |

#### ArrayUtil::range($i, $j = null, $step = 1)

Generate a sequential array consisting of a range of numbers.

A step size may optionally be specified.

If all arguments are either integers or not specified, then the
result will contain integers in the range [`$i`, `$j`), where the
end bound is non-inclusive.

Otherwise, the result will contain numbers in the range [`$i`, `$j`]
inclusive.

If only the first argument is given, then it acts as the ending
index, and an offset of 0 and step size of 1 are assumed.

Any of the arguments may be negative. An empty list will be returned
if the beginning of the interval occurs after its end. It is an
error to use a step size of 0. A step size larger than the size of
the interval may be used.

|   | Type | Description |
|---|------|-------------|
| **`$i`** | `int|float` | If this is the only argument, then this is the end of the range, and the start is implicitly 0. Otherwise, this is the starting index. |
| **`$j`** | `int|float` | The ending index. Non-inclusive in integer mode, inclusive otherwise. |
| **`$step`** | `int|float` | An optional step size. A step size of 0 causes an error. A step size larger than the size of the interval is valid. |
| returns | `(int|float)[]` |  |

#### ArrayUtil::fromPairs($pairs)

Construct an associative array from a list of key-value pairs.

|   | Type | Description |
|---|------|-------------|
| **`An`** | `array[]` | array of pairs of the form `array($key, $value)`. |
| returns | `array` |  |

#### ArrayUtil::fromLists($keys, $values)

Construct an associative array from separate arrays of keys and
values.

|   | Type |
|---|------|
| **`$keys`** | `(int|string)[]` |
| **`$values`** | `array` |
| returns | `array` |

#### ArrayUtil::toSet($array, $value = true)

Hash a list of values into the keys of an associative array.

This returns an associative array mapping the values of `$array`
to `true`. This structure can be used to test membership of elements
efficiently, like a set.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `(int|string)[]` | The list of values. Naturally, the elements may only be integers or strings. |
| **`$value`** | `mixed` | An optional value to use other than `true`. |
| returns | `true[]` |  |

#### ArrayUtil::fill($value, $n)

Generate a sequential array of `$n` copies of `$value`.

|   | Type |
|---|------|
| **`$value`** | `mixed` |
| **`$n`** | `int` |
| returns | `array` |

#### ArrayUtil::pad($array, $value, $n)

Pad a sequential array with copies of `$value`.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$value`** | `mixed` |  |
| **`$n`** | `int` | The length to which the array should be padded. The sign of `$n` determines whether the array is padded at the beginning or the end. |
| returns | `array` | The padded array. |

#### ArrayUtil::pluck($arrays, $key)

Given a list of arrays, list all of the values under a certain key.

Returns a sequential array of all the values under a certain key in
a list of arrays. Whenever that key is missing from an array, that
array is simply skipped.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$key`** | `int|string` |
| returns | `array` |

#### ArrayUtil::pick($array, $keys)

Select a portion of an array with a list of keys.

Returns all of the key-value pairs in an array with the listed keys.
The result is returned as an associative array whose ordering
reflects the ordering of the keys listed. Keys which do not exist
are omitted.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$keys`** | `(int|string)[]` |
| returns | `array` |

#### ArrayUtil::getPick($array, $keys, $default = null)

Like `pick`, but use a default value for missing keys.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$keys`** | `(int|string)[]` |  |
| **`$default`** | `mixed` | The default value to use as the value for missing keys. |
| returns | `array` |  |

#### ArrayUtil::invert($array)

Invert an array's key-value pairs.

Inverts an array so that its values become the keys and vice-versa.
Of course, it is an error to try to invert an array containing 
on-integer or non-string values.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `(int|string)[]` |  |
| returns | `An` | inverted copy of the array. |

#### ArrayUtil::extend($array1, $array2)

Combine the key-value pairs of two arrays into one.

The values of the second array take precedence over those in the
first.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| returns | `array` |

#### ArrayUtil::deepExtend($array1, $array2)

Recursively combine two array structures.

The values of the second array structure take precedence over those
in the first.

See `\jitsu\ArrayUtil::extend()`.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| returns | `array` |

#### ArrayUtil::chunks($array, $n)

Split an array's values into chunks of a certain size.

Returns a sequential array containing the chunks of values as
sequential arrays. The last chunk may have fewer than `$n`
elements. Splits according to ordering. Does not preserve
associative array keys but instead always reindexes each chunk.

|   | Type |
|---|------|
| **`$array`** | `array` |
| **`$n`** | `int` |
| returns | `array[]` |

#### ArrayUtil::map($array, $callback)

Apply a function to the an array's values to generate a new array.

Keys are preserved.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$callback`** | `callable` | Called with each of the array's values. |
| returns | `array` |  |

#### ArrayUtil::filter($array, $callback = null)

Filter an array's values by a predicate to generate a new array.

Preserves keys (does not reindex sequential arrays).

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$callback`** | `callable` | Called with each of the array's values and should return `bool`. If not given, filters all truthy values. |
| returns | `array` |  |

#### ArrayUtil::filterPairs($array, $callback)

Filter an array's key-value pairs by a predicate to generate a new
array.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$callback`** | `callable` | Called with the arguments `$key` and `$value` and should return `bool`. |
| returns | `array` |  |

#### ArrayUtil::sum($array)

Take the sum of an array's values.

|   | Type |
|---|------|
| **`$array`** | `(int|float)[]` |
| returns | `int|float` |

#### ArrayUtil::product($array)

Take the product of an array's values.

|   | Type |
|---|------|
| **`$array`** | `(int|float)[]` |
| returns | `int|float` |

#### ArrayUtil::reduce($array, $callback, $initial = null)

Reduce an array's values using a binary function.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$callback`** | `callable` | A function which accepts two arguments: the running "total" and the next value in the array. |
| **`$initial`** | `mixed` | An optional initial value, which is `null` by default. |
| returns | `mixed` |  |

#### ArrayUtil::apply(&$array, $callback)

Call a callback on each element of an array.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$callback`** | `callback` | A function which accepts an array value as its first argument and optionally the associated key as the second. |

#### ArrayUtil::traverseLeaves(&$array, $callback)

Perform an in-order traversal of a nested array structure.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$callback`** | `callback` | A function which accepts the arguments `$key` and `$value`. The callback may modify the array's contents in-place. |

#### ArrayUtil::difference($array1, $array2, $key\_cmp = null, $value\_cmp = true)

Get key-value pairs which exist in one array but not in another.

Returns an associative array containing all key-value pairs which
exist in the first array but not in the second according to some
uniqueness criteria determined by the values passed as `$key_cmp`
and `$value_cmp`. Both `$key_cmp` and `$value_cmp` may be `null`,
`true`, or a comparison callback and are used to compare the keys
and values of array elements, respectively. If a comparator is
`null`, its component is ignored in the comparison. If a comparator
is `true`, then the default string comparison method is used for
that component. Otherwise, a callback implementing an arbitrary
comparison function may be used. The default is to ignore keys and
compare values by string comparison.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| **`$key_cmp`** | `callable|true|null` |
| **`$value_cmp`** | `callable|true|null` |
| returns | `array` |

#### ArrayUtil::pairDifference($array1, $array2, $key\_cmp = null, $value\_cmp = null)

Get key-value pairs which exist in one array but not in another,
where both keys and values determine uniqueness.

Returns an associative array containing all key-value pairs which
exist in the first array but not in the second. Optionally provide
comparison functions for the keys and values. String comparison is
used by default.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| **`$key_cmp`** | `callable|null` |
| **`$value_cmp`** | `callable|null` |
| returns | `array` |

#### ArrayUtil::keyDifference($array1, $array2, $key\_cmp = null)

Get key-value pairs which exist in one array but not in another,
where keys alone determine uniqueness.

Returns an associative array containing all key-value pairs in the
first array whose keys do not exist in the second. Optionally
provide a comparison function for the keys. String comparison is
used by default.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| **`$key_cmp`** | `callable|null` |
| returns | `array` |

#### ArrayUtil::valueDifference($array1, $array2, $value\_cmp = null)

Get key-value pairs which exist in one array but not in another,
where values alone determine uniqueness.

Returns an associative array containing all key-value pairs in the
first array whose values do not exist in the second. Optionally
provide a comparison function for values. Uses string comparison by
default.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| **`$value_cmp`** | `callable|null` |
| returns | `array` |

#### ArrayUtil::pairIntersection($array1, $array2, $key\_cmp = null, $value\_cmp = null)

Get key-value pairs which exist in both of two arrays.

Returns an associative array whose key-value pairs exist in both
arrays. Uses string comparison for values by default. Optionally
provide comparison functions for keys and values.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| **`$key_cmp`** | `callable|null` |
| **`$value_cmp`** | `callable|null` |
| returns | `array` |

#### ArrayUtil::keyIntersection($array1, $array2, $key\_cmp = null)

Get key-value pairs which exist in both of two arrays, where keys
alone determine uniqueness.

Returns an associative array containing all key-value pairs in the
first array whose keys exist in the second. Optionally provide a
key comparison function.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| **`$key_cmp`** | `callable|null` |
| returns | `array` |

#### ArrayUtil::valueIntersection($array1, $array2, $value\_cmp = null)

Get key-value pairs which exist in both of two arrays, where values
alone determine uniqueness.

Returns an associative array containing all key-value pairs in the
first array whose values exist in the second. Uses string
comparison by default. Optionally provide a comparison function.

|   | Type |
|---|------|
| **`$array1`** | `array` |
| **`$array2`** | `array` |
| **`$value_cmp`** | `callable|null` |
| returns | `array` |

#### ArrayUtil::uniqueValues($array)

Get all of the unique values in an array, de-duplicated.

Removes all key-value pairs from an array whose values are duplicates
of other values earlier in the array. Comparison is *non-strict*.
Keys are preserved.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `array` |

#### ArrayUtil::hasOnlyKeys($array, $keys, &$unexpected = null)

Determine whether an array only contains keys in a given list.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$keys`** | `(int|string)[]` |  |
| **`$unexpected`** | `array|null` | An optional argument which collects the unexpected keys found. |
| returns | `bool` |  |

#### ArrayUtil::hasKeys($array, $keys, &$missing = null)

Determine whether an array contains all keys in a given list.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$keys`** | `(int|string)[]` |  |
| **`$missing`** | `array|null` | An optional argument which collects the missing keys not found. |
| returns | `bool` |  |

#### ArrayUtil::hasExactKeys($array, $keys, &$unexpected = null, &$missing = null)

Determine whether an array contains exactly the keys in a given
list.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$keys`** | `(int|string)[]` |  |
| **`$unexpected`** | `array|null` | An optional argument which collects the unexpected keys found. |
| **`$missing`** | `array|null` | An optional argument which collects the missing keys not found. |
| returns | `bool` |  |

#### ArrayUtil::randomKey($array)

Pick a random key from an array.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `int|string` |

#### ArrayUtil::randomValue($array)

Pick a random value from an array.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `mixed` |

#### ArrayUtil::randomPair($array)

Pick a random key-value pair from an array.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| returns | `array` | The pair `array($key, $value)`. |

#### ArrayUtil::randomKeys($array, $n)

Pick `$n` random keys from an array.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$n`** | `int` |  |
| returns | `array` | A sequential array. |

#### ArrayUtil::shuffle(&$array)

Randomly shuffle and re-index the values of an array in-place.

|   | Type |
|---|------|
| **`$array`** | `array` |

#### ArrayUtil::sort(&$array, $value\_cmp = null)

Sort and re-index the values of an array in-place.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$value_cmp`** | `callable|null` | An optional comparison function. |

#### ArrayUtil::reverseSort(&$array)

Sort and re-index the values of an array in-place, in reverse.

|   | Type |
|---|------|
| **`$array`** | `array` |

#### ArrayUtil::localeSort(&$array)

Sort and re-index the values of an array of strings in-place, based
on the rules of the current locale.

|   | Type |
|---|------|
| **`$array`** | `array` |

#### ArrayUtil::sortPairs(&$array, $value\_cmp = null)

Sort the key-value pairs of an array in-place based on their values.

Like `sort` but preserves keys.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$value_cmp`** | `callable|null` | An optional comparison function. |

#### ArrayUtil::reverseSortPairs(&$array)

Sort the key-value pairs of an array in-place based on their values,
in reverse order.

|   | Type |
|---|------|
| **`$array`** | `array` |

#### ArrayUtil::sortKeys(&$array, $key\_cmp = null)

Sort the key-value pairs of an array in-place based on their keys.

|   | Type | Description |
|---|------|-------------|
| **`$array`** | `array` |  |
| **`$key_cmp`** | `callable|null` | An optional comparison function. |

#### ArrayUtil::reverseSortKeys(&$array)

Sort the key-value pairs of an array in-place based on their keys,
in reverse order.

|   | Type |
|---|------|
| **`$array`** | `array` |

#### ArrayUtil::humanSortValues(&$array)

Sort the key-value pairs of an array of strings based on their
values in a human-sensible way, in-place.

|   | Type |
|---|------|
| **`$array`** | `array` |

#### ArrayUtil::iHumanSortValues(&$array)

Sort the key-value pairs of an array of strings based on their
values in a human-sensible way, ignoring case, in-place.

|   | Type |
|---|------|
| **`$array`** | `array` |

#### ArrayUtil::lowerKeys($array)

Convert the keys in an array to lower case.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `array` |

#### ArrayUtil::upperKeys($array)

Convert the keys in an array to upper case.

|   | Type |
|---|------|
| **`$array`** | `array` |
| returns | `array` |

#### ArrayUtil::isSequential($array)

Determine if a value is a sequential array.

Checks that the value is an array whose keys coincide exactly with
their ordering.

**Note:** Since the complexity of this function is linear
in the size of the array, its use should be avoided.

|   | Type |
|---|------|
| **`$array`** | `mixed` |
| returns | `bool` |

#### ArrayUtil::isAssociative($array)

Determine if a value is an associative array.

**Note:** Since the complexity of this function is linear
in the size of the array, its use should be avoided.

See `\Jitsu\ArrayUtil::isSequential()`.

|   | Type |
|---|------|
| **`$array`** | `mixed` |
| returns | `bool` |

#### ArrayUtil::looksSequential($array)

Efficiently guess whether a value is a sequential array.

Guesses in constant time whether a value appears to be a sequential
array. This simply checks whether a value is an array whose first
key is 0 or an empty array.

Mainly useful for implementing overloaded functions.

|   | Type |
|---|------|
| **`$array`** | `mixed` |
| returns | `bool` |

#### ArrayUtil::looksAssociative($array)

Efficiently guess whether a value is an associative array.

Guesses in constant time whether a value appears to be an
associative array. This simply checks whether the first key in the
array is not 0. Note that an empty array is considered both
sequential and associative.

Mainly useful for implementing overloaded functions.

|   | Type |
|---|------|
| **`$array`** | `mixed` |
| returns | `bool` |

#### ArrayUtil::countValues($array)

Tally the occurences of each value in an array.

Counts the number of times each value appears in an array and
produces an associative array mapping those values to their
frequencies. Of course, this limits the array values to strings
and integers.

|   | Type |
|---|------|
| **`$array`** | `(int|string)[]` |
| returns | `int[]` |

