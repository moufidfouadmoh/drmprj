<?php

namespace App\Command;


use App\Entity\Article;
use App\Manager\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Faker;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImportArticleCommand extends Command
{

    /**
     * @var UserManagerInterface
     */
    private $userManager;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(UserManagerInterface $userManager,EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->userManager = $userManager;
        $this->em = $em;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setName('app:create:article')
            ->setDescription('Import articles')
            ->setDefinition(array(
                new InputArgument('title', InputArgument::REQUIRED, 'Le titre de l\'article'),
                new InputArgument('user', InputArgument::REQUIRED, 'Le nom d\'utilisateur')
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $faker = Faker\Factory::create('fr_FR');

        $title = $input->getArgument('title');
        $username = $input->getArgument('user');
        $user = $this->userManager->getUserByUsernameOrEmail($username);

        $article = new Article();
        $article->setTitle($title);
        $article->setUser($user);
        $article->setContent($faker->realText(2000));

        $errors = $this->validator->validate($article);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }


        $this->em->persist($article);
        $this->em->flush();

        $output->writeln(sprintf('Created article %s', $title));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('title')) {
            $question = new Question('Donner un titre:');
            $questions['title'] = $question;
        }

        if (!$input->getArgument('user')) {
            $question = new Question('Choisir un nom d\'utilisateur:');
            $questions['user'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}