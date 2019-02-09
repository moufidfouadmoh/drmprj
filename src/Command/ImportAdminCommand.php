<?php


namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Type\Choice\SexeChoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportAdminCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder, UserRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->manager = $manager;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setName('app:create:admin')
            ->setDescription('Import admin')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $admin = $this->buildAdmin();
        $user = $this->repository->findOneBy([
            'username' => $admin->getUsername()
        ]);
        if(is_null($user)){
            $admin->setPassword($this->encoder->encodePassword($admin,$admin->getUsername()));
            $this->manager->persist($admin);

            //$output->writeln($admin->getUsername());
        }else{
            $user->setPassword($this->encoder->encodePassword($user,'123456'));
            $output->writeln('admin déjà créé');
        }
        $this->manager->flush();
    }

    private function buildAdmin()
    {
        $superadmin = new User();
        $superadmin->setUsername('admin');
        $superadmin->setNom('Admin');
        $superadmin->setEmail('admin@email.com');
        $superadmin->setSexe(SexeChoiceType::SEXE_MASCULIN);
        $superadmin->setDatenaissance(new \DateTime());
        $superadmin->setTelephone1('');
        $superadmin->setRoles(array(User::ROLE_SUPER_ADMIN));
        $superadmin->setEnabled(true);

        return $superadmin;
    }
}