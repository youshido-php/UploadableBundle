<?php
/**
 * Date: 01.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\UploadableBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Youshido\UploadableBundle\Annotations\Reader\UploadableReader;

class FileType extends AbstractType
{

    /** @var  UploadableReader */
    public $reader;

    /** @var  ValidatorInterface */
    public $validator;

    public function __construct(UploadableReader $reader, ValidatorInterface $validator)
    {
        $this->reader = $reader;
        $this->validator = $validator;
    }

    /**
     * @param FormBuilder|FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new FileTypeDataTransformer());

        $reader = $this->reader;
        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($builder, $reader, $options) {
                $form = $event->getForm();
                $data = $form->getData();
                $config = $form->getConfig();

                $name = array_key_exists('entity_property', $options) && $options['entity_property'] ? $options['entity_property'] : $config->getName();

                $annotation = $reader->readAnnotationOfProperty($options['entity_class'], $name);

                if ($annotation && is_array($annotation->getAsserts())) {
                    $errors = $this->validator->validate($data, $annotation->getAsserts());

                    if (count($errors) > 0) {
                        foreach($errors as $error){
                            $form->addError(new FormError($error->getMessage()));
                        }
                    }
                }
            });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['entity_class']);

        $resolver->setDefaults([
            'entity_property' => false
        ]);
    }

    public function getParent()
    {
        return 'file';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'youshido_file';
    }
}