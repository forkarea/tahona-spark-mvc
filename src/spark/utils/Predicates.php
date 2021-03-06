<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 26.03.15
 * Time: 20:20
 */

namespace spark\utils;


class Predicates {

    public static function alwaysTrue() {
        return function ($obj) {
            return true;
        };
    }

    public static function notNull() {
        return function ($obj) {
            return Objects::isNotNull($obj);
        };
    }

    public static function notEmpty() {
        return function ($obj) {
            return Collections::isNotEmpty($obj);
        };
    }

    public static function not(\Closure $pred) {
        return function ($obj) use ($pred) {
            return false === $pred($obj);
        };
    }

    public static function hasArrayKey($field) {
        return function ($arr) use ($field) {
            return Collections::isNotEmpty($arr) && Collections::hasKey($arr, $field);
        };
    }

    public static function contains($defined = array()) {
        return function ($x) use ($defined) {
            return Collections::contains($x, $defined);
        };
    }

    public static function compute(\Closure $function, \Closure $predicate) {
        return function ($x) use ($function, $predicate) {
            return $predicate($function($x));
        };
    }

    public static function equals($defined) {
        return function ($x) use ($defined) {
            return $x == $defined;
        };
    }

} 