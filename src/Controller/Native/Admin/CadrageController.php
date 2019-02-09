<?php

namespace App\Controller\Native\Admin;
use App\Entity\Cadrage;
use App\Entity\User;
use App\Form\Type\CadrageFormType;
use App\Handler\Native\Admin\CadrageHandler;
use App\Repository\CadrageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class CadrageController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/cadrage")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class CadrageController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var CadrageHandler
     */
    private $cadrageHandler;


    public function __construct(Environment $twig, CadrageHandler $cadrageHandler, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->twig = $twig;
        $this->cadrageHandler = $cadrageHandler;
    }

    /**
     * @Route("/",name="native.admin.cadrage.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->cadrageHandler;
        $datatable = $handler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            /** @var CadrageRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/User/Actions/Cadrage/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/add", name="native.admin.cadrage.add")
     */
    public function add(Request $request,User $user)
    {
        $form = $this->cadrageHandler->createForm(CadrageFormType::class,new Cadrage($user));
        $html = $this->twig->render('Admin/User/Actions/Cadrage/add.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);

        if($this->cadrageHandler->process($form,$request)){
            $url = $this->cadrageHandler->generateUrl('native.admin.cadrage.show',[
                'slug' => $this->cadrageHandler->getCadrage()->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/show", name="native.admin.cadrage.show")
     */
    public function show(Request $request)
    {
        $cadrage = $this->cadrageHandler->getCadrageWithDetail($request,'slug');
        $html = $this->twig->render('Admin/User/Actions/Cadrage/show.html.twig', [
            'cadrage' => $cadrage
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit", name="native.admin.cadrage.edit")
     */
    public function edit(Request $request)
    {
        $cadrage = $this->cadrageHandler->getCadrageWithDetail($request,'slug');
        $form = $this->cadrageHandler->createForm(CadrageFormType::class,$cadrage);
        $html = $this->twig->render('Admin/User/Actions/Cadrage/edit.html.twig', [
            'cadrage' => $cadrage,
            'form' => $form->createView()
        ]);
        if($this->cadrageHandler->process($form,$request)){
            $url = $this->cadrageHandler->generateUrl('native.admin.cadrage.show',[
                'slug' => $this->cadrageHandler->getCadrage()->getSlug()
            ]);
            return new RedirectResponse($url);
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete", name="native.admin.cadrage.delete")
     * @param Request $request
     * @param Cadrage $cadrage
     * @return RedirectResponse
     */
    public function delete(Request $request,Cadrage $cadrage)
    {
        $form = $this->cadrageHandler->createDeleteForm($cadrage->getId(),'native.admin.cadrage.delete');

        if($this->cadrageHandler->process($form,$request,$cadrage)){
            $url = $this->cadrageHandler->generateUrl('native.admin.user.show',[
                'slug' => $cadrage->getUser()->getSlug()
            ]);
        }else{
            $url = $this->cadrageHandler->generateUrl('native.admin.cadrage.show',[
                'slug' => $cadrage->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }
}