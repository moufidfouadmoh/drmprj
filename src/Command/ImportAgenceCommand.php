<?php

namespace App\Command;

use App\Entity\Agence;
use App\Manager\AgenceManagerInterface;
use App\Manager\LieuManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportAgenceCommand extends Command
{
    /**
     * @var ConvertCsvToArray
     */
    private $csvToArray;
    /**
     * @var AgenceManagerInterface
     */
    private $agenceManager;
    /**
     * @var LieuManagerInterface
     */
    private $lieuManager;


    public function __construct(ConvertCsvToArray $csvToArray, AgenceManagerInterface $agenceManager,LieuManagerInterface $lieuManager)
    {
        parent::__construct();
        $this->csvToArray = $csvToArray;
        $this->agenceManager = $agenceManager;
        $this->lieuManager = $lieuManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:create:agence')
            ->setDescription('Import agences depuis CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->get($input, $output);

        $size = count($data);
        $progress = new ProgressBar($output, $size);
        $progress->start();

        foreach($data as $row) {
            $object = $this->agenceManager->getAgenceByNom($row['nom']);
            if(is_null($object)){
                $agence = new Agence();
                $agence->setNom($row['nom']);
                $lieu = $this->lieuManager->getRepository()->find($row['ville_id']);
                if(!is_null($lieu)){
                    $agence->setLieu($lieu);
                    $this->agenceManager->save($agence);
                }
            }
        }
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'bin/database/import/siege.csv';
        $data = $this->csvToArray->convert($fileName, ';');
        return $data;
    }


}
