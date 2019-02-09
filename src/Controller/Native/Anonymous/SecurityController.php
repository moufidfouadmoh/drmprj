<?php

namespace App\Controller\Native\Anonymous;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/login",name="security_login")
     * @param AuthenticationUtils $utils
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function login(AuthenticationUtils $utils)
    {
        $html = $this->twig->render('Anonymous/security/login.html.twig',[
            'last_username' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError()
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