<?php
/**
 * Date: 01.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\UploadableBundle\Holder;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Youshido\UploadableBundle\Annotations\Uploadable;

class UploadParametersHolder
{

    /** @var  Uploadable */
    private $annotation;

    /** @var  UploadedFile */
    private $value;

    /** @var mixed */
    private $entity;

    /** @var string */
    private $propertyName;

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     * @return UploadParametersHolder
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     * @return UploadParametersHolder
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return UploadedFile|String
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param UploadedFile $value
     * @return UploadParametersHolder
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Uploadable
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @param Uploadable $annotation
     * @return UploadParametersHolder
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }
}