<?php

/**
 * Array utility functions.
 */

namespace Jitsu;

/**
 * A collection of static methods for dealing with arrays.
 */
class ArrayUtil {

	/**
	 * Return the number of key-value pairs in an array.
	 *
	 * @param array $array
	 * @return int
	 */
	public static function size($array) {
		return count($array);
	}

	/**
	 * @see \jitsu\ArrayUtil::size() Alias of `size`.
	 *
	 * @param array $array
	 * @return int
	 */
	public static function length($array) {
		return count($array);
	}

	/**
	 * Return whether an array is empty.
	 *
	 * @param array $array
	 * @return bool
	 */
	public static function isEmpty($array) {
		return !$array;
	}

	/**
	 * Get an array element or a default value.
	 *
	 * Retrieves the value stored under a key in an array, or a default
	 * value if the key does not exist.
	 *
	 * @see \jitsu\ArrayUtil::hasKey() See for a note about integer keys.
	 *
	 * @param array $array
	 * @param int|string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get($array, $key, $default = null) {
		return array_key_exists($key, $array) ? $array[$key] : $default;
	}

	/**
	 * Get a reference to an array element, inserting a value if necessary.
	 *
	 * Gets a reference to the value stored under a key in an array. If the
	 * key does not exist, inserts a default value at that key and returns
	 * a reference to the new element.
	 *
	 * @see \jitsu\ArrayUtil::hasKey() See for a note about integer keys.
	 *
	 * @param array $array
	 * @param int|string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function &getRef(&$array, $key, $default = null) {
		if(!array_key_exists($key, $array)) {
			$array[$key] = $default;
		}
		return $array[$key];
	}

	/**
	 * Return whether an array contains a certain key.
	 *
	 * Unlike `isset`, this works even if the value is `null`.
	 *
	 * For integer keys, testing with the string equivalent will still
	 * result in `true`.
	 *
	 * @param array $array
	 * @param int|string $key
	 * @return bool
	 */
	public static function hasKey($array, $key) {
		// unlike isset, this properly detects null values
		return array_key_exists($key, $array);
	}

	/**
	 * Normalize an arbitrary string or integer to its PHP array key
	 * equivalent.
	 *
	 * PHP arrays normalize their keys by converting all strings of decimal
	 * digits without superfluous leading 0's to their integer equivalents.
	 * Integers are always left alone. The important thing to remember is
	 * that inserting a key into an array may or may not change its type,
	 * which has ramifications for performing strict comparisons on that
	 * key if it is retrieved from the array later. For example:
	 *
	 *     $a = array();
	 *     $k = '123';
	 *     $a[$k] = 456;
	 *     $keys = array_keys($a);
	 *     echo ($keys[0] === $k); // suprisingly, this is false
	 *
	 * In such a situation, `normalizeKey` can be used to ensure that an
	 * arbitrary string value can be compared strictly.
	 *
	 *     echo ($keys[0] === \Jitsu\ArrayUtil::normalizeKey($k)); // true
	 *
	 * @param int|string $k
	 * @return int|string
	 */
	public static function normalizeKey($k) {
		if(is_int($k)) return $k;
		foreach(array($k => null) as $result => $v) return $result;
	}

	/**
	 * Remove a key from an array.
	 *
	 * It is not an error to remove a non-existent key.
	 *
	 * @param array $array
	 * @param int|string $key
	 */
	public static function remove(&$array, $key) {
		unset($array[$key]);
	}

	/**
	 * List all of the keys in an array.
	 *
	 * @param array $array
	 * @return (int|string)[] A sequential array.
	 */
	public static function keys($array) {
		return array_keys($array);
	}

	/**
	 * List the values in an array.
	 *
	 * When called with a list of keys, returns the values of the elements
	 * with those keys, in the same order.
	 *
	 * @param array $array
	 * @param (int|string)[]|null $keys
	 * @param mixed $default Default value used for missing keys.
	 * @return array
	 * @throws \RuntimeException A key was missing and no default value was
	 *                           provided.
	 */
	public static function values($array, $keys = null, $default = null) {
		if($keys === null) {
			return array_values($array);
		} else {
			$result = array();
			if(func_num_args() > 2) {
				foreach($keys as $key) {
					$result[] = self::get($array, $key, $default);
				}
			} else {
				foreach($keys as $key) {
					if(array_key_exists($key, $array)) {
						$result[] = $array[$key];
					} else {
						throw new \RuntimeException('missing key ' . $key);
					}
				}
			}
			return $result;
		}
	}

