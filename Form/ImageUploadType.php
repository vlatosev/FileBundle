<?php
namespace ED\FileBundle\Form;

use ED\FileBundle\Entity\EdImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;

class ImageUploadType extends AbstractType
{
  public function getName()
  {
    return 'image_upload_widget';
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('file', 'upload_widget', array(
        'file_namespace' => $options['file_namespace'],
        'file_type' => 'image/*',
        'label' => false,
        'required' => false
    ));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'ED\FileBundle\Entity\EdImage',
        'file_namespace' => 'general-images'
    ));
  }
}