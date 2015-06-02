<?php
namespace EDV\FileBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateSlugsCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('ed_file:update-db')
      ->setDescription('Remove file rows without file?');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $em = $this->getContainer()->get('doctrine.orm.entity_manager');
    $kernelRoot = $this->getContainer()->getParameter('kernel.root_dir');
    $ds = DIRECTORY_SEPARATOR;
    $upldir = $kernelRoot . $ds . '..' . $ds . 'uploads';
    $repo = $em->getRepository("EDFileBundle:EdFile");
    $files = $repo->findAll();
    foreach ($files as $file)
    {
      $filename = $upldir . $ds . $file->getFileNamespace() . $ds . $file->getId();
      $exists = file_exists($filename) ? 'yes' : 'no';
      echo $file->getName() . ' --- ' . $filename . " --> " . $exists . "\r\n";
      if($exists == 'no') $em->remove($file);
    }
    $em->flush();
    $output->writeln("Job finished");
  }
}