	/**
	 * Append a value to the end of a sequential array.
	 *
	 * Note that this is equivalent to the more succinct
	 * `$array[] = $value`.
	 *
	 * @param array $array
	 * @param mixed $value
	 */
	public static function append(&$array, $value) {
		$array[] = $value;
	}

	/**
	 * Append a list of values to an array.
	 *
	 * @param array $array1 The array being modified.
	 * @param array $array2 The list of values to append.
	 */
	public static function appendMany(&$array1, $array2) {
		foreach($array2 as $value) {
			$array1[] = $value;
		}
	}

	/**
	 * Concatenate two sequential arrays.
	 *
	 * This does *not* work as expected on associative arrays.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function concat($array1, $array2) {
		return array_merge($array1, $array2);
	}

	/**
	 * @see \jitsu\ArrayUtil::append() Alias of `append`.
	 *
	 * @param array $array
	 * @param mixed $value
	 */
	public static function push(&$array, $value) {
		$array[] = $value;
	}

	/**
	 * Pop a value off the end of an array.
	 *
	 * Returns `null` if the array was empty.
	 *
	 * @param array $array
	 * @return mixed|null
	 */
	public static function pop(&$array) {
		return array_pop($array);
	}

	/**
	 * Shift an element off the beginning of an array.
	 *
	 * Returns `null` if the array was empty. Re-indexes sequential
	 * arrays.
	 *
	 * @param array $array
	 * @return mixed|null
	 */
	public static function shift(&$array) {
		return array_shift($array);
	}

	/**
	 * Prepend a value to the beginning of an array.
	 *
	 * Re-indexes sequential arrays.
	 *
	 * @param array $array
	 * @param mixed $value
	 */
	public static function unshift(&$array, $value) {
		array_unshift($array, $value);
	}

	/**
	 * Look up a key by its value using linear search.
	 *
	 * Returns the first key whose value is strictly equal to the given
	 * value. Returns `null` if the value does not exist in the array.
	 * Works for both associative and sequential arrays.
	 *
	 * @param array $array
	 * @param mixed $value
	 * @return int|string|null
	 */
	public static function keyOf($array, $value) {
		$r = array_search($value, $array, true);
		return $r === false ? null : $r;
	}

	/**
	 * @see \jitsu\ArrayUtil::keyOf()
	 */
	public static function indexOf($array, $value) {
		return self::keyOf($array, $value);
	}

	/**
	 * Find all keys in an array which map to a certain value.
	 *
	 * Returns a sequential array of all keys in the array whose values
	 * are strictly equal to the given value. Uses linear search.
	 *
	 * @param array $array
	 * @param mixed $value
	 * @return (int|string)[]
	 */
	public static function keysOf($array, $value) {
		return array_keys($array, $value, true);
	}

	/**
	 * Determine whether an array contains a value using linear search.
	 *
	 * Uses strict comparison.
	 *
	 * @param array $array
	 * @param mixed $value
	 * @return bool
	 */
	public static function contains($array, $value) {
		return in_array($value, $array, true);
	}

	/**
	 * Get the value at a certain position in an array.
	 *
	 * Note that this is completely different from looking up a value by
	 * key. Rather, this looks up a value by its offset according to the
	 * array's internal ordering of key-value pairs.
	 *
	 * This discussion of "internal ordering" might require some
	 * explanation. Despite its name, the `array` type in PHP really
	 * implements an ordered dictionary data structure. All arrays, both
	 * sequential and associative, record a mapping of keys to values, as
	 * well as the order in which those key-value pairs were inserted. A
	 * sequential array is just an array whose key values coincide exactly
	 * with their ordering. This ordering determines the order in which
	 * a `foreach` loop iterates over an array's elements, among other
	 * behaviors. This function taps into that internal ordering and looks
	 * up elements by position in constant time.
	 *
	 * @param array $array
	 * @param int $i
	 * @return mixed|null Returns `null` if the index was out of range.
	 */
	public static function at($array, $i) {
		foreach(array_slice($array, $i, 1) as $v) {
			return $v;
		}
	}

