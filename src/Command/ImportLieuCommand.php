<?php

namespace App\Command;

use App\Entity\Lieu;
use App\Manager\LieuManagerInterface;
use App\Utils\Type\Choice\IleChoiceType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportLieuCommand extends Command
{
    //protected static $defaultName = 'ImportCModeleCommand';
    /**
     * @var LieuManagerInterface
     */
    private $manager;
    /**
     * @var ConvertCsvToArray
     */
    private $csvToArray;

    public function __construct(ConvertCsvToArray $csvToArray, LieuManagerInterface $manager)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->csvToArray = $csvToArray;
    }

    protected function configure()
    {
        $this
            ->setName('app:create:lieu')
            ->setDescription('Import lieux depuis CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->get($input, $output);

        $size = count($data);
        $progress = new ProgressBar($output, $size);
        $progress->start();
        foreach($data as $row) {
            $object = $this->manager->getLieuByNom($row['nom']);
            if(is_null($object)){
                $ville = new Lieu();
                $ville->setNom($row['nom']);
                switch ($row['ile_id']){
                    case 1:
                        $ville->setIle(IleChoiceType::NGAZIDJA);break;
                    case 2:
                        $ville->setIle(IleChoiceType::NDZUANI);break;
                    case 3:
                        $ville->setIle(IleChoiceType::MWALI);break;
                }

                $this->manager->save($ville);
            }
        }
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'bin/database/import/ville.csv';
        $data = $this->csvToArray->convert($fileName, ';');
        return $data;
    }


}
