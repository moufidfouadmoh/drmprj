<?php

namespace App\Controller\Native\Admin;
use App\Handler\Native\Admin\BureauHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * Class BureauController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class BureauController
{
    /**
     * @var BureauHandler
     */
    private $bureauHandler;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig,BureauHandler $bureauHandler)
    {
        $this->bureauHandler = $bureauHandler;
        $this->twig = $twig;
    }

    /**
     * @Route("/bureau",name="native.admin.bureau.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->bureauHandler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            return $this->bureauHandler->buildResponse($datatable,'bureau');
        }

        $html = $this->twig->render('Admin/Bureau/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }
}