	/**
	 * Get the key-value pair at a certain position in an array.
	 *
	 * @see \jitsu\ArrayUtil::at()
	 *
	 * @param array $array
	 * @param int $i
	 * @return array|null The pair `array($key, $value)`.
	 */
	public static function pairAt($array, $i) {
		foreach(array_slice($array, $i, 1, true) as $k => $v) {
			return array($k, $v);
		}
	}

	/**
	 * Get the key at a certain position in an array.
	 *
	 * @see \jitsu\ArrayUtil::at()
	 *
	 * @param array $array
	 * @param int $i
	 * @return int|string|null
	 */
	public static function keyAt($array, $i) {
		foreach(array_slice($array, $i, 1, true) as $k => $v) {
			return $k;
		}
	}

	/**
	 * Get a slice of an array.
	 *
	 * Negative indices indicate an offset from the end of the array.
	 *
	 * Slices according to the array's ordering. Slices of sequential
	 * arrays are re-indexed.
	 *
	 * @param array $array
	 * @param int $i The starting index.
	 * @param int|null $j One past the last index, or `null` if all the
	 *                    rest of the array should be used.
	 * @return array
	 */
	public static function slice($array, $i, $j = null) {
		return self::_slice($array, $i, $j, false);
	}

	/**
	 * Get a slice of an array while preserving numeric keys.
	 *
	 * @see \jitsu\ArrayUtil::slice()
	 *
	 * Like `\jitsu\ArrayUtil::slice()`, but preserves keys even for
	 * sequential arrays.
	 *
	 * @param array $array
	 * @param int $i
	 * @param int|null $j
	 * @return array
	 */
	public static function pairSlice($array, $i, $j = null) {
		return self::_slice($array, $i, $j, true);
	}

	/**
	 * Replace a slice of an array with a list of values.
	 *
	 * @see \jitsu\ArrayUtil::slice()
	 *
	 * @param array $array
	 * @param int $i
	 * @param int|null $j
	 * @param array $sub
	 * @return array The replaced slice.
	 */
	public static function assignSlice(&$array, $sub, $i, $j = null) {
		list($offset, $len) = self::_convertSliceIndexes($i, $j, count($array));
		return array_splice($array, $offset, $len, $sub);
	}

	/**
	 * Remove a slice from an array.
	 *
	 * @see \jitsu\ArrayUtil::slice()
	 *
	 * @param array $array
	 * @param int $i
	 * @param int|null $j
	 * @return array The removed slice.
	 */
	public static function removeSlice(&$array, $i, $j = null) {
		return self::assignSlice($array, array(), $i, $j);
	}

	/**
	 * Get a reversed and re-indexed copy of a sequential array.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function reverse($array) {
		return array_reverse($array);
	}

	/**
	 * Get a copy of an array with the order of its key-value pairs
	 * reversed.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function reversePairs($array) {
		return array_reverse($array, true);
	}

	/**
	 * Generate a sequential array consisting of a range of numbers.
	 *
	 * A step size may optionally be specified.
	 *
	 * If all arguments are either integers or not specified, then the
	 * result will contain integers in the range [`$i`, `$j`), where the
	 * end bound is non-inclusive.
	 *
	 * Otherwise, the result will contain numbers in the range [`$i`, `$j`]
	 * inclusive.
	 *
	 * If only the first argument is given, then it acts as the ending
	 * index, and an offset of 0 and step size of 1 are assumed.
	 *
	 * Any of the arguments may be negative. An empty list will be returned
	 * if the beginning of the interval occurs after its end. It is an
	 * error to use a step size of 0. A step size larger than the size of
	 * the interval may be used.
	 *
	 * @param int|float $i If this is the only argument, then this is the
	 *                     end of the range, and the start is implicitly 0.
	 *                     Otherwise, this is the starting index.
	 * @param int|float $j The ending index. Non-inclusive in integer mode,
	 *                     inclusive otherwise.
	 * @param int|float $step An optional step size. A step size of 0
	 *                        causes an error. A step size larger than the
	 *                        size of the interval is valid.
	 * @return (int|float)[]
	 */
	public static function range($i, $j = null, $step = 1) {
		if($j === null) {
			$j = $i;
			$i = 0;
		}
		$ints = is_int($i) && is_int($j) && is_int($step);
		// Adjust $j to be inclusive
		$j += ($step < 0 ? $ints : -$ints);
		// If the step size and interval run in different directions,
		// return an empty array
		if($i > $j !== $step < 0) return array();
		// PHP range has this weird limitation with step sizes larger
		// than the interval... in this case just return an array which
		// includes the start offset
		if(abs($step) > abs($j - $i)) return array($i);
		return range($i, $j, $step);
	}

