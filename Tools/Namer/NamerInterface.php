<?php
/**
 * Date: 01.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\UploadableBundle\Tools\Namer;


use Youshido\UploadableBundle\Holder\UploadParametersHolder;

interface NamerInterface {

    public function getDirectory(UploadParametersHolder $parametersHolder);

    public function getName(UploadParametersHolder $parametersHolder);
}