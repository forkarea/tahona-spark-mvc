<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 14.07.14
 * Time: 00:17
 */

namespace spark\core\annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;


/**
 * @Annotation
 * @Component
 * @Target({"CLASS"})
 */
final class Configuration  implements Mapping\Annotation {

    /** @var string */
    public $name = "";

} 