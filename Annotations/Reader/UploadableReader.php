<?php
/**
 * @author Portey Vasil <portey@gmail.com>
 * Date: 01.07.15
 */

namespace Youshido\UploadableBundle\Annotations\Reader;


use Doctrine\Common\Annotations\AnnotationReader;
use Youshido\UploadableBundle\Annotations\Uploadable;
use Youshido\UploadableBundle\Holder\UploadParametersHolder;

class UploadableReader
{

    private $annotationClass = 'Youshido\UploadableBundle\Annotations\Uploadable';

    /**
     * @param $object
     * @return UploadParametersHolder[]
     */
    public function readAndReturnHolders($object)
    {
        $reader = new AnnotationReader();

        $holders = [];
        $reflectionObj = new \ReflectionObject($object);

        foreach($reflectionObj->getProperties() as $property)
        {
            /** @var Uploadable $annotation */
            $annotation = $reader->getPropertyAnnotation($property, $this->annotationClass);

            if($annotation){
                $property->setAccessible(true);
                $value = $property->getValue($object);

                $holder = new UploadParametersHolder();

                $holder
                    ->setAnnotation($annotation)
                    ->setEntity($object)
                    ->setValue($value)
                    ->setPropertyName($property->getName());

                $holders[] = $holder;
            }
        }

        return $holders;
    }

    /**
     * @param $class
     * @param $property
     * @return null|Uploadable
     */
    public function readAnnotationOfProperty($class, $property)
    {
        try{
            $reader = new AnnotationReader();

            $propertyReflection = new \ReflectionProperty($class, $property);

            return $reader->getPropertyAnnotation($propertyReflection, $this->annotationClass);
        }catch (\Exception $e){
            return null;
        }
    }

}