<?php



namespace App\Command;


use App\Entity\Categorie;
use App\Manager\CategorieManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCategorieCommand extends Command
{

    /**
     * @var ConvertCsvToArray
     */
    private $csvToArray;
    /**
     * @var CategorieManagerInterface
     */
    private $manager;

    public function __construct(ConvertCsvToArray $csvToArray, CategorieManagerInterface $manager)
    {
        parent::__construct();
        $this->csvToArray = $csvToArray;
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setName('app:create:categorie')
            ->setDescription('Import categories depuis CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->get($input, $output);

        $size = count($data);
        $progress = new ProgressBar($output, $size);
        $progress->start();
        foreach($data as $row) {
            $object = $this->manager->getCategorieByNom($row['categorie']);
            if(is_null($object)){
                $categorie = new Categorie();
                $categorie->setNom($row['categorie']);
                $categorie->setEtat(true);
                $this->manager->save($categorie);
            }
        }
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'bin/database/import/categorie.csv';
        $data = $this->csvToArray->convert($fileName, ';');
        return $data;
    }

}