	/**
	 * Construct an associative array from a list of key-value pairs.
	 *
	 * @param array[] An array of pairs of the form `array($key, $value)`.
	 * @return array
	 */
	public static function fromPairs($pairs) {
		return array_column($pairs, 1, 0);
	}

	/**
	 * Construct an associative array from separate arrays of keys and
	 * values.
	 *
	 * @param (int|string)[] $keys
	 * @param array $values
	 * @return array
	 */
	public static function fromLists($keys, $values) {
		return array_combine($keys, $values);
	}

	/**
	 * Hash a list of values into the keys of an associative array.
	 *
	 * This returns an associative array mapping the values of `$array`
	 * to `true`. This structure can be used to test membership of elements
	 * efficiently, like a set.
	 *
	 * @param (int|string)[] $array The list of values. Naturally, the
	 *                              elements may only be integers or
	 *                              strings.
	 * @param mixed $value An optional value to use other than `true`.
	 * @return true[]
	 */
	public static function toSet($array, $value = true) {
		return array_fill_keys($array, $value);
	}

	/**
	 * Generate a sequential array of `$n` copies of `$value`.
	 *
	 * @param mixed $value
	 * @param int $n
	 * @return array
	 */
	public static function fill($value, $n) {
		return $n === 0 ? array() : array_fill(0, $n, $value);
	}

	/**
	 * Pad a sequential array with copies of `$value`.
	 *
	 * @param array $array
	 * @param mixed $value
	 * @param int $n The length to which the array should be padded. The
	 *               sign of `$n` determines whether the array is padded at
	 *               the beginning or the end.
	 * @return array The padded array.
	 */
	public static function pad($array, $value, $n) {
		return array_pad($array, $n, $value);
	}

	/**
	 * Given a list of arrays, list all of the values under a certain key.
	 *
	 * Returns a sequential array of all the values under a certain key in
	 * a list of arrays. Whenever that key is missing from an array, that
	 * array is simply skipped.
	 *
	 * @param array $array
	 * @param int|string $key
	 * @return array
	 */
	public static function pluck($arrays, $key) {
		return array_column($arrays, $key);
	}

	/**
	 * Select a portion of an array with a list of keys.
	 *
	 * Returns all of the key-value pairs in an array with the listed keys.
	 * The result is returned as an associative array whose ordering
	 * reflects the ordering of the keys listed.
	 *
	 * @param array $array
	 * @param (int|string)[] $keys
	 * @param mixed $default An optional default value to use as the value
	 *                       for missing keys. If not given, missing keys
	 *                       are omitted.
	 * @return array
	 */
	public static function pick($array, $keys, $default = null) {
		$result = array();
		if(func_num_args() > 2) {
			foreach($keys as $key) {
				$result[$key] = self::get($array, $key, $default);
			}
		} else {
			foreach($keys as $key) {
				if(array_key_exists($key, $array)) {
					$result[$key] = $array[$key];
				}
			}
		}
		return $result;
	}

	/**
	 * Invert an array's key-value pairs.
	 *
	 * Inverts an array so that its values become the keys and vice-versa.
	 * Of course, it is an error to try to invert an array containing 
	 * on-integer or non-string values.
	 *
	 * @param (int|string)[] $array
	 * @return An inverted copy of the array.
	 */
	public static function invert($array) {
		return array_flip($array);
	}

	/**
	 * Combine the key-value pairs of two arrays into one.
	 *
	 * The values of the second array take precedence over those in the
	 * first.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function extend($array1, $array2) {
		return array_replace($array1, $array2);
	}

	/**
	 * Recursively combine two array structures.
	 *
	 * The values of the second array structure take precedence over those
	 * in the first.
	 *
	 * @see \jitsu\ArrayUtil::extend()
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function deepExtend($array1, $array2) {
		return array_replace_recursive($array1, $array2);
	}

	/**
	 * Split an array's values into chunks of a certain size.
	 *
	 * Returns a sequential array containing the chunks of values as
	 * sequential arrays. The last chunk may have fewer than `$n`
	 * elements. Splits according to ordering. Does not preserve
	 * associative array keys but instead always reindexes each chunk.
	 *
	 * @param array $array
	 * @param int $n
	 * @return array[]
	 */
	public static function chunks($array, $n) {
		return array_chunk($array, $n);
	}

