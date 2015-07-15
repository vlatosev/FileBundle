<?php
namespace EDV\FileBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use EDV\FileBundle\Form\Transformers\FileReplaceTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\File;

class FileUploadType extends AbstractType
{
  /**
   * @var User|null
   */
  protected $user = null;

  /**
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  protected $em;

  public function __construct(SecurityContextInterface $context, EntityManagerInterface $em)
  {
    $user = $context->getToken()->getUser();
    if($user instanceof UserInterface)
    {
      $this->user = $user;
    }
    $this->em   = $em;
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
        ->add('upload_file',   'file', ['label' => false] /* array_merge(array('label' => false), $file_options)*/)
        ->add('file_id', 'hidden', [
          'label' => false,
          'attr' => [
              'data-file-class' => $options['file_sub_class']
          ]
        ])
        ->add('file_namespace', 'hidden', [
            'label' => false,
            'data'  => $options['file_namespace']
        ])
        ->addViewTransformer(new FileReplaceTransformer($this->user, $this->em, $options['file_sub_class']));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'file_namespace'    => 'general-files',
      'file_type'         => null,
      'label'             => false,
      'file_sub_class'    => null,
      'attr'              => ['class' => 'js-upload-widget']
    ));
  }
}