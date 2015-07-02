<?php
/**
 * @author Portey Vasil <portey@gmail.com>
 * Date: 01.07.15
 */

namespace Youshido\UploadableBundle\Listener;


use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Youshido\UploadableBundle\Annotations\Reader\UploadableReader;
use Youshido\UploadableBundle\Manager\FileManager;

class UploadableListener implements EventSubscriber
{

    /**
     * @var UploadableReader
     */
    private $reader;

    /**
     * @var FileManager
     */
    private $fileManager;

    public function __construct(UploadableReader $reader, FileManager $fileManager)
    {
        $this->reader = $reader;
        $this->fileManager = $fileManager;
    }

    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'postRemove',
            'preUpdate'
        );
    }

    public function preUpdate(EventArgs $args)
    {
        $this->processUpdateCreateEvent($args);
    }

    public function prePersist(EventArgs $args)
    {
        $this->processUpdateCreateEvent($args);
    }

    private function processUpdateCreateEvent(EventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity){
            $holders = $this->reader->readAndReturnHolders($entity);

            if($holders && is_array($holders)){
                $this->fileManager->processFromListener($holders, $args);
            }
        }
    }

    public function postRemove(EventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity){
            $holders = $this->reader->readAndReturnHolders($entity);

            if($holders && is_array($holders)){
                $this->fileManager->removeByHolders($holders);
            }
        }
    }
}