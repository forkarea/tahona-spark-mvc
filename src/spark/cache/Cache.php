<?php
/**
 * Created by PhpStorm.
 * User: crownclown67
 * Date: 03.06.17
 * Time: 14:48
 */

namespace spark\cache;


interface Cache {

    public function put($key, $object);
    public function get($key);
    public function has($key);


}