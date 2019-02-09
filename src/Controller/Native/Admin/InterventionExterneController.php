<?php

namespace App\Controller\Native\Admin;
use App\Entity\InterventionExterne;
use App\Form\Type\InterventionExterneFormType;
use App\Handler\Native\Admin\InterventionExterneHandler;
use App\Repository\InterventionExterneRepository;
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
 * @Route("/n/admin/intervention/externe")
 * @Security("is_granted('ROLE_ADMIN_INTERVENTION_EXTERNE')",message="exception.authorization")
 */
class InterventionExterneController
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
     * @var InterventionExterneHandler
     */
    private $interventionHandler;


    public function __construct(Environment $twig,
                                InterventionExterneHandler $interventionHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->interventionHandler = $interventionHandler;
    }

    /**
     * @Route("/index",name="native.admin.intervention.externe.index")
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
            /** @var InterventionExterneRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Intervention/Externe/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/add",name="native.admin.intervention.externe.add")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(Request $request)
    {
        $form = $this->interventionHandler->createForm(InterventionExterneFormType::class,new InterventionExterne());
        $html = $this->twig->render('Admin/Intervention/Externe/add.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->interventionHandler->process($form,$request)){
            $url = $this->interventionHandler->generateUrl('native.admin.intervention.externe.show',[
                'slug' => $this->interventionHandler->getIntervention()
            ]);
            return new RedirectResponse($url);
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.intervention.externe.show")
     * @param InterventionExterne $intervention
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $intervention = $this->interventionHandler->getInterventionBySlugWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Intervention/Externe/show.html.twig', [
            'intervention' => $intervention
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.intervention.externe.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $intervention = $this->interventionHandler->getInterventionBySlugWithDetail($request,'slug');
        $form = $this->interventionHandler->createForm(InterventionExterneFormType::class,$intervention);
        $html = $this->twig->render('Admin/Intervention/Externe/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->interventionHandler->process($form,$request)){
            $intervention = $this->interventionHandler->getIntervention();
            return new RedirectResponse($this->interventionHandler->generateUrl('native.admin.intervention.externe.show',[
                'slug' => $intervention->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.intervention.externe.delete")
     * @param Request $request
     * @param InterventionExterne $intervention
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,InterventionExterne $intervention)
    {
        $form = $this->interventionHandler->createDeleteForm($intervention->getId(),'native.admin.intervention.externe.delete');

        if($this->interventionHandler->process($form,$request,$intervention)){
            $url = $this->interventionHandler->generateUrl('native.admin.intervention.externe.index');
        }else{
            $url = $this->interventionHandler->generateUrl('native.admin.intervention.externe.show',[
                'slug' => $intervention->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}