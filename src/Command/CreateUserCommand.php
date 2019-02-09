<?php

namespace App\Command;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Faker;

class CreateUserCommand extends Command
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->encoder = $encoder;
        $this->em = $em;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setName('app:create:user')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'Le nom d\'utilisateur'),
                new InputArgument('email', InputArgument::REQUIRED, 'L\'email'),
                new InputArgument('nom', InputArgument::REQUIRED, 'Le nom'),
                new InputArgument('prenom', InputArgument::REQUIRED, 'Le prenom'),
                new InputArgument('sexe', InputArgument::REQUIRED, 'Le sexe'),
                new InputArgument('telephone1', InputArgument::REQUIRED, 'Le telephone')
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $faker = Faker\Factory::create('fr_FR');
        $user = new User();

        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');
        $sexe = $input->getArgument('sexe');
        $telephone1 = $input->getArgument('telephone1');
        $password = $this->encoder->encodePassword($user, $username);

        $user->setUsername($username);
        $user->setPlainPassword($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setSexe($sexe);
        $user->setTelephone1($telephone1);
        $user->setDatenaissance($faker->dateTimeBetween());

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }


        $this->em->persist($user);
        $this->em->flush();

        $output->writeln(sprintf('Created user %s', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Choisir un nom d\'utilisateur:');
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Choisir un email:');
            $questions['email'] = $question;
        }

        if (!$input->getArgument('nom')) {
            $question = new Question('Choisir un nom:');
            $questions['nom'] = $question;
        }

        if (!$input->getArgument('prenom')) {
            $question = new Question('Choisir un prenom:');
            $questions['prenom'] = $question;
        }

        if (!$input->getArgument('sexe')) {
            $question = new Question('Choisir un sexe:');
            $questions['sexe'] = $question;
        }

        if (!$input->getArgument('telephone1')) {
            $question = new Question('Choisir un telephone:');
            $questions['telephone1'] = $question;
        }



        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}