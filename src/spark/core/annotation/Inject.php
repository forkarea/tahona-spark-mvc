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
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class Inject {

    /** @var string */
    public $name = "";

} 