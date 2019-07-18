<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\News;

class AddnewsCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'addnews';

    protected function configure()
    {
        $this
            ->setName('addnews')
            ->setHelp('Usage: php bin/console addnews "This Title News" "This text news"')
            ->setDescription('This command add news to DB')
            ->addArgument('title', InputArgument::REQUIRED, 'News Title')
            ->addArgument('text', InputArgument::REQUIRED, 'News Text');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Добавляется новость со следующими параметрами:',
        ]);

        $title = $input->getArgument('title');
        $text = $input->getArgument('text');

        $output->writeln('Title : ' . $title);
        $output->writeln('Text : ' . $text);

        $news = new News();
        $news->setTitle($title);
        $news->setText($text);
        $news->setCreated(new \DateTime());

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($news);
        $entityManager->flush();

        $output->writeln([
            'Новость успешно добавлена',
        ]);
    }
}
