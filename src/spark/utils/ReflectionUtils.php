<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 13.07.14
 * Time: 19:31
 */

namespace spark\utils;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use spark\core\annotation\Bean;
use spark\utils\Collections;
use spark\utils\Objects;

class ReflectionUtils {


    private static $ANNOTATION_READER;

    public static function setValue(&$bean, $property, &$value) {
        $className = get_class($bean);

        $reflectionProperty = new \ReflectionProperty($className, $property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($bean, $value);
    }


    public static function handlePropertyAnnotation(&$bean, $annotationName, \Closure $handler) {
        Asserts::notNull($bean);

        $annotationReader = self::getReaderInstance();

        $reflectionObject = new \ReflectionObject($bean);
        $reflectionProperties = $reflectionObject->getProperties();

        $fluentIterables = Collections::builder()
            ->addAll($reflectionProperties);

        $cls = $reflectionObject->getParentClass();

        while ($cls != null) {
            $fluentIterables->addAll($cls->getProperties());
            $cls = $cls->getParentClass();
        }

        $properties = $fluentIterables->get();

        $observersWaitingToInject = array();

        /** @var $properties \ReflectionProperty */
        foreach ($properties as $reflectionProperty) {
            $annotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $annotationName);

            if (false == is_null($annotation)) {
                $observer = $handler($bean, $reflectionProperty, $annotation);
                if (Objects::isNotNull($observer)) {
                    $observersWaitingToInject[$observer->getId()] = $observer;
                }
            }
        }

        return $observersWaitingToInject;
    }

    /**
     * @return AnnotationReader
     */
    public static function getReaderInstance() {
        if (false == isset(self::$ANNOTATION_READER)) {
            //RegisterAutoLoader for annotations
            AnnotationRegistry::registerLoader("class_exists");
            self::$ANNOTATION_READER = new AnnotationReader();
        }
        return self::$ANNOTATION_READER;
    }

    public static function handleMethodAnnotation($bean, $annotationName, \Closure $handler) {
        Asserts::notNull($bean);
        $annotationReader = self::getReaderInstance();

        $reflectionObject = new \ReflectionObject($bean);
        $reflectionMethods = $reflectionObject->getMethods();

        $fluentIterables = Collections::builder()
            ->addAll($reflectionMethods);

        $cls = $reflectionObject->getParentClass();

        while ($cls != null) {
            $fluentIterables->addAll($cls->getMethods());
            $cls = $cls->getParentClass();
        }
        $methods = $fluentIterables->get();

        /** @var $reflectionMethod \ReflectionMethod */
        foreach ($methods as $reflectionMethod) {
            $annotation = $annotationReader->getMethodAnnotation($reflectionMethod, $annotationName);

            if (!is_null($annotation)) {
                $handler($bean, $reflectionMethod, $annotation);
            }
        }
    }

    /**
     * @param $bean
     * @param $field
     * @param $annotationName
     * @return \Doctrine\Common\Annotations\The|null|object
     */
    public static function getPropertyAnnotation($fullClassName, $field, $annotationName) {
        $annotationReader = self::getReaderInstance();
        $reflectionObject = new \ReflectionClass($fullClassName);

        if ($reflectionObject->hasProperty($field)) {
            $reflectionProperty = $reflectionObject->getProperty($field);
            return $annotationReader->getPropertyAnnotation($reflectionProperty, $annotationName);
        }
        return null;

    }

    /**
     *
     * @param $fullClassName
     * @param $annotationName
     * @return array Annotations
     */
    public static function getClassAnnotations($fullClassName, $annotationName) {
        $annotationReader = self::getReaderInstance();
        $reflectionObject = new \ReflectionClass($fullClassName);
        return Collections::builder($annotationReader->getClassAnnotations($reflectionObject))
            ->filter(Functions::hasClassName($annotationName))
            ->get();
    }

}