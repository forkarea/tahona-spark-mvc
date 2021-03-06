<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 14.07.14
 * Time: 00:17
 */

namespace spark\core\annotation;

use Doctrine\Common\Annotations\Annotation\Target;



/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
final class Path {

    /** @var string */
    public $path = "";
    /** @var array */
    public $method = array();
    /** @var array */
    public $header = array();
//    /**
//     * @var array
//     */
//    public $params = array();

}