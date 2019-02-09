<?php

namespace App\Controller\Native\Admin;
use App\Entity\Agence;
use App\Form\Type\AgenceFormType;
use App\Handler\Native\Admin\AgenceHandler;
use App\Repository\AgenceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class AgenceController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class AgenceController
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
     * @var AgenceHandler
     */
    private $agenceHandler;

    public function __construct(Environment $twig,
                                AgenceHandler $agenceHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->agenceHandler = $agenceHandler;
    }

    /**
     * @Route("/agence",name="native.admin.agence.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->agenceHandler;
        $form = $handler->createSaveForm(AgenceFormType::class,new Agence(),'native.admin.agence.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var AgenceRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Agence/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/agence/create",name="native.admin.agence.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->agenceHandler->createSaveForm(AgenceFormType::class,new Agence(),$request->get('_route'));
        $html = $this->twig->render('Admin/Agence/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->agenceHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/agence/{slug}/show",name="native.admin.agence.show")
     * @param Agence $agence
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Agence $agence)
    {
        $html = $this->twig->render('Admin/Agence/show.html.twig', [
            'agence' => $agence
        ]);
        return new Response($html);
    }

    /**
     * @Route("/agence/{slug}/edit",name="native.admin.agence.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request,Agence $agence)
    {
        $form = $this->agenceHandler->createForm(AgenceFormType::class,$agence);
        $html = $this->twig->render('Admin/Agence/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->agenceHandler->process($form,$request)){
            $agence = $this->agenceHandler->getAgence();
            return new RedirectResponse($this->agenceHandler->generateUrl('native.admin.agence.show',[
                'slug' => $agence->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/agence/{id}/delete",name="native.admin.agence.delete")
     * @param Request $request
     * @param Agence $agence
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Agence $agence)
    {
        $form = $this->agenceHandler->createDeleteForm($agence->getId(),'native.admin.agence.delete');

        if($this->agenceHandler->process($form,$request,$agence)){
            $url = $this->agenceHandler->generateUrl('native.admin.agence.index');
        }else{
            $url = $this->agenceHandler->generateUrl('native.admin.agence.show',[
                'slug' => $agence->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}