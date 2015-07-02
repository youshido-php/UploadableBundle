# UploadableBundle
Bundle brings uploadable behavor for Symfony2 Doctrine Entities


### Install via Composer:
> composer require youshido/uploadable

### Enable in your AppKernel.php:

> new Youshido\UploadableBundle\UploadableBundle(),

### Add annotation to your entity:

``` php
use Youshido\UploadableBundle\Annotations as Youshido;

class Post
{
    
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     *
     * @Youshido\Uploadable(override="true", asserts={@Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )})
     */
    private $path;
```

### Use in controller:

``` php
$post = new Post();

$form = $this->createFormBuilder($post, ['action' => $this->generateUrl('example1')])
    ->add('path', 'youshido_file', ['entity_class' => 'AppBundle\Entity\Post'])
    ->add('submit', 'submit')
    ->getForm();


$form->handleRequest($request);

if($form->isValid()){
    $this->getDoctrine()->getManager()->persist($post);
    $this->getDoctrine()->getManager()->flush();
}
```

``` php
if($request->getMethod() == 'POST'){
    if($file = $request->files->get('path')){
        if($post = $this->getDoctrine()->getRepository('AppBundle:Post')->find(37)){
            $this->get('youshido.uploadable.enity_manager')->saveFile($post, 'path', $file, true);
        }
    }
}
```