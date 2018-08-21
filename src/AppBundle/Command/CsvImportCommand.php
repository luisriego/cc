<?php

namespace AppBundle\Command;

use AppBundle\Entity\Chamado;
use AppBundle\Entity\Defeito;
use AppBundle\Entity\Status;
use League\Csv\Reader;
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

        $reader = Reader::createFromPath('%kernel.root_dir%/../src/AppBundle/Data/defeito.csv');
//        $reader = Reader::createFromPath('%kernel.root_dir%/../src/AppBundle/Data/status.csv');

        $results = $reader->fetchAssoc();

        foreach ($results as $row) {
            $defeito = (new Defeito())
                ->setNome($row['nome'])
                ->setPrioridade($row['prioridade'])
            ;

            $this->em->persist($defeito);
        }

//        foreach ($results as $row) {
//            $status = (new Status())
//                ->setNome($row['nome'])
//                ->setSlug($row['slug'])
//                ->setCor($row['cor'])
//                ->setAtivo(true)
//            ;
//
//            $this->em->persist($status);
//        }

        $this->em->flush();

        $io->success('Tudo foi bem!');
    }
}