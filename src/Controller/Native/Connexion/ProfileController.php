<?php

namespace App\Controller\Native\Connexion;

use App\Entity\Includes\Resetting;
use App\Entity\User;
use App\Form\Type\ResettingFormType;
use App\Manager\UserManagerInterface;
use App\Utils\PrintAbleTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController
{
    use PrintAbleTrait;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var UserManagerInterface
     */
    private $userManager;
    /**
     * @var FormFactoryInterface
     */
    private $form;
    /**
     * @var PasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(\Twig_Environment $twig,
                                TokenStorageInterface $tokenStorage,
                                FormFactoryInterface $form,
                                TranslatorInterface $translator,
                                RouterInterface $router,
                                FlashBagInterface $flashBag,
                                KernelInterface $kernel,
                                UserPasswordEncoderInterface $encoder,
                                UserManagerInterface $userManager)
    {
        $this->twig = $twig;
        $this->tokenStorage = $tokenStorage;
        $this->userManager = $userManager;
        $this->form = $form;
        $this->encoder = $encoder;
        $this->translator = $translator;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->kernel = $kernel;
    }

    /**
     * @Route("/profile",name="security_profile")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function profile()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if($user instanceof User){
            $agent = $this->userManager->getUserBySlugWithDetail($user->getSlug());
            if(!is_null($agent)){
                $html = $this->twig->render('Connexion/Profile/show.html.twig',[
                    'user' => $agent
                ]);
                return new Response($html);
            }
        }
        throw new NotFoundHttpException();
    }

    /**
     * @Route("/password",name="security_password")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function password(Request $request)
    {
        $form = $this->form->create(ResettingFormType::class,new Resetting());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $url = $this->router->generate('security_password');
            $this->flashBag->add('success',$this->translator->trans('password.change.success',[],'security'));
            return new RedirectResponse($url);
        }

        $html = $this->twig->render('Connexion/Password/password.html.twig',[
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/conges",name="security_conges")
     */
    public function conges()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if($user instanceof User) {
            $agent = $this->userManager->getUserBySlugWithDetail($user->getSlug());
            $html = $this->twig->render('Connexion/Conge/index.html.twig',[
                'user' => $agent
            ]);
            return new Response($html);
        }

    }

    /**
     * @Route("/pdf/print",name="security_pdf")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function pdfUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if($user instanceof User) {
            $agent = $this->userManager->getUserBySlugWithDetail($user->getSlug());
            $html = $this->twig->render('Admin/User/includes/pdf/user.html.twig', [
                'user' => $agent,
                'base_dir' => $this->kernel->getProjectDir() . '/public'
            ]);
            $this->print($html,$agent->getNomPrenom().".pdf");
        }
        throw new NotFoundHttpException();
    }
}