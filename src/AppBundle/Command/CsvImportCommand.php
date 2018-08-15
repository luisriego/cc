<?php

namespace AppBundle\Command;

use AppBundle\Entity\Defeito;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\inputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvImportCommand extends Command
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Importar un arquivo CSV de testes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Intentando importar...');

        $defeito = (new Defeito())
            ->setNome('petó feo')
            ->setPrioridade(2);

        $this->em->persist($defeito);

        $this->em->flush();

        $io->success('Tudo foi bem!');
    }
}