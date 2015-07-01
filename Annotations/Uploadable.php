<?php
/**
 * @author Portey Vasil <portey@gmail.com>
 * Date: 01.07.15
 */

namespace Youshido\UploadableBundle\Annotations;


use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Uploadable extends Annotation
{
    /** @var  string */
    public $uploadDir;

    /** @var array  */
    public $asserts = [];

    /** @var bool  */
    public $override = false;

    /**
     * @return mixed
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    /**
     * @param mixed $uploadDir
     */
    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * @return mixed
     */
    public function getAsserts()
    {
        return $this->asserts;
    }

    /**
     * @param mixed $asserts
     */
    public function setAsserts($asserts)
    {
        $this->asserts = $asserts;
    }

    /**
     * @return boolean
     */
    public function isOverride()
    {
        return $this->override;
    }

    /**
     * @param boolean $override
     */
    public function setOverride($override)
    {
        $this->override = $override;
    }
}