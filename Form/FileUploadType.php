<?php
namespace EDV\FileBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use EDV\FileBundle\Form\Transformers\FileUploadTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\File;

class FileUploadType extends AbstractType
{
  /**
   * @var UserInterface|null
   */
  protected $user = null;

  /**
   * @var string
   */
  protected $file_class;

  public function __construct(SecurityContextInterface $context, $file_class)
  {
    $user = $context->getToken()->getUser();
    if($user instanceof UserInterface)
    {
      $this->user = $user;
    }
    $this->file_class = $file_class;
  }

  public function getName()
  {
    return 'upload_widget';
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $file_options = !is_null($options['file_type']) ? array(
      'constraints' => new File(array('mimeTypes' => $options['file_type']))
    ) : array();
    $builder
        ->add('upload_file', 'file',  array_merge([
            'label' => false
        ], $file_options))
        ->addModelTransformer(new FileUploadTransformer($this->user, $options['file_namespace']))
//        ->add('file_id', 'hidden', [
//          'label' => false,
//          'attr' => [
//              'data-file-class' => $options['file_sub_class']
//          ]
//        ])
//        ->add('file_namespace', 'hidden', [
//            'label' => false,
//            'data'  => $options['file_namespace']
//        ])
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'file_namespace'    => 'general-files',
      'file_type'         => null,
      'data_class'        => $this->file_class,
      'label'             => false,
      'attr'              => ['class' => 'js-upload-widget']
    ));
  }
}