	/**
	 * Apply a function to the an array's values to generate a new array.
	 *
	 * Keys are preserved.
	 *
	 * @param array $array
	 * @param callable $callback Called with each of the array's values.
	 * @return array
	 */
	public static function map($array, $callback) {
		return array_map($callback, $array);
	}

	/**
	 * Filter an array's values by a predicate to generate a new array.
	 *
	 * Preserves keys (does not reindex sequential arrays).
	 *
	 * @param array $array
	 * @param callable $callback Called with each of the array's values
	 *                           and should return `bool`. If not given,
	 *                           filters all truthy values.
	 * @return array
	 */
	public static function filter($array, $callback = null) {
		return array_filter($array, $callback);
	}

	/**
	 * Filter an array's key-value pairs by a predicate to generate a new
	 * array.
	 *
	 * @param array $array
	 * @param callable $callback Called with the arguments `$key` and
	 *                           `$value` and should return `bool`.
	 * @return array
	 */
	public static function filterPairs($array, $callback) {
		return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
	}

	/**
	 * Take the sum of an array's values.
	 *
	 * @param (int|float)[] $array
	 * @return int|float
	 */
	public static function sum($array) {
		return array_sum($array);
	}

	/**
	 * Take the product of an array's values.
	 *
	 * @param (int|float)[] $array
	 * @return int|float
	 */
	public static function product($array) {
		return array_product($array);
	}

	/**
	 * Reduce an array's values using a binary function.
	 *
	 * @param array $array
	 * @param callable $callback A function which accepts two arguments:
	 *                           the running "total" and the next value in
	 *                           the array.
	 * @param mixed $initial An optional initial value, which is `null` by
	 *                       default.
	 * @return mixed
	 */
	public static function reduce($array, $callback, $initial = null) {
		return array_reduce($array, $callback, $initial);
	}

	/**
	 * Call a callback on each element of an array.
	 *
	 * @param array $array
	 * @param callback $callback A function which accepts an array value as
	 *                           its first argument and optionally the
	 *                           associated key as the second.
	 */
	public static function apply(&$array, $callback) {
		array_walk($array, $callback);
	}

	/**
	 * Perform an in-order traversal of a nested array structure.
	 *
	 * @param array $array
	 * @param callback $callback A function which accepts the arguments
	 *                           `$key` and `$value`. The callback may
	 *                           modify the array's contents in-place.
	 */
	public static function traverseLeaves(&$array, $callback) {
		array_walk_recursive($array, $callback);
	}

	/**
	 * Get key-value pairs which exist in one array but not in another.
	 *
	 * Returns an associative array containing all key-value pairs which
	 * exist in the first array but not in the second according to some
	 * uniqueness criteria determined by the values passed as `$key_cmp`
	 * and `$value_cmp`. Both `$key_cmp` and `$value_cmp` may be `null`,
	 * `true`, or a comparison callback and are used to compare the keys
	 * and values of array elements, respectively. If a comparator is
	 * `null`, its component is ignored in the comparison. If a comparator
	 * is `true`, then the default string comparison method is used for
	 * that component. Otherwise, a callback implementing an arbitrary
	 * comparison function may be used. The default is to ignore keys and
	 * compare values by string comparison.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param callable|true|null $key_cmp
	 * @param callable|true|null $value_cmp
	 * @return array
	 */
	public static function difference($array1, $array2, $key_cmp = null, $value_cmp = true) {
		if($key_cmp === null) {
			if($value_cmp === null) {
				throw new \BadMethodCallException('no comparators given to compute array difference');
			} else {
				if($value_cmp === true) $value_cmp = null;
				return self::valueDifference($array1, $array2, $value_cmp);
			}
		} else {
			if($key_cmp === true) $key_cmp = null;
			if($value_cmp === null) {
				return self::keyDifference($array1, $array2, $key_cmp);
			} else {
				if($value_cmp === true) $value_cmp = null;
				return self::pairDifference($array1, $array2, $key_cmp, $value_cmp);
			}
		}
	}

