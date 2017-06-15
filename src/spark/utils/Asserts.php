<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 09.10.14
 * Time: 08:02
 */

namespace spark\utils;


use spark\common\IllegalArgumentException;
use spark\common\IllegalStateException;
use spark\utils\Objects;

class Asserts {

    /**
     *
     * @param $object
     * @param string $message
     * @throws IllegalArgumentException
     */
    public static function notNull($object, $message = "Object cannot be null or undefined.") {
        if (false == isset($object) || is_null($object)) {
            throw new IllegalArgumentException($message);
        }
    }

    /**
     * @deprecated
     * @param $object
     * @param string $message
     * @throws \spark\common\IllegalArgumentException
     */
    public static function isArray($object, $message = "Object is not an array") {
        if (is_null($object) || false === is_array($object)) {
            throw new IllegalArgumentException($message);
        }
    }

    /**
     * @param $object
     * @param string $message
     * @throws \spark\common\IllegalArgumentException
     */
    public static function checkArray($object, $message = "Object is not an array") {
        if (is_null($object) || false === is_array($object)) {
            throw new IllegalArgumentException($message);
        }
    }

    public static function checkArgument($bool, $message = "Invalid argument") {
        if (false === $bool) {
            throw new IllegalArgumentException($message);
        }
    }

    public static function checkState($bool, $message = 'Invaid state (should be "true")') {
        if (false === $bool) {
            throw new IllegalStateException($message);
        }
    }
}