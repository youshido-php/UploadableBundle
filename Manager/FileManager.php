<?php
/**
 * Date: 01.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\UploadableBundle\Manager;


use Doctrine\Common\EventArgs;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Youshido\UploadableBundle\Holder\UploadParametersHolder;
use Youshido\UploadableBundle\Tools\Namer\NamerInterface;

class FileManager extends ContainerAware
{

    private $baseUploadDir;

    /** @var  NamerInterface */
    private $namer;

    public function __construct(NamerInterface $namer)
    {
        $this->namer = $namer;
    }

    /**
     * @param UploadParametersHolder[] $holders
     * @param EventArgs|PreUpdateEventArgs $args
     */
    public function process(array $holders, EventArgs $args)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($holders as $holder) {
            $entity = $holder->getEntity();

            $isOverride = false;
            if ($holder->getAnnotation()->isOverride() && ($id = $holder->getEntity()->getId())) {
                $changeSet = $args->getEntityChangeSet();

                if ($changeSet && array_key_exists($holder->getPropertyName(), $changeSet)) {
                    $originalPath = $changeSet[$holder->getPropertyName()][0];

                    $directory = dirname($originalPath);
                    $name = basename($originalPath);

                    $relativePath = $this->moveFile($holder, $directory, $name);

                    $isOverride = true;
                }
            }

            if(!$isOverride){
                $relativePath = $this->processOne($holder);
            }

            $propertyAccessor->setValue($entity, $holder->getPropertyName(), $relativePath);
        }
    }

    private function processOne(UploadParametersHolder $holder)
    {
        $directory = $this->namer->getDirectory($holder);

        if($holder->getAnnotation()->getUploadDir()){
            $entityUploadDir = $holder->getAnnotation()->getUploadDir();
        }else{
            $entityUploadDir = Inflector::tableize(get_class($holder->getEntity())).DIRECTORY_SEPARATOR.Inflector::tableize($holder->getPropertyName());
            $entityUploadDir = str_replace('\\', DIRECTORY_SEPARATOR, $entityUploadDir);

            $entityUploadDir = trim($holder->getAnnotation()->getPrefix(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $entityUploadDir;
        }

        $directory = trim($entityUploadDir, '/') . DIRECTORY_SEPARATOR . $directory;

        $name = $this->namer->getName($holder);

        return $this->moveFile($holder, $directory, $name);
    }

    private function moveFile(UploadParametersHolder $holder, $directory, $name)
    {
        $holder->getValue()->move(sprintf('%s/%s', $this->baseUploadDir, $directory), $name);

        return sprintf('%s/%s', $directory, $name);
    }

    public function setBaseUploadDir($baseUploadDir)
    {
        $this->baseUploadDir = rtrim($baseUploadDir, '/');
    }

    /**
     * @param UploadParametersHolder[] $holders
     */
    public function removeFilesOfEntity(array $holders)
    {
        foreach ($holders as $holder) {
            $fullPath = $this->baseUploadDir . DIRECTORY_SEPARATOR . $holder->getValue();

            $this->removeFile($fullPath);
        }
    }

    private function removeFile($fullPath)
    {
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

}