<?php

namespace App\Controller\Native\Anonymous;

use App\Entity\User;
use App\Form\Type\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegisterController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var FormFactoryInterface
     */
    private $form;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(\Twig_Environment $twig,FormFactoryInterface $form,RouterInterface $router)
    {
        $this->twig = $twig;
        $this->form = $form;
        $this->router = $router;
    }

    /**
     * @Route("/register",name="security_register")
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager)
    {
        $user = new User();
        $form = $this->form->create(UserFormType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $password = $encoder->encodePassword($user,$user->getUsername());
            $data->setPassword($password);
            $manager->persist($data);
            $manager->flush();
             return new RedirectResponse($this->router->generate('native.index'));
        }
        $html = $this->twig->render('Anonymous/security/register.html.twig',[
            'form' => $form->createView()
        ]);

        return new Response($html);
    }

    /**
     * @Route("/logout",name="security_logout")
     */
    public function logout()
    {

    }
}