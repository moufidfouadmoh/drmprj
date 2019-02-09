<?php

namespace App\Command;

use App\Entity\CModele;
use App\Manager\CModeleManagerInterface;
use App\Utils\Type\Choice\ModeleChoiceType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCModeleCommand extends Command
{
    /**
     * @var CModeleManagerInterface
     */
    private $modeleManager;

    public function __construct(CModeleManagerInterface $modeleManager)
    {
        parent::__construct();
        $this->modeleManager = $modeleManager;
    }


    protected function configure()
    {
        $this
            ->setName('app:create:cmodele')
            ->setDescription('Import cmodeles depuis CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->buildElements();

        $size = count($data);
        $progress = new ProgressBar($output, $size);
        $progress->start();
        foreach($data as $row) {
            $object = $this->modeleManager->getCModeleByNom($row->getNom());
            if(is_null($object)){
                $cmodele = $row;
                $this->modeleManager->save($cmodele);
            }
        }
        $progress->finish();
    }


    private function buildElements()
    {
        $elements = [];
        $m1 = new CModele();
        $m1->setNom('Congé Administratif');
        $m1->setEtat(true);
        $m1->setType(ModeleChoiceType::ADMINISTRATIF);
        $m1->setJustificatif(0);
        $m1->setService(true);
        $m1->setDepartement(true);
        $m1->setDirection(true);
        $m1->setFixe(false);
        $m1->setDelaimin(new \DateInterval('P00Y00M7DT00H00M00S'));
        $m1->setDelaimax(new \DateInterval('P00Y00M30DT00H00M00S'));

        $m2 = new CModele();
        $m2->setNom('Permission');
        $m2->setEtat(true);
        $m2->setType(ModeleChoiceType::ADMINISTRATIF);
        $m2->setJustificatif(0);
        $m2->setService(true);
        $m2->setDepartement(true);
        $m2->setDirection(false);
        $m2->setFixe(false);
        $m2->setDelaimax(new \DateInterval('P00Y00M07DT00H00M00S'));

        $m3 = new CModele();
        $m3->setNom('Décès d\'un époux');
        $m3->setEtat(true);
        $m3->setType(ModeleChoiceType::SPECIAL);
        $m3->setJustificatif(0);
        $m3->setService(true);
        $m3->setDepartement(true);
        $m3->setDirection(false);
        $m3->setFixe(true);
        $m3->setDelaimax(new \DateInterval('P00Y04M15DT00H00M00S'));

        $m4 = new CModele();
        $m4->setNom('Décès d\'une épouse');
        $m4->setEtat(true);
        $m4->setType(ModeleChoiceType::SPECIAL);
        $m4->setJustificatif(0);
        $m4->setService(true);
        $m4->setDepartement(true);
        $m4->setDirection(false);
        $m4->setFixe(true);
        $m4->setDelaimax(new \DateInterval('P00Y00M09DT00H00M00S'));

        $m5 = new CModele();
        $m5->setNom('Décès d\'un père, mère ou enfant');
        $m5->setEtat(true);
        $m5->setType(ModeleChoiceType::SPECIAL);
        $m5->setJustificatif(0);
        $m5->setService(true);
        $m5->setDepartement(true);
        $m5->setDirection(false);
        $m5->setFixe(true);
        $m5->setDelaimax(new \DateInterval('P00Y00M03DT00H00M00S'));

        $m6 = new CModele();
        $m6->setNom('Circoncision d\'un enfant');
        $m6->setEtat(true);
        $m6->setType(ModeleChoiceType::SPECIAL);
        $m6->setJustificatif(0);
        $m6->setService(true);
        $m6->setDepartement(true);
        $m6->setDirection(false);
        $m6->setFixe(true);
        $m6->setDelaimax(new \DateInterval('P00Y00M03DT00H00M00S'));

        $m7 = new CModele();
        $m7->setNom('Reconnaissance');
        $m7->setEtat(true);
        $m7->setType(ModeleChoiceType::RECONNAISSANCE);
        $m7->setJustificatif(0);
        $m7->setService(true);
        $m7->setDepartement(true);
        $m7->setDirection(false);
        array_push($elements,$m1,$m2,$m3,$m4,$m5,$m6,$m7);

        return $elements;

    }
}
