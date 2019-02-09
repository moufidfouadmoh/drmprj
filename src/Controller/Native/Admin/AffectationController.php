<?php

namespace App\Controller\Native\Admin;
use App\Entity\Affectation;
use App\Entity\User;
use App\Form\Type\AffectationFormType;
use App\Handler\Native\Admin\AffectationHandler;
use App\Repository\AffectationRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class AffectationController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/affectation")
 */
class AffectationController
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
     * @var AffectationHandler
     */
    private $affectationHandler;


    public function __construct(Environment $twig, AffectationHandler $affectationHandler, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->twig = $twig;
        $this->affectationHandler = $affectationHandler;
    }

    /**
     * @Route("/",name="native.admin.affectation.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->affectationHandler;
        $datatable = $handler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            /** @var AffectationRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/User/Actions/Affectation/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/add", name="native.admin.affectation.add")
     */
    public function add(Request $request,User $user)
    {
        $form = $this->affectationHandler->createForm(AffectationFormType::class,new Affectation($user));
        $html = $this->twig->render('Admin/User/Actions/Affectation/add.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);

        if($this->affectationHandler->process($form,$request)){
            $url = $this->affectationHandler->generateUrl('native.admin.affectation.show',[
                'slug' => $this->affectationHandler->getAffectation()->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/show", name="native.admin.affectation.show")
     */
    public function show(Request $request)
    {
        $affectation = $this->affectationHandler->getAffectationWithDetail($request,'slug');
        $html = $this->twig->render('Admin/User/Actions/Affectation/show.html.twig', [
            'affectation' => $affectation
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit", name="native.admin.affectation.edit")
     */
    public function edit(Request $request,Affectation $affectation)
    {
        $form = $this->affectationHandler->createForm(AffectationFormType::class,$affectation);
        $html = $this->twig->render('Admin/User/Actions/Affectation/edit.html.twig', [
            'affectation' => $affectation,
            'form' => $form->createView()
        ]);
        if($this->affectationHandler->process($form,$request)){
            $url = $this->affectationHandler->generateUrl('native.admin.affectation.show',[
                'slug' => $this->affectationHandler->getAffectation()->getSlug()
            ]);
            return new RedirectResponse($url);
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete", name="native.admin.affectation.delete")
     * @param Request $request
     * @param Affectation $affectation
     * @return RedirectResponse
     */
    public function delete(Request $request,Affectation $affectation)
    {
        $form = $this->affectationHandler->createDeleteForm($affectation->getId(),'native.admin.affectation.delete');

        if($this->affectationHandler->process($form,$request,$affectation)){
            $url = $this->affectationHandler->generateUrl('native.admin.user.show',[
                'slug' => $affectation->getUser()->getSlug()
            ]);
        }else{
            $url = $this->affectationHandler->generateUrl('native.admin.affectation.show',[
                'slug' => $affectation->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }
}