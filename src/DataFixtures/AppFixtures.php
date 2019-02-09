<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loacUsers($manager);
    }

    private function loacUsers(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($k=0; $k < 25; $k++) {
            $users[$k] = new User();
            $users[$k]
                ->setUsername('000'.$k)
                ->setPassword($this->encoder->encodePassword($users[$k],'000'.$k))
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setEmail($faker->email)
                ->setTelephone1($faker->phoneNumber)
                ->setSexe($faker->randomElement(array('M','F')))
                ->setDatenaissance($faker->dateTimeBetween())
                //->setEnabled(true)
            ;
            $manager->persist($users[$k]);
        }

        $manager->flush();
    }
}
