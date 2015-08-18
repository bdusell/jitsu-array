<?php

namespace Jitsu\Tests;

use Jitsu\ArrayUtil as a;

class ArrayUtilTest extends \PHPUnit_Framework_TestCase {

	private function eq($a, $b) {
		$this->assertSame($a, $b);
	}

	private function afeq($a, $b) {
		$this->assertSame(array_keys($a), array_keys($b));
		foreach($a as $k => $v) {
			$this->assertSame($v, $b[$k], 0.001);
		}
	}

	public function testSize() {
		$this->eq(a::size([]), 0);
		$this->eq(a::size([1, 2, 3]), 3);
		$this->eq(a::size([[1, 2], [3, 4]]), 2);
		$this->eq(a::size(['a' => 1, 'b' => 2]), 2);
		$this->eq(a::length(['a', 'b', 'c', 'd']), 4);
	}

	public function testIsEmpty() {
		$this->eq(a::isEmpty([]), true);
		$this->eq(a::isEmpty([1, 2, 3]), false);
	}

	public function testGet() {
		$a = ['a' => 1, 'b' => 2];
		$this->eq(a::get($a, 'a'), 1);
		$this->eq(a::get($a, 'c'), null);
		$this->eq(a::get($a, 'a', 42), 1);
		$this->eq(a::get($a, 'd', 42), 42);
	}

	public function testGetRef() {
		$a = ['a' => 1, 'b' => 2];
		$this->eq(a::getRef($a, 'a'), 1);
		$aref = &a::getRef($a, 'a');
		$aref = 3;
		$this->eq($a['a'], 3);
		$this->eq(a::getRef($a, 'b', 4), 2);
		$cref = &a::getRef($a, 'c');
		$this->eq($cref, null);
		$this->eq($a['c'], null);
		$cref = 5;
		$this->eq($a['c'], 5);
		a::getRef($a, 'd', 6);
		$this->eq($a['d'], 6);
		$this->eq(a::size($a), 4);
	}

	public function testHasKey() {
		$this->eq(a::hasKey([1, 2, 3], 0), true);
		$this->eq(a::hasKey([1, 2, 3], '0'), true);
		$this->eq(a::hasKey([1, 2, 3], 4), false);
		$this->eq(a::hasKey([1, 2, 3], '4'), false);
		$this->eq(a::hasKey(['a' => 1, 'b' => 2], 'a'), true);
		$this->eq(a::hasKey(['a' => 1, 'b' => 2], 'c'), false);
		$this->eq(a::hasKey(['a' => 0, 'b' => 2], 'a'), true);
		$this->eq(a::hasKey(['a' => null, 'b' => 2], 'a'), true);
	}

	public function testNormalizeKey() {
		$this->eq(a::normalizeKey(123), 123);
		$this->eq(a::normalizeKey('123'), 123);
		$this->eq(a::normalizeKey('-123'), -123);
		$this->eq(a::normalizeKey('-0123'), '-0123');
		$this->eq(a::normalizeKey('0123'), '0123');
		$this->eq(a::normalizeKey('123abc'), '123abc');
		$this->eq(a::normalizeKey('abc123'), 'abc123');
		$this->eq(a::normalizeKey('abc123def'), 'abc123def');
		$this->eq(a::normalizeKey('abc'), 'abc');
		$a = [];
		$k = '123';
		$a[$k] = 456;
		$keys = array_keys($a);
		$this->eq($keys[0] === $k, false);
		$this->eq($keys[0] === a::normalizeKey($k), true);
	}

	public function testRemove() {
		$a = ['a' => 1, 'b' => 2];
		$this->eq(a::hasKey($a, 'a'), true);
		$this->eq(a::size($a), 2);
		a::remove($a, 'a');
		$this->eq(a::hasKey($a, 'a'), false);
		$this->eq(a::size($a), 1);
		a::remove($a, 'c');
		$this->eq(a::size($a), 1);
	}

	public function testKeys() {
		$this->eq(a::keys([1, 2, 3]), [0, 1, 2]);
		$this->eq(a::keys(['a' => 1, 'b' => 2]), ['a', 'b']);
		$this->eq(a::keys([3 => 'a', 2 => 'b', 1 => 'c']), [3, 2, 1]);
	}