	/**
	 * Get key-value pairs which exist in one array but not in another,
	 * where both keys and values determine uniqueness.
	 *
	 * Returns an associative array containing all key-value pairs which
	 * exist in the first array but not in the second. Optionally provide
	 * comparison functions for the keys and values. String comparison is
	 * used by default.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param callable|null $key_cmp
	 * @param callable|null $value_cmp
	 * @return array
	 */
	public static function pairDifference($array1, $array2, $key_cmp = null, $value_cmp = null) {
		// Seriously, PHP? seriously?
		if($key_cmp === null) {
			if($value_cmp === null) {
				return array_diff_assoc($array1, $array2);
			} else {
				return array_udiff_assoc($array1, $array2, $value_cmp);
			}
		} else {
			if($value_cmp === null) {
				return array_diff_uassoc($array1, $array2, $key_cmp);
			} else {
				return array_udiff_uassoc($array1, $array2, $value_cmp, $key_cmp);
			}
		}
	}

	/**
	 * Get key-value pairs which exist in one array but not in another,
	 * where keys alone determine uniqueness.
	 *
	 * Returns an associative array containing all key-value pairs in the
	 * first array whose keys do not exist in the second. Optionally
	 * provide a comparison function for the keys. String comparison is
	 * used by default.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param callable|null $key_cmp
	 * @return array
	 */
	public static function keyDifference($array1, $array2, $key_cmp = null) {
		if($key_cmp === null) {
			return array_diff_key($array1, $array2);
		} else {
			return array_diff_ukey($array1, $array2, $key_cmp);
		}
	}

	/**
	 * Get key-value pairs which exist in one array but not in another,
	 * where values alone determine uniqueness.
	 *
	 * Returns an associative array containing all key-value pairs in the
	 * first array whose values do not exist in the second. Optionally
	 * provide a comparison function for values. Uses string comparison by
	 * default.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param callable|null $value_cmp
	 * @return array
	 */
	public static function valueDifference($array1, $array2, $value_cmp = null) {
		if($value_cmp === null) {
			return array_diff($array1, $array2);
		} else {
			return array_udiff($array1, $array2, $value_cmp);
		}
	}

	/**
	 * Get key-value pairs which exist in both of two arrays.
	 *
	 * Returns an associative array whose key-value pairs exist in both
	 * arrays. Uses string comparison for values by default. Optionally
	 * provide comparison functions for keys and values.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param callable|null $key_cmp
	 * @param callable|null $value_cmp
	 * @return array
	 */
	public static function pairIntersection($array1, $array2, $key_cmp = null, $value_cmp = null) {
		if($key_cmp === null) {
			if($value_cmp === null) {
				return array_intersect_assoc($array1, $array2);
			} else {
				return array_uintersect_assoc($array1, $array2, $value_cmp);
			}
		} else {
			if($value_cmp === null) {
				return array_intersect_uassoc($array1, $array2, $key_cmp);
			} else {
				return array_uintersect_uassoc($array1, $array2, $value_cmp, $key_cmp);
			}
		}
	}

	/**
	 * Get key-value pairs which exist in both of two arrays, where keys
	 * alone determine uniqueness.
	 *
	 * Returns an associative array containing all key-value pairs in the
	 * first array whose keys exist in the second. Optionally provide a
	 * key comparison function.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param callable|null $key_cmp
	 * @return array
	 */ 
	public static function keyIntersection($array1, $array2, $key_cmp = null) {
		if($key_cmp === null) {
			return array_intersect_key($array1, $array2);
		} else {
			return array_intersect_ukey($array1, $array2, $key_cmp);
		}
	}

	/**
	 * Get key-value pairs which exist in both of two arrays, where values
	 * alone determine uniqueness.
	 *
	 * Returnis an associative array containing all key-value pairs in the
	 * first array whose values exist in the second. Uses string
	 * comparison by default. Optionally provide a comparison function.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param callable|null $value_cmp
	 * @return array
	 */
	public static function valueIntersection($array1, $array2, $value_cmp = null) {
		if($value_cmp === null) {
			return array_intersect($array1, $array2);
		} else {
			return array_uintersect($array1, $array2, $value_cmp);
		}
	}

