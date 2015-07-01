<?php
/**
 * Date: 01.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\UploadableBundle\Tools\Namer;


use Youshido\UploadableBundle\Holder\UploadParametersHolder;
use Youshido\UploadableBundle\Tools\ExtensionGuesser;

class Md5HashNamer implements NamerInterface
{

    public function getDirectory(UploadParametersHolder $parametersHolder)
    {
        return sprintf('%s/%s', $this->generateRandomDirectoryName(), $this->generateRandomDirectoryName());
    }

    public function getName(UploadParametersHolder $parametersHolder)
    {
        return sprintf('%s.%s', md5(time()), ExtensionGuesser::guess($parametersHolder->getValue()->getMimeType()));
    }

    private function generateRandomDirectoryName($directoryNameLength = 2)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $directoryNameLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}