<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TDM\Escher\Collection;

/**
 * CollectionTest
 * Tests the Collection object
 *
 * @covers Collection
 */
final class CollectionTest extends TestCase
{
    public function testMap()
    {
        $result = Collection::map([1, 2, 3], function ($n) {
            return $n * 2;
        });

        $this->assertEquals($result, [2, 4, 6]);
    }

    public function testPick()
    {
        $array = ["foo" => "bar", "baz" => "baf"];

        // String key
        $result = Collection::pick($array, "foo");
        $this->assertEquals($result, ["foo" => "bar"]);

        // Array key
        $result = Collection::pick($array, ["foo"]);
        $this->assertEquals($result, ["foo" => "bar"]);

        // Key which doesn't appear should return empty array
        $result = Collection::pick($array, ["quuz"]);
        $this->assertEquals($result, []);
    }

    public function testOmit()
    {
        $array = ["foo" => "bar", "baz" => "baf"];

        $result = Collection::omit($array, "foo");
        $this->assertEquals($result, ["baz" => "baf"]);

        $result = Collection::omit($array, ["foo"]);
        $this->assertEquals($result, ["baz" => "baf"]);

        $result = Collection::omit($array, ["quuz"]);
        $this->assertEquals($result, $array);
    }

    public function testSlice()
    {
        $result = Collection::slice([1, 2, 3, 4], 2, 2);
        $this->assertEquals($result, [3, 4]);

        $result = Collection::slice([1, 2, 3, 4], 2, 2, YES);
        $this->assertEquals($result, [2 => 3, 3 => 4]);
    }

    public function testReduce()
    {
        $result = Collection::reduce([1, 2, 3, 4, 5], function ($carry, $item) {
            return $carry * $item;
        });
        $this->assertEquals($result, 120);

        $result = Collection::reduce([1, 2, 3, 4, 5], function ($carry, $item) {
            return $carry * $item;
        }, 5);
        $this->assertEquals($result, 600);
    }

    public function testFilter()
    {
        $result = Collection::filter([1, 2, 3, 4, 5], function ($item) {
            return $item >= 3;
        });
        $this->assertEquals($result, [3, 4, 5]);
    }

    public function testHas()
    {
        $result = Collection::has([1, 2, 3], 3);
        $this->assertEquals($result, YES);

        $result = Collection::has([1, 2, 3], 4);
        $this->assertEquals($result, NO);
    }

    public function testHasKey()
    {
        $result = Collection::hasKey(["foo" => "bar"], "foo");
        $this->assertEquals($result, YES);

        $result = Collection::hasKey(["foo" => "bar"], "bar");
        $this->assertEquals($result, NO);
    }

    public function testFlatten()
    {
        $array = [[1, 2, 3], [4], [5, 6, 7]];
        $result = Collection::flatten($array);
        $this->assertEquals($result, [1, 2, 3, 4, 5, 6, 7]);

        $array = [[1, 2, 3], [4], [5, [6, 7]]];
        $result = Collection::flatten($array, YES);
        $this->assertEquals($result, [1, 2, 3, 4, 5, 6, 7]);
    }

    public function testFind()
    {
        $array = [["foo", "bar"], ["foo", "baz"], ["baz", "baf"]];

        $result = Collection::find($array, function ($item) {
            return $item[0] === "foo";
        });
        $this->assertEquals($result, ["foo", "bar"]);

        $result = Collection::find($array, function ($item) {
            return $item[1] === "baz";
        });
        $this->assertEquals($result, ["foo", "baz"]);

        $result = Collection::find($array, function ($item) {
            return $item[0] === "quuz";
        });
        $this->assertEquals($result, null);
    }

    public function testEvery()
    {
        $array = [2, 4, 6, 8];

        $result = Collection::every($array, function ($item) {
            return $item % 2 === 0;
        });
        $this->assertEquals($result, YES);

        $result = Collection::every($array, function ($item) {
            return $item === 2;
        });
        $this->assertEquals($result, NO);
    }

    public function testSome()
    {
        $array = [2, 4, 6, 8];

        $result = Collection::some($array, function ($item) {
            return $item % 2 === 0;
        });
        $this->assertEquals($result, YES);

        $result = Collection::some($array, function ($item) {
            return $item === 2;
        });
        $this->assertEquals($result, YES);

        $result = Collection::some($array, function ($item) {
            return $item % 2 === 1;
        });
        $this->assertEquals($result, NO);
    }
}