	public function testValues() {
		$seq = [1, 2, 3];
		$assoc = ['a' => 1, 'b' => 2, 'c' => 3];
		$this->eq(a::values($seq), [1, 2, 3]);
		$this->eq(a::values($assoc), [1, 2, 3]);
		$this->eq(a::values($assoc, ['a', 'c']), [1, 3]);
		$this->eq(a::values($assoc, ['c', 'b', 'a']), [3, 2, 1]);
		$this->eq(a::values($assoc, ['b', 'c', 'd'], null), [2, 3, null]);
		$this->eq(a::values($assoc, ['b', 'c', 'd'], 123), [2, 3, 123]);
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testValuesMissing() {
		a::values(['a' => 1, 'b' => 2], ['b', 'c', 'd']);
	}

	public function testAppend() {
		$a = [1, 2, 3];
		a::append($a, 4);
		$this->eq($a, [1, 2, 3, 4]);
		a::push($a, 5);
		$this->eq($a, [1, 2, 3, 4, 5]);
	}

	public function testAppendMany() {
		$a = [1, 2, 3];
		a::appendMany($a, [4, 5, 6]);
		$this->eq($a, [1, 2, 3, 4, 5, 6]);
	}

	public function testConcat() {
		$a = [1, 2, 3];
		$b = [4, 5, 6];
		$this->eq(a::concat($a, $b), [1, 2, 3, 4, 5, 6]);
		$this->eq($a, [1, 2, 3]);
		$this->eq($b, [4, 5, 6]);
	}

	public function testPop() {
		$a = [1, 2, 3];
		a::pop($a);
		$this->eq($a, [1, 2]);
	}

	public function testShift() {
		$a = [1, 2, 3];
		a::shift($a);
		$this->eq($a, [2, 3]);
	}

	public function testUnshift() {
		$a = [1, 2, 3];
		a::unshift($a, 4);
		$this->eq($a, [4, 1, 2, 3]);
	}

	public function testKeyOf() {
		$a = ['a' => 1, 'b' => '2', 'c' => 2];
		$this->eq(a::keyOf($a, '2'), 'b');
		$this->eq(a::keyOf($a, 2), 'c');
		$this->eq(a::keyOf($a, 'abc'), null);
		$this->eq(a::indexOf([1, 2, 3, '1', '2', '3'], '2'), 4);
	}

	public function testKeysOf() {
		$seq = [0, 1, 1, 0, 0, 1];
		$assoc = ['a' => 1, 'b' => 2, 'c' => 1];
		$this->eq(a::keysOf($seq, 0), [0, 3, 4]);
		$this->eq(a::keysOf($seq, 1), [1, 2, 5]);
		$this->eq(a::keysOf($seq, 2), []);
		$this->eq(a::keysOf($seq, '0'), []);
		$this->eq(a::keysOf($assoc, 1), ['a', 'c']);
		$this->eq(a::keysOf($assoc, 2), ['b']);
		$this->eq(a::keysOf($assoc, 3), []);
	}

	public function testContains() {
		$this->eq(a::contains([1, 2, 3], 2), true);
		$this->eq(a::contains([1, 2, 3], 5), false);
		$this->eq(a::contains([1, 2, 3], '2'), false);
		$this->eq(a::contains(['a' => 1, 'b' => 2], 2), true);
	}

	public function testAt() {
		$seq = [3 => 0, 2 => 1, 1 => 2];
		$assoc = ['a' => 1, 'b' => 2, 'c' => 3];
		$this->eq($seq[2], 1);
		$this->eq(a::at($seq, 2), 2);
		$this->eq(a::pairAt($seq, 2), [1, 2]);
		$this->eq(a::keyAt($seq, 2), 1);
		$this->eq(a::at($seq, 1000), null);
		$this->eq(a::pairAt($seq, 1000), null);
		$this->eq(a::keyAt($seq, 1000), null);
		$this->eq(a::at($assoc, 0), 1);
		$this->eq(a::pairAt($assoc, 0), ['a', 1]);
		$this->eq(a::keyAt($assoc, 0), 'a');
		$this->eq(a::at($assoc, 2), 3);
		$this->eq(a::pairAt($assoc, 2), ['c', 3]);
		$this->eq(a::keyAt($assoc, 2), 'c');
	}

	public function testSlice() {
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::slice($a, 1, 3), [2, 3]);
		$this->eq(a::slice($a, 3, 4), [4]);
		$this->eq(a::slice($a, 3, 3), []);
		$this->eq(a::slice($a, 3, 2), []);
		$this->eq(a::slice($a, 2), [3, 4, 5, 6]);
		$this->eq(a::slice($a, 2, 1000), [3, 4, 5, 6]);
		$this->eq(a::slice($a, 1000), []);
		$this->eq(a::slice($a, 1000, 4), []);
		$this->eq(a::slice($a, 1000, 2000), []);
		$this->eq(a::slice($a, 0, -2), [1, 2, 3, 4]);
		$this->eq(a::slice($a, 1, -1), [2, 3, 4, 5]);
		$this->eq(a::slice($a, 3, -3), []);
		$this->eq(a::slice($a, 4, -5), []);
		$this->eq(a::slice($a, -4, -2), [3, 4]);
		$this->eq(a::slice($a, -4, 4), [3, 4]);
		$this->eq(a::slice($a, -1000), [1, 2, 3, 4, 5, 6]);
		$this->eq(a::slice($a, -1000, -2), [1, 2, 3, 4]);
		$this->eq(a::slice($a, -1000, -100), []);
		$this->eq(a::slice([], 3, 5), []);
		$this->eq(a::slice(['a' => 1, 'b' => 2, 'c' => 3], 1, 2), ['b' => 2]);
		$this->eq(a::slice($a, 6), []);
		$this->eq(a::slice($a, 6, 6), []);
		$this->eq(a::slice($a, 5, 6), [6]);
		$this->eq(a::slice($a, 7), []);
	}

