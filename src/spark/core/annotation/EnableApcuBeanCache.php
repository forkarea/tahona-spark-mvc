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
 * @Target({"CLASS"})
 */
final class EnableApcuBeanCache implements Mapping\Annotation {


    /** @var string */
    public $prefix = "";

    /** @var string */
    public $resetParam = null;

} 