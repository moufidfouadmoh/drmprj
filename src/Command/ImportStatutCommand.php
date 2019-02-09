<?php

namespace App\Command;

use App\Entity\Statut;
use App\Manager\StatutManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportStatutCommand extends Command
{
    //protected static $defaultName = 'ImportCModeleCommand';
    /**
     * @var StatutManagerInterface
     */
    private $manager;
    /**
     * @var ConvertCsvToArray
     */
    private $csvToArray;

    public function __construct(ConvertCsvToArray $csvToArray, StatutManagerInterface $manager)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->csvToArray = $csvToArray;
    }

    protected function configure()
    {
        $this
            ->setName('app:create:statut')
            ->setDescription('Import statuts depuis CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->get($input, $output);

        $size = count($data);
        $progress = new ProgressBar($output, $size);
        $progress->start();
        foreach($data as $row) {
            $object = $this->manager->getStatutByNom($row['statut']);
            if(is_null($object)){
                $statut = new Statut();
                $statut->setNom($row['statut']);
                $statut->setEtat(true);
                $this->manager->save($statut);
            }
        }
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'bin/database/import/statut.csv';
        $data = $this->csvToArray->convert($fileName, ';');
        return $data;
    }


}
