<?php
/**
 * Date: 02.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\UploadableBundle\Manager;


use Youshido\UploadableBundle\Annotations\Reader\UploadableReader;
use Youshido\UploadableBundle\Holder\UploadParametersHolder;

class EntityManager
{

    /**
     * @var UploadableReader
     */
    private $reader;

    /** @var FileManager */
    private $fileManager;

    public function __construct(UploadableReader $reader, FileManager $fileManager)
    {
        $this->reader = $reader;
        $this->fileManager = $fileManager;
    }

    public function removeFiles($entity)
    {
        $holders = $this->reader->readAndReturnHolders($entity);

        if($holders && is_array($holders)){
            $this->fileManager->removeByHolders($holders);
        }
    }

    public function removeFile($entity, $property)
    {
        $holders = $this->reader->readAndReturnHolders($entity);

        if($holders && is_array($holders)){
            foreach($holders as $holder){
                /** @var UploadParametersHolder $holder */
                if($holder->getPropertyName() == $property){
                    $this->fileManager->removeByHolders([$holder]);

                    break;
                }
            }
        }
    }

}