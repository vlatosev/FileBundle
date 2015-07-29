<?php
namespace EDV\FileBundle\Form;

use EDV\FileBundle\Form\Transformers\ImageUploadTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageUploadType extends AbstractType
{
  private $image_class;

  public function __construct($imgClass)
  {
    $this->image_class = $imgClass;
  }

  public function getName()
  {
    return 'image_upload_widget';
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('file', 'upload_widget', [
            'file_namespace' => $options['file_namespace'],
            'image_type' => true,
            'label' => false,
            'required' => false
        ])->addModelTransformer(new ImageUploadTransformer());
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults([
        'data_class' => $this->image_class,
        'file_namespace' => null,
        'cascade_validation' => true
    ]);
  }
}