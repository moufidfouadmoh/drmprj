<?php

namespace App\Controller\Native\Admin;
use App\Entity\InterventionInterne;
use App\Form\Type\InterventionInterneFormType;
use App\Handler\Native\Admin\InterventionInterneHandler;
use App\Repository\InterventionInterneRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class InterventionController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/intervention/interne")
 * @Security("is_granted('ROLE_ADMIN_INTERVENTION_INTERNE')",message="exception.authorization")
 */
class InterventionInterneController
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
     * @var InterventionInterneHandler
     */
    private $interventionHandler;


    public function __construct(Environment $twig,
                                InterventionInterneHandler $interventionHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->interventionHandler = $interventionHandler;
    }

    /**
     * @Route("/index",name="native.admin.intervention.interne.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->interventionHandler;
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var InterventionInterneRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Intervention/Interne/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/add",name="native.admin.intervention.interne.add")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(Request $request)
    {
        $form = $this->interventionHandler->createForm(InterventionInterneFormType::class,new InterventionInterne());
        $html = $this->twig->render('Admin/Intervention/Interne/add.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->interventionHandler->process($form,$request)){
            $url = $this->interventionHandler->generateUrl('native.admin.intervention.interne.show',[
                'slug' => $this->interventionHandler->getIntervention()
            ]);
            return new RedirectResponse($url);
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.intervention.interne.show")
     * @param InterventionInterne $intervention
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $intervention = $this->interventionHandler->getInterventionBySlugWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Intervention/Interne/show.html.twig', [
            'intervention' => $intervention
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.intervention.interne.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $intervention = $this->interventionHandler->getInterventionBySlugWithDetail($request,'slug');
        $form = $this->interventionHandler->createForm(InterventionInterneFormType::class,$intervention);
        $html = $this->twig->render('Admin/Intervention/Interne/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->interventionHandler->process($form,$request)){
            $intervention = $this->interventionHandler->getIntervention();
            return new RedirectResponse($this->interventionHandler->generateUrl('native.admin.intervention.interne.show',[
                'slug' => $intervention->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.intervention.interne.delete")
     * @param Request $request
     * @param InterventionInterne $intervention
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,InterventionInterne $intervention)
    {
        $form = $this->interventionHandler->createDeleteForm($intervention->getId(),'native.admin.intervention.interne.delete');

        if($this->interventionHandler->process($form,$request,$intervention)){
            $url = $this->interventionHandler->generateUrl('native.admin.intervention.interne.index');
        }else{
            $url = $this->interventionHandler->generateUrl('native.admin.intervention.interne.show',[
                'slug' => $intervention->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}