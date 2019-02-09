<?php

namespace App\Controller\Native\Admin;
use App\Entity\Departement;
use App\Form\Type\DepartementFormType;
use App\Handler\Native\Admin\DepartementHandler;
use App\Repository\DepartementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class DepartementController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/departement")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class DepartementController
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
     * @var DepartementHandler
     */
    private $departementHandler;


    public function __construct(Environment $twig,
                                DepartementHandler $departementHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->departementHandler = $departementHandler;
    }

    /**
     * @Route("/index",name="native.admin.departement.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->departementHandler;
        $form = $handler->createSaveForm(DepartementFormType::class,new Departement(),'native.admin.departement.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var DepartementRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Bureau/Childs/Departement/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.departement.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->departementHandler->createSaveForm(DepartementFormType::class,new Departement(),$request->get('_route'));
        $html = $this->twig->render('Admin/Bureau/Childs/Departement/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->departementHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.departement.show")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $departement = $this->departementHandler->getDepartementWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Bureau/Childs/Departement/show.html.twig', [
            'departement' => $departement
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.departement.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $departement = $this->departementHandler->getDepartementWithDetail($request,'slug');
        $form = $this->departementHandler->createForm(DepartementFormType::class,$departement);
        $html = $this->twig->render('Admin/Bureau/Childs/Departement/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->departementHandler->process($form,$request)){
            $departement = $this->departementHandler->getDepartement();
            return new RedirectResponse($this->departementHandler->generateUrl('native.admin.departement.show',[
                'slug' => $departement->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.departement.delete")
     * @param Request $request
     * @param Departement $departement
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Departement $departement)
    {
        $form = $this->departementHandler->createDeleteForm($departement->getId(),'native.admin.departement.delete');

        if($this->departementHandler->process($form,$request,$departement)){
            $url = $this->departementHandler->generateUrl('native.admin.departement.index');
        }else{
            $url = $this->departementHandler->generateUrl('native.admin.departement.show',[
                'slug' => $departement->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}