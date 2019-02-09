<?php

namespace App\Controller\Native\Admin;
use App\Entity\Statut;
use App\Form\Type\StatutFormType;
use App\Handler\Native\Admin\StatutHandler;
use App\Repository\StatutRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class StatutController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/statut")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class StatutController
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
     * @var StatutHandler
     */
    private $statutHandler;

    public function __construct(Environment $twig,
                                StatutHandler $statutHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->statutHandler = $statutHandler;
    }

    /**
     * @Route("/index",name="native.admin.statut.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->statutHandler;
        $form = $handler->createSaveForm(StatutFormType::class,new Statut(),'native.admin.statut.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var StatutRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->createQueryBuilder('statut');
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Statut/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.statut.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->statutHandler->createSaveForm(StatutFormType::class,new Statut(),$request->get('_route'));
        $html = $this->twig->render('Admin/Statut/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->statutHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.statut.show")
     * @param Statut $statut
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Statut $statut)
    {
        $html = $this->twig->render('Admin/Statut/show.html.twig', [
            'statut' => $statut
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.statut.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request,Statut $statut)
    {
        $form = $this->statutHandler->createForm(StatutFormType::class,$statut);
        $html = $this->twig->render('Admin/Statut/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->statutHandler->process($form,$request)){
            $statut = $this->statutHandler->getStatut();
            return new RedirectResponse($this->statutHandler->generateUrl('native.admin.statut.show',[
                'slug' => $statut->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.statut.delete")
     * @param Request $request
     * @param Statut $statut
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Statut $statut)
    {
        $form = $this->statutHandler->createDeleteForm($statut->getId(),'native.admin.statut.delete');

        if($this->statutHandler->process($form,$request,$statut)){
            $url = $this->statutHandler->generateUrl('native.admin.statut.index');
        }else{
            $url = $this->statutHandler->generateUrl('native.admin.statut.show',[
                'slug' => $statut->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}