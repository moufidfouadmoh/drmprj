<?php

namespace App\Controller\Native\Admin;


use App\Datatables\Admin\RoleDatatable;
use App\Entity\Includes\Search\RoleSearch;
use App\Form\Search\RoleSearchForm;
use App\Handler\Native\Admin\UserHandler;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * Class RoleController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/role")
 * @Security("is_granted('ROLE_SUPER_ADMIN')",message="exception.authorization")
 */
class RoleController
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var UserHandler
     */
    private $userHandler;

    public function __construct(Environment $twig,UserHandler $userHandler)
    {
        $this->twig = $twig;
        $this->userHandler = $userHandler;
    }

    /**
     * @Route("/index",name="native.admin.role.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request)
    {
        $handler = $this->userHandler;
        $datatable = $handler->buildDatatable(RoleDatatable::class);
        $searched = new RoleSearch();
        $search = $this->userHandler->createForm(RoleSearchForm::class,$searched);
        $search->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            /** @var UserRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectByRoles();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/User/Actions/Role/index.html.twig',[
            'datatable' => $datatable,
            'search' => $search->createView()
        ]);
        return new Response($html);
    }
}