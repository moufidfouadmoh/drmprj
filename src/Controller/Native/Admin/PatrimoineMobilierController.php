<?php

namespace App\Controller\Native\Admin;

use App\Entity\PatrimoineMobilier;
use App\Form\Type\PatrimoineMobilierFormType;
use App\Handler\Native\Admin\PatrimoineMobilierHandler;
use App\Repository\PatrimoineMobilierRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class PatrimoineMobilierController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/patrimoine/mobilier")
 * @Security("is_granted('ROLE_ADMIN_MATERIEL_MOBILIER')",message="exception.authorization")
 */
class PatrimoineMobilierController
{

    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var PatrimoineMobilierHandler
     */
    private $patrimoineMobilierHandler;

    public function __construct(Environment $twig,
                                PatrimoineMobilierHandler $patrimoineMobilierHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->patrimoineMobilierHandler = $patrimoineMobilierHandler;
    }

    /**
     * @Route("/index",name="native.admin.patrimoine.mobilier.index")
     */
    public function index(Request $request)
    {
        $handler = $this->patrimoineMobilierHandler;
        $datatable = $handler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            if ($request->isXmlHttpRequest()) {
                /** @var PatrimoineMobilierRepository $repository */
                $repository = $handler->getRepository();
                $qb = $handler->setQueryBuilderList(function () use ($repository){
                    return $repository->selectAll();
                });
                return $handler->buildResponse($datatable,$qb);
            }
        }
        $html = $this->twig->render('Admin/Patrimoine/Childs/Mobilier/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/add",name="native.admin.patrimoine.mobilier.add")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(Request $request)
    {
        $inventaire = new PatrimoineMobilier();
        $form = $this->patrimoineMobilierHandler->createForm(PatrimoineMobilierFormType::class,$inventaire);
        $html = $this->twig->render('Admin/Patrimoine/Childs/Mobilier/add.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->patrimoineMobilierHandler->process($form,$request)){
            $patrimoine = $this->patrimoineMobilierHandler->getPatrimoine();
            $url = $this->patrimoineMobilierHandler->generateUrl('native.admin.patrimoine.mobilier.show',[
                'slug' => $patrimoine->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.patrimoine.mobilier.show")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $patrimoine = $this->patrimoineMobilierHandler->getPatrimoineWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Patrimoine/Childs/Mobilier/show.html.twig', [
            'patrimoine' => $patrimoine
        ]);
        return new Response($html);
    }


    /**
     * @Route("/{slug}/edit",name="native.admin.patrimoine.mobilier.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $patrimoine = $this->patrimoineMobilierHandler->getPatrimoineWithDetail($request,'slug');
        $form = $this->patrimoineMobilierHandler->createForm(PatrimoineMobilierFormType::class,$patrimoine);
        $html = $this->twig->render('Admin/Patrimoine/Childs/Mobilier/edit.html.twig',[
            'form' => $form->createView(),
            'edit' => true
        ]);

        if($this->patrimoineMobilierHandler->process($form,$request)){
            $patrimoine = $this->patrimoineMobilierHandler->getPatrimoine();
            return new RedirectResponse($this->patrimoineMobilierHandler->generateUrl('native.admin.patrimoine.mobilier.show',[
                'slug' => $patrimoine->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.patrimoine.mobilier.delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,PatrimoineMobilier $patrimoine)
    {
        $form = $this->patrimoineMobilierHandler->createDeleteForm($patrimoine->getId(),'native.admin.patrimoine.mobilier.delete');

        if($this->patrimoineMobilierHandler->process($form,$request,$patrimoine)){
            $url = $this->patrimoineMobilierHandler->generateUrl('native.admin.patrimoine.mobilier.index');
        }else{
            $url = $this->patrimoineMobilierHandler->generateUrl('native.admin.patrimoine.mobilier.show',[
                'slug' => $patrimoine->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }
}