<?php

namespace App\Controller\Native\Admin;
use App\Entity\User;
use App\Entity\Includes\Search\UserSearch;
use App\Form\Search\UserSearchForm;
use App\Form\Type\UserFormType;
use App\Handler\Native\Admin\UserHandler;
use App\Repository\UserRepository;
use App\Utils\PrintAbleTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * Class UserController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/user")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class UserController
{
    const LIST_SESSION = 'user_list';
    use PrintAbleTrait;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var UserHandler
     */
    private $userHandler;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(Environment $twig,
                                UserHandler $userHandler,
                                KernelInterface $kernel,
                                RouterInterface $router)
    {
        $this->twig = $twig;
        $this->userHandler = $userHandler;
        $this->router = $router;
        $this->kernel = $kernel;
    }

    /**
     * @Route("/index",name="native.admin.user.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request,SessionInterface $session)
    {
        $handler = $this->userHandler;
        $datatable = $handler->buildDatatable();
        $searched = new UserSearch();
        $search = $this->userHandler->createForm(UserSearchForm::class,$searched);
        $search->handleRequest($request);
        $session->set(self::LIST_SESSION,$search->getData());

        if ($request->isXmlHttpRequest()) {
            /** @var UserRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository,$session){
                return $repository->selectAll($session->get(self::LIST_SESSION));
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/User/index.html.twig',[
            'datatable' => $datatable,
            'search' => $search->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/new",name="native.admin.user.add")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(Request $request)
    {
        $form = $this->userHandler->createForm(UserFormType::class,new User());
        $html = $this->twig->render('Admin/User/new.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->userHandler->process($form,$request)){
            $user = $this->userHandler->getUser();
            return new RedirectResponse($this->router->generate('native.admin.user.show',[
                'slug' => $user->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.user.show")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $user = $this->userHandler->getUserBySlugWithDetail($request,'slug');
        $html = $this->twig->render('Admin/User/show.html.twig', [
            'user' => $user
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.user.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $user = $this->userHandler->getUserBySlugWithDetail($request,'slug');
        $form = $this->userHandler->createForm(UserFormType::class,$user);
        $html = $this->twig->render('Admin/User/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->userHandler->process($form,$request)){
            $user = $this->userHandler->getUser();
            return new RedirectResponse($this->router->generate('native.admin.user.show',[
                'slug' => $user->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/pdf/list",name="native.admin.user.pdf.list")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function pdfList(SessionInterface $session)
    {
        $qb = $this->userHandler->getRepository()->selectAll($session->get(self::LIST_SESSION));
        $list = $qb->getQuery()->getResult();
        $html = $this->twig->render('Admin/User/includes/pdf/list.html.twig', [
            'list' => $list
        ]);
        $this->print($html,"agents.pdf");
    }

    /**
     * @Route("/pdf/{slug}/user",name="native.admin.user.pdf.user")
     * @param Request $request
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function pdfUser(Request $request)
    {
        $user = $this->userHandler->getUserBySlugWithDetail($request,'slug');
        $html = $this->twig->render('Admin/User/includes/pdf/user.html.twig', [
            'user' => $user,
            'base_dir' => $this->kernel->getProjectDir() . '/public'
        ]);
        $this->print($html,$user->getNomPrenom().".pdf");
    }
}