	/**
	 * Get all of the unique values in an array, de-duplicated.
	 *
	 * Removes all key-value pairs from an array whose values are duplicates
	 * of other values earlier in the array. Comparison is *non-strict*.
	 * Keys are preserved.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function uniqueValues($array) {
		return array_unique($array, SORT_REGULAR);
	}

	/**
	 * Determine whether an array only contains keys in a given list.
	 *
	 * @param array $array
	 * @param (int|string)[] $keys
	 * @param array|null $unexpected An optional argument which collects
	 *                               the unexpected keys found.
	 * @return bool
	 */
	public static function hasOnlyKeys($array, $keys, &$unexpected = null) {
		$gather = func_num_args() > 2;
		if($gather) $unexpected = array();
		$key_set = self::toSet($keys);
		foreach($array as $key => $value) {
			if(!array_key_exists($key, $key_set)) {
				if($gather) {
					$unexpected[] = $key;
				} else {
					return false;
				}
			}
		}
		return !$unexpected;
	}

	/**
	 * Determine whether an array contains all keys in a given list.
	 *
	 * @param array $array
	 * @param (int|string)[] $keys
	 * @param array|null $missing An optional argument which collects the
	 *                            missing keys not found.
	 * @return bool
	 */
	public static function hasKeys($array, $keys, &$missing = null) {
		$gather = func_num_args() > 2;
		if($gather) $missing = array();
		foreach($keys as $key) {
			if(!array_key_exists($key, $array)) {
				if($gather) {
					$missing[] = $key;
				} else {
					return false;
				}
			}
		}
		return !$missing;
	}

	/**
	 * Determine whether an array contains exactly the keys in a given
	 * list.
	 *
	 * @param array $array
	 * @param (int|string)[] $keys
	 * @param array|null $unexpected An optional argument which collects
	 *                               the unexpected keys found.
	 * @param array|null $missing An optional argument which collects the
	 *                            missing keys not found.
	 * @return bool
	 */
	public static function hasExactKeys($array, $keys, &$unexpected = null, &$missing = null) {
		$gather = func_num_args() > 2;
		if($gather) $unexpected = $missing = array();
		$key_set = self::toSet($keys);
		foreach($array as $key => $value) {
			if(array_key_exists($key, $key_set)) {
				unset($key_set[$key]);
			} elseif($gather) {
				$unexpected[] = $key;
			} else {
				return false;
			}
		}
		if($gather) {
			$missing = array_keys($key_set);
		}
		return !$key_set && !$unexpected;
	}

	/**
	 * Pick a random key from an array.
	 *
	 * @param array $array
	 * @return int|string
	 */
	public static function randomKey($array) {
		return array_rand($array);
	}

	/**
	 * Pick a random value from an array.
	 *
	 * @param array $array
	 * @return mixed
	 */
	public static function randomValue($array) {
		return $array[array_rand($array)];
	}

	/**
	 * Pick a random key-value pair from an array.
	 *
	 * @param array $array
	 * @return array The pair `array($key, $value)`.
	 */
	public static function randomPair($array) {
		$k = array_rand($array);
		return array($k, $array[$k]);
	}

	/**
	 * Pick `$n` random keys from an array.
	 *
	 * @param array $array
	 * @param int $n
	 * @return array A sequential array.
	 */
	public static function randomKeys($array, $n) {
		if($n === 0) return array();
		$r = array_rand($array, $n);
		if($n === 1) $r = array($r);
		return $r;
	}

	/**
	 * Randomly shuffle and re-index the values of an array in-place.
	 *
	 * @param array $array
	 */
	public static function shuffle(&$array) {
		shuffle($array);
	}

	/**
	 * Sort and re-index the values of an array in-place.
	 *
	 * @param array $array
	 * @param callable|null $value_cmp An optional comparison function.
	 */
	public static function sort(&$array, $value_cmp = null) {
		if($value_cmp === null) {
			sort($array);
		} else {
			usort($array, $value_cmp);
		}
	}

	/**
	 * Sort and re-index the values of an array in-place, in reverse.
	 *
	 * @param array $array
	 */
	public static function reverseSort(&$array) {
		rsort($array);
	}

	/**
	 * Sort and re-index the values of an array of strings in-place, based
	 * on the rules of the current locale.
	 *
	 * @param array $array
	 */
	public static function localeSort(&$array) {
		sort($array, SORT_LOCALE_STRING);
	}

	/**
	 * Sort the key-value pairs of an array in-place based on their values.
	 *
	 * Like `sort` but preserves keys.
	 *
	 * @param array $array
	 * @param callable|null $value_cmp An optional comparison function.
	 */
	public static function sortPairs(&$array, $value_cmp = null) {
		if($value_cmp === null) {
			asort($array);
		} else {
			uasort($array, $value_cmp);
		}
	}