	public function testPairSlice() {
		$seq = [1, 2, 3, 4, 5];
		$assoc = ['a' => 1, 'b' => 2, 'c' => 3];
		$this->eq(a::pairSlice($seq, 1, -1), [1 => 2, 2 => 3, 3 => 4]);
		$this->eq(a::pairSlice($assoc, 1), ['b' => 2, 'c' => 3]);
	}

	public function testAssignSlice() {
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], 2, 4), [3, 4]);
		$this->eq($a, [1, 2, 0, 5, 6]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], 2), [3, 4, 5, 6]);
		$this->eq($a, [1, 2, 0]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], 2, 1000), [3, 4, 5, 6]);
		$this->eq($a, [1, 2, 0]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], 1000), []);
		$this->eq($a, [1, 2, 3, 4, 5, 6, 0]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], 1000, 1003), []);
		$this->eq($a, [1, 2, 3, 4, 5, 6, 0]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], 1000, 3), []);
		$this->eq($a, [1, 2, 3, 4, 5, 6, 0]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], 2, 2), []);
		$this->eq($a, [1, 2, 0, 3, 4, 5, 6]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], -2), [5, 6]);
		$this->eq($a, [1, 2, 3, 4, 0]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], -4, 4), [3, 4]);
		$this->eq($a, [1, 2, 0, 5, 6]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], -1000), [1, 2, 3, 4, 5, 6]);
		$this->eq($a, [0]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], -1000, -100), []);
		$this->eq($a, [0, 1, 2, 3, 4, 5, 6]);
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::assignSlice($a, [0], -7, -4), [1, 2]);
		$this->eq($a, [0, 3, 4, 5, 6]);
		$a = [];
		$this->eq(a::assignSlice($a, [0], 2, 4), []);
		$this->eq($a, [0]);
	}

	public function testRemoveSlice() {
		$a = [1, 2, 3, 4, 5, 6];
		$this->eq(a::removeSlice($a, 1, -1), [2, 3, 4, 5]);
		$this->eq($a, [1, 6]);
	}

	public function testReverse() {
		$this->eq(a::reverse([1, 2, 3]), [3, 2, 1]);
		$this->eq(a::reverse([]), []);
	}

	public function testReversePairs() {
		$this->eq(a::reversePairs([1, 2, 3]), [2 => 3, 1 => 2, 0 => 1]);
		$this->eq(a::reversePairs([
			'a' => 1,
			'b' => 2,
			'c' => 3
		]), [
			'c' => 3,
			'b' => 2,
			'a' => 1
		]);
	}

	public function testRange() {
		$this->eq(a::range(5), [0, 1, 2, 3, 4]);
		$this->eq(a::range(0), []);
		$this->eq(a::range(0, 5), [0, 1, 2, 3, 4]);
		$this->eq(a::range(5, 10), [5, 6, 7, 8, 9]);
		$this->eq(a::range(0, 6, 2), [0, 2, 4]);
		$this->eq(a::range(5, 0, -1), [5, 4, 3, 2, 1]);
		$this->eq(a::range(10, 0, -2), [10, 8, 6, 4, 2]);
		$this->eq(a::range(-10, -5), [-10, -9, -8, -7, -6]);
		$this->eq(a::range(10, 5), []);
		$this->eq(a::range(5, 10, -1), []);
		$this->eq(a::range(-5, -10), []);
		$this->eq(a::range(-10, -5, -1), []);
		$this->eq(a::range(5, 10, 3), [5, 8]);
		$this->eq(a::range(5, 10, 10), [5]);
		$this->eq(a::range(10, 5, -3), [10, 7]);
		$this->eq(a::range(10, 5, -10), [10]);
		$this->eq(a::range(10, 5, 10), []);
		$this->afeq(a::range(0, 5, 1.0), [0.0, 1.0, 2.0, 3.0, 4.0, 5.0]);
		$this->afeq(a::range(0, 1, 0.2), [0.0, 0.2, 0.4, 0.6, 0.8, 1.0]);
	}

	public function testFromPairs() {
		$this->eq(a::fromPairs([['a', 1], ['b', 2], ['c', 3]]), [
			'a' => 1,
			'b' => 2,
			'c' => 3
		]);
	}

	public function testFromLists() {
		$this->eq(a::fromLists(['a', 'b', 'c'], [1, 2, 3]), [
			'a' => 1,
			'b' => 2,
			'c' => 3
		]);
	}

	public function testToSet() {
		$this->eq(a::toSet(['a', 'b', 'c']), [
			'a' => true,
			'b' => true,
			'c' => true
		]);
	}

	public function testFill() {
		$this->eq(a::fill('a', 3), ['a', 'a', 'a']);
		$this->eq(a::fill(1, 0), []);
	}

	public function testPad() {
		$this->eq(a::pad([1, 2, 3], 0, 5), [1, 2, 3, 0, 0]);
		$this->eq(a::pad([1, 2, 3], 0, -5), [0, 0, 1, 2, 3]);
		$this->eq(a::pad([1, 2, 3], 0, 0), [1, 2, 3]);
	}

	public function testPluck() {
		$a = [
			['a' => 1, 'b' => 4],
			['a' => 2],
			['a' => 3, 'b' => 6]
		];
		$this->eq(a::pluck($a, 'a'), [1, 2, 3]);
		$this->eq(a::pluck($a, 'b'), [4, 6]);
	}

	public function testPick() {
		$a = [
			'a' => 1,
			'b' => 2,
			'c' => 3
		];
		$this->eq(a::pick($a, ['a', 'c']), ['a' => 1, 'c' => 3]);
		$this->eq(a::pick($a, ['a', 'd']), ['a' => 1]);
		$this->eq(a::pick($a, ['c', 'b']), ['c' => 3, 'b' => 2]);
		$this->eq(a::pick($a, []), []);
		$this->eq(a::pick($a, ['a', 'd'], null), ['a' => 1, 'd' => null]);
	}

	public function testInvert() {
		$this->eq(a::invert(['a', 'b', 'c']), ['a' => 0, 'b' => 1, 'c' => 2]);
		$this->eq(a::invert(['a' => 1, 'b' => 2]), [1 => 'a', 2 => 'b']);
		$this->eq(a::invert([]), []);
	}

	public function testExtend() {
		$this->eq(a::extend(['a' => 1], ['b' => 2]), ['a' => 1, 'b' => 2]);
		$this->eq(a::extend(['a' => 1, 'b' => 2], ['b' => 3, 'c' => 4]), [
			'a' => 1,
			'b' => 3,
			'c' => 4
		]);
	}

	public function testDeepExtend() {
		$this->eq(a::deepExtend([
			'a' => [
				'a' => 1,
				'b' => 2
			],
			'b' => 2
		], [
			'a' => [
				'b' => 3,
				'c' => 4
			],
			'c' => 3
		]), [
			'a' => [
				'a' => 1,
				'b' => 3,
				'c' => 4
			],
			'b' => 2,
			'c' => 3
		]);
		$this->eq(a::deepExtend([
			'a' => ['b' => 2]
		], [
			'a' => 1
		]), [
			'a' => 1
		]);
		$this->eq(a::deepExtend([
			'a' => 1
		], [
			'a' => ['b' => 2]
		]), [
			'a' => ['b' => 2]
		]);
	}

	public function testChunks() {
		$this->eq(a::chunks([1, 2, 3, 4, 5, 6], 2), [[1, 2], [3, 4], [5, 6]]);
		$this->eq(a::chunks([1, 2, 3, 4, 5, 6], 3), [[1, 2, 3], [4, 5, 6]]);
		$this->eq(a::chunks([1, 2, 3, 4, 5, 6], 4), [[1, 2, 3, 4], [5, 6]]);
		$this->eq(a::chunks([1, 2, 3], 10), [[1, 2, 3]]);
		$this->eq(a::chunks([], 10), []);
		$this->eq(a::chunks(['a' => 1, 'b' => 2, 'c' => 3], 2), [
			[1, 2],
			[3]
		]);
	}

	public function testMap() {
		$counter = 0;
		$this->eq(a::map([1, 2, 3, 4, 5], function($x) use(&$counter) {
			++$counter;
			return $x + 1;
		}), [2, 3, 4, 5, 6]);
		$this->eq($counter, 5);
	}

	public function testFilter() {
		$this->eq(a::filter([1, 2, 3, 4, 5], function($x) {
			return $x % 2 === 0;
		}), [1 => 2, 3 => 4]);
	}

	public function testFilterPairs() {
		if(!defined('ARRAY_FILTER_USE_BOTH')) return;
		$this->eq(a::filterPairs([
			'a' => 'a',
			'b' => 2,
			'c' => 'c'
		], function($k, $v) {
			return $k === $v;
		}), [
			'a' => 'a',
			'c' => 'c'
		]);
	}

	public function testSum() {
		$this->eq(a::sum([1, 2, 3]), 6);
		$this->eq(a::sum([]), 0);
	}

	public function testProduct() {
		$this->eq(a::product([2, 3, 4]), 24);
		$this->eq(a::product([5, 4, 3, 2, 1, 0]), 0);
		$this->eq(a::product([]), 1);
	}

	public function testReduce() {
		$this->eq(a::reduce([1, 2, 3], function($a, $b) {
			return $a + $b;
		}), 6);
		$this->eq(a::reduce(['a', 'b', 'c'], function($a, $b) {
			return $a . $b;
		}), 'abc');
		$this->eq(a::reduce([], function($a, $b) {
			return $a * $b;
		}), null);
		$this->eq(a::reduce(['bar'], function($a, $b) {
			return $a . $b;
		}, 'foo'), 'foobar');
		$this->eq(a::reduce([], function($a, $b) {
			return $a + $b;
		}, 0), 0);
		$this->eq(a::reduce([], function($a, $b) {
			return $a + $b;
		}, 0), 0);
	}

	public function testApply() {
		$a = [1, 2, 3];
		$counter = 0;
		$values = [];
		$keys = [];
		a::apply($a, function(&$x, $k) use (&$counter, &$values, &$keys) {
			++$counter;
			$values[] = $x;
			$keys[] = $k;
			++$x;
		});
		$this->eq($counter, 3);
		$this->eq($values, [1, 2, 3]);
		$this->eq($a, [2, 3, 4]);
		$this->eq($keys, [0, 1, 2]);
	}

	public function testTraverseLeaves() {
		$a = [
			'a' => 1,
			'b' => [
				'a' => 21,
				'b' => [
					'a' => 221,
					'b' => 222
				],
				'c' => 23
			],
			'c' => 3
		];
		$counter = 0;
		$leaves = [];
		$keys =  [];
		a::traverseLeaves($a, function(&$v, $k) use (&$counter, &$leaves, &$keys) {
			++$counter;
			$leaves[] = $v;
			--$v;
			$keys[] = $k;
		});
		$this->eq($counter, 6);
		$this->eq($leaves, [1, 21, 221, 222, 23, 3]);
		$this->eq($keys, ['a', 'a', 'a', 'b', 'c', 'c']);
		$this->eq($a, [
			'a' => 0,
			'b' => [
				'a' => 20,
				'b' => [
					'a' => 220,
					'b' => 221
				],
				'c' => 22
			],
			'c' => 2
		]);
	}

	public function testDifference() {
		$this->eq(a::difference([1, 2, 3], [2]), [0 => 1, 2 => 3]);
		$this->eq(a::difference([1, '2', '3'], [2]), [0 => 1, 2 => '3']);
		$this->eq(a::difference([1, 2], [1, 2, 3]), []);
		$this->eq(a::difference([
			'a' => 1,
			'b' => 2
		], [
			'b' => 3,
			'c' => 4
		], true, null), [
			'a' => 1
		]);
		$cmp = function($a, $b) {
			$r = is_int($a) - is_int($b);
			if($r === 0) {
				if(is_int($a)) {
					return $a - $b;
				} else {
					return strcmp($a, $b);
				}
			} else {
				return $r;
			}
		};
		$this->eq(a::difference([2, 3, 4], [1, 2, 3], null, $cmp), [2 => 4]);
		$this->eq(a::difference([2, 3, 4], ['1', 2, '3'], null, $cmp), [
			1 => 3,
			2 => 4
		]);
		$this->eq(a::difference([
			'a' => 1,
			'b' => 2,
			'c' => 3
		], [
			'B' => 4,
			'C' => 5,
			'D' => 6
		], 'strcasecmp', null), [
			'a' => 1
		]);
		$this->eq(a::difference([
			'a' => 1,
			'b' => 2,
			'c' => 3
		], [
			'b' => 4,
			'c' => 3
		], true, true), [
			'a' => 1,
			'b' => 2
		]);
		$this->eq(a::difference([
			'a' => 'alpha',
			'b' => 'beta',
			'g' => 'gamma',
			'd' => 'delta'
		], [
			'B' => 'beta',
			'g' => 'Gamma',
			'd' => 'foo'
		], 'strcasecmp', 'strcasecmp'), [
			'a' => 'alpha',
			'd' => 'delta'
		]);
	}

	/**
	 * @expectedException Exception
	 */
	public function testDifferenceException() {
		a::difference([1, 2, 3], [1, 2], null, null);
	}

	public function testPairIntersection() {
		$this->eq(a::pairIntersection([
			'a' => 'alpha',
			'b' => 'beta',
			'g' => 'gamma'
		], [
			'b' => 'beta',
			'g' => 'gamma',
			'd' => 'delta'
		]), [
			'b' => 'beta',
			'g' => 'gamma'
		]);
		$this->eq(a::pairIntersection([
			'a' => 'alpha',
			'b' => 'BETA',
			'g' => 'gamma'
		], [
			'b' => 'beta',
			'G' => 'gamma',
			'd' => 'delta'
		], 'strcasecmp', null), [
			'g' => 'gamma'
		]);
		$this->eq(a::pairIntersection([
			'a' => 'alpha',
			'b' => 'BETA',
			'g' => 'gamma'
		], [
			'B' => 'beta',
			'g' => 'GAMMA',
			'd' => 'delta'
		], null, 'strcasecmp'), [
			'g' => 'gamma'
		]);
		$this->eq(a::pairIntersection([
			'a' => 'alpha',
			'b' => 'BETA',
			'g' => 'gamma'
		], [
			'b' => 'beta',
			'G' => 'gamma',
			'd' => 'delta'
		], 'strcasecmp', 'strcasecmp'), [
			'b' => 'BETA',
			'g' => 'gamma'
		]);
	}

	public function testKeyIntersection() {
		$this->eq(a::keyIntersection([
			'a' => 1,
			'b' => 2
		], [
			'b' => 3,
			'c' => 4
		]), [
			'b' => 2
		]);
		$this->eq(a::keyIntersection([
			'a' => 1,
			'B' => 2
		], [
			'b' => 3,
			'c' => 4
		], 'strcasecmp'), [
			'B' => 2
		]);
	}

	public function testValueIntersection() {
		$this->eq(a::valueIntersection([1, 2, 3], [2, 3, 4]), [1 => 2, 2 => 3]);
		$this->eq(a::valueIntersection(
			['a', 'B', 'c'],
			['b', 'C', 'd'],
			'strcasecmp'
		), [
			1 => 'B',
			2 => 'c'
		]);
	}

	public function testUniqueValues() {
		$this->eq(a::uniqueValues([1, 2, 3, 2, 1]), [1, 2, 3]);
		$this->eq(a::uniqueValues([]), []);
		$this->eq(a::uniqueValues(['1', 2, 1, '2', 3]), [
			0 => '1',
			1 => 2,
			4 => 3
		]);
	}

	public function testHasOnlyKeys() {
		$a = [
			'a' => 1,
			'b' => 2,
			'c' => 3
		];
		$this->eq(a::hasOnlyKeys($a, ['a', 'b', 'c']), true);
		$this->eq(a::hasOnlyKeys($a, ['a', 'b', 'c', 'd']), true);
		$this->eq(a::hasOnlyKeys($a, ['a', 'b']), false);
		$this->eq(a::hasOnlyKeys($a, ['a'], $unexpected), false);
		$this->eq($unexpected, ['b', 'c']);
		$this->eq(a::hasOnlyKeys($a, [], $unexpected), false);
		$this->eq($unexpected, ['a', 'b', 'c']);
		$this->eq(a::hasOnlyKeys($a, ['a', 'b', 'c'], $unexpected), true);
		$this->eq($unexpected, []);
	}

	public function testHasKeys() {
		$a = [
			'a' => 1,
			'b' => 2,
			'c' => 3
		];
		$this->eq(a::hasKeys($a, ['a', 'b', 'c']), true);
		$this->eq(a::hasKeys($a, ['a', 'b']), true);
		$this->eq(a::hasKeys($a, ['a', 'b', 'c', 'd']), false);
		$this->eq(a::hasKeys($a, ['a', 'b', 'c', 'd'], $missing), false);
		$this->eq($missing, ['d']);
		$this->eq(a::hasKeys($a, [], $missing), true);
		$this->eq($missing, []);
	}

	public function testHasExactKeys() {
		$a = [
			'a' => 1,
			'b' => 2,
			'c' => 3
		];
		$this->eq(a::hasExactKeys($a, ['a', 'b', 'c']), true);
		$this->eq(a::hasExactKeys($a, ['a', 'b']), false);
		$this->eq(a::hasExactKeys($a, ['a', 'b', 'c', 'd']), false);
		$this->eq(a::hasExactKeys($a, ['b', 'c', 'd']), false);
		$this->eq(a::hasExactKeys($a, ['a', 'b'], $unexpected, $missing), false);
		$this->eq($unexpected, ['c']);
		$this->eq($missing, []);
		$this->eq(a::hasExactKeys($a, ['a', 'b', 'c', 'd'], $unexpected, $missing), false);
		$this->eq($unexpected, []);
		$this->eq($missing, ['d']);
		$this->eq(a::hasExactKeys($a, ['b', 'c', 'd'], $unexpected, $missing), false);
		$this->eq($unexpected, ['a']);
		$this->eq($missing, ['d']);
		$this->eq(a::hasExactKeys($a, ['a', 'b', 'c'], $unexpected, $missing), true);
		$this->eq($unexpected, []);
		$this->eq($missing, []);
	}

	public function testRandom() {
		$a = [
			'a' => 1,
			'b' => 2,
			'c' => 3
		];
		$this->eq(array_key_exists(a::randomKey($a), $a), true);
		$this->eq(in_array(a::randomValue($a), $a), true);
		list($k, $v) = a::randomPair($a);
		$this->eq($v, $a[$k]);
	}

	public function testRandomKeys() {
		$a = [
			'a' => 1,
			'b' => 2,
			'c' => 3
		];
		$this->eq(count(a::randomKeys($a, 3)), 3);
		$this->eq(count(a::randomKeys($a, 1)), 1);
		$this->eq(a::randomKeys($a, 0), []);
	}

	public function testShuffle() {
		$a = [1, 2, 3, 4, 5];
		a::shuffle($a);
		$this->eq(count($a), 5);
		$this->eq(a::isSequential($a), true);
		$b = [42];
		a::shuffle($b);
		$this->eq($b, [42]);
		$e = [];
		a::shuffle($e);
	}

	public function testSort() {
		$a = [4, 2, 5, 1, 3];
		a::sort($a);
		$this->eq($a, [1, 2, 3, 4, 5]);
		$a = ['c', 'D', 'b', 'A', 'e'];
		a::sort($a, 'strcasecmp');
		$this->eq($a, ['A', 'b', 'c', 'D', 'e']);
		$a = [];
		a::sort($a);
		$this->eq($a, []);
	}

	public function testReverseSort() {
		$a = [4, 2, 5, 1, 3];
		a::reverseSort($a);
		$this->eq($a, [5, 4, 3, 2, 1]);
	}

	public function testSortPairs() {
		$a = [5, 2, 1, 4, 3];
		a::sortPairs($a);
		$this->eq($a, [2 => 1, 1 => 2, 4 => 3, 3 => 4, 0 => 5]);
	}

	public function testReverseSortPairs() {
		$a = [5, 2, 1, 4, 3];
		a::reverseSortPairs($a);
		$this->eq($a, [0 => 5, 3 => 4, 4 => 3, 1 => 2, 2 => 1]);
	}

	public function testSortKeys() {
		$a = [
			'd' => 1,
			'a' => 2,
			'e' => 3,
			'b' => 4,
			'c' => 5
		];
		a::sortKeys($a);
		$this->eq($a, [
			'a' => 2,
			'b' => 4,
			'c' => 5,
			'd' => 1,
			'e' => 3
		]);
		$a = [
			'D' => 1,
			'a' => 2,
			'e' => 3,
			'B' => 4,
			'c' => 5
		];
		a::sortKeys($a, 'strcasecmp');
		$this->eq($a, [
			'a' => 2,
			'B' => 4,
			'c' => 5,
			'D' => 1,
			'e' => 3
		]);
	}

	public function testHumanSortValues() {
		$a = [
			'file10.txt',
			'file1.txt',
			'file3.txt',
			'file25.txt',
			'file2.txt'
		];
		a::humanSortValues($a);
		$this->eq($a, [
			1 => 'file1.txt',
			4 => 'file2.txt',
			2 => 'file3.txt',
			0 => 'file10.txt',
			3 => 'file25.txt'
		]);
	}

	public function testIHumanSortValues() {
		$a = [
			'File10.txt',
			'file1.txt',
			'FILE3.TXT',
			'file25.txt',
			'file2.txt'
		];
		a::iHumanSortValues($a);
		$this->eq($a, [
			1 => 'file1.txt',
			4 => 'file2.txt',
			2 => 'FILE3.TXT',
			0 => 'File10.txt',
			3 => 'file25.txt'
		]);
	}

	public function testLowerKeys() {
		$this->eq(a::lowerKeys([
			'Alpha' => 1,
			'BETA' => 2,
			'gamma' => 3
		]), [
			'alpha' => 1,
			'beta' => 2,
			'gamma' => 3
		]);
	}

	public function testUpperKeys() {
		$this->eq(a::upperKeys([
			'Alpha' => 1,
			'BETA' => 2,
			'gamma' => 3
		]), [
			'ALPHA' => 1,
			'BETA' => 2,
			'GAMMA' => 3
		]);
	}

	public function testIsSequential() {
		$this->eq(a::isSequential([]), true);
		$this->eq(a::isSequential(123), false);
		$this->eq(a::isSequential([1, 2, 3]), true);
		$this->eq(a::isSequential([0 => 1, 1 => 2, 2 => 3]), true);
		$this->eq(a::isSequential(['a' => 1, 'b' => 2]), false);
		$this->eq(a::isSequential([0 => 1, 2 => 2, 1 => 3]), false);
	}

	public function testIsAssociative() {
		$this->eq(a::isAssociative([]), true);
		$this->eq(a::isAssociative(123), false);
		$this->eq(a::isAssociative([1, 2, 3]), false);
		$this->eq(a::isAssociative([0 => 1, 1 => 2, 2 => 3]), false);
		$this->eq(a::isAssociative(['a' => 1, 'b' => 2]), true);
		$this->eq(a::isAssociative([0 => 1, 2 => 2, 1 => 3]), true);
	}

	public function testCountValues() {
		$this->eq(a::countValues([1, 2, 2, 1, 1]), [1 => 3, 2 => 2]);
		$this->eq(a::countValues([]), []);
	}
}