	/**
	 * Sort the key-value pairs of an array in-place based on their values,
	 * in reverse order.
	 *
	 * @param array $array
	 */
	public static function reverseSortPairs(&$array) {
		arsort($array);
	}

	/**
	 * Sort the key-value pairs of an array in-place based on their keys.
	 *
	 * @param array $array
	 * @param callable|null $key_cmp An optional comparison function.
	 */
	public static function sortKeys(&$array, $key_cmp = null) {
		if($key_cmp === null) {
			ksort($array);
		} else {
			uksort($array, $key_cmp);
		}
	}

	/**
	 * Sort the key-value pairs of an array in-place based on their keys,
	 * in reverse order.
	 *
	 * @param array $array
	 */
	public static function reverseSortKeys(&$array) {
		krsort($array);
	}

	/**
	 * Sort the key-value pairs of an array of strings based on their
	 * values in a human-sensible way, in-place.
	 *
	 * @param array $array
	 */
	public static function humanSortValues(&$array) {
		natsort($array);
	}

	/**
	 * Sort the key-value pairs of an array of strings based on their
	 * values in a human-sensible way, ignoring case, in-place.
	 *
	 * @param array $array
	 */
	public static function iHumanSortValues(&$array) {
		natcasesort($array);
	}

	/**
	 * Convert the keys in an array to lower case.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function lowerKeys($array) {
		return array_change_key_case($array);
	}

	/**
	 * Convert the keys in an array to upper case.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function upperKeys($array) {
		return array_change_key_case($array, CASE_UPPER);
	}

	private static function _isSequential($array) {
		$i = 0;
		foreach($array as $k => $v) {
			if($k !== $i++) return false;
		}
		return true;
	}

	/**
	 * Determine if a value is a sequential array.
	 *
	 * Checks that the value is an array whose keys coincide exactly with
	 * their ordering.
	 *
	 * **Note:** Since the complexity of this function is linear
	 * in the size of the array, its use should be avoided.
	 *
	 * @param mixed $array
	 * @return bool
	 */
	public static function isSequential($array) {
		return is_array($array) && self::_isSequential($array);
	}

	/**
	 * Determine if a value is an associative array.
	 *
	 * **Note:** Since the complexity of this function is linear
	 * in the size of the array, its use should be avoided.
	 *
	 * @see \Jitsu\ArrayUtil::isSequential()
	 */
	public static function isAssociative($array) {
		return is_array($array) && (
			count($array) === 0 ||
			!self::_isSequential($array)
		);
	}

	/**
	 * Tally the occurences of each value in an array.
	 *
	 * Counts the number of times each value appears in an array and
	 * produces an associative array mapping those values to their
	 * frequencies. Of course, this limits the array values to strings
	 * and integers.
	 *
	 * @param (int|string)[] $array
	 * @return int[]
	 */
	public static function countValues($array) {
		return array_count_values($array);
	}

	private static function _slice($array, $i, $j, $preserve_keys) {
		list($offset, $len) = self::_convertSliceIndexes($i, $j, count($array));
		return array_slice($array, $offset, $len, $preserve_keys);
	}

	/**
	* Given two slice indices and a length, compute the starting offset
	* and length of the resulting slice.
	*
	* This function is useful for converting slice indexes to arguments
	* accepted by some builtin PHP functions.
	*
	* @param int $i
	* @param int $j
	* @param int $length
	* @return array A pair in the form `array($offset, $slice_length)`.
	*/
	private static function _convertSliceIndexes($i, $j, $length) {
	    $i = self::_normalizeSliceIndex($i, $length, 0);
	    $j = self::_normalizeSliceIndex($j, $length, $length);
	    return array(min($i, $length), max(0, $j - $i));
	}

	/**
	* Normalize an index to be within the range [0, `$length`], where
	* a negative value will be treated as an offset from `$length`.
	*
	* This function is useful for computing slice ranges.
	*
	* @param int $i
	* @param int $length
	* @param int|null $default An optionally value to use when `$i` is `null`.
	* @return int|null
	*/
	private static function _normalizeSliceIndex($i, $length, $default = null) {
	    if($i === null) return $default;
	    if($i < 0) return max(0, $length + $i);
	    return min($length, $i);
	}
}
