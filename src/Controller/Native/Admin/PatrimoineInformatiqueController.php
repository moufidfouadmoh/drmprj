<?php

namespace App\Controller\Native\Admin;

use App\Entity\PatrimoineInformatique;
use App\Form\Type\InventaireInformatiqueFormType;
use App\Form\Type\PatrimoineInformatiqueFormType;
use App\Handler\Native\Admin\PatrimoineInformatiqueHandler;
use App\Repository\PatrimoineInformatiqueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class InventaireInformatiqueController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/patrimoine/informatique")
 * @Security("is_granted('ROLE_ADMIN_MATERIEL_INFORMATIQUE')",message="exception.authorization")
 */
class PatrimoineInformatiqueController
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
     * @var PatrimoineInformatiqueHandler
     */
    private $patrimoineInformatiqueHandler;

    public function __construct(Environment $twig,
                                PatrimoineInformatiqueHandler $patrimoineInformatiqueHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->patrimoineInformatiqueHandler = $patrimoineInformatiqueHandler;
    }

    /**
     * @Route("/index",name="native.admin.patrimoine.informatique.index")
     */
    public function index(Request $request)
    {
        $handler = $this->patrimoineInformatiqueHandler;
        $datatable = $handler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            if ($request->isXmlHttpRequest()) {
                /** @var PatrimoineInformatiqueRepository $repository */
                $repository = $handler->getRepository();
                $qb = $handler->setQueryBuilderList(function () use ($repository){
                    return $repository->selectAll();
                });
                return $handler->buildResponse($datatable,$qb);
            }
        }
        $html = $this->twig->render('Admin/Patrimoine/Childs/Informatique/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/add",name="native.admin.patrimoine.informatique.add")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(Request $request)
    {
        $inventaire = new PatrimoineInformatique();
        $form = $this->patrimoineInformatiqueHandler->createForm(PatrimoineInformatiqueFormType::class,$inventaire);
        $html = $this->twig->render('Admin/Patrimoine/Childs/Informatique/add.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->patrimoineInformatiqueHandler->process($form,$request)){
            $patrimoine = $this->patrimoineInformatiqueHandler->getPatrimoine();
            $url = $this->patrimoineInformatiqueHandler->generateUrl('native.admin.patrimoine.informatique.show',[
                'slug' => $patrimoine->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.patrimoine.informatique.show")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $patrimoine = $this->patrimoineInformatiqueHandler->getPatrimoineWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Patrimoine/Childs/Informatique/show.html.twig', [
            'patrimoine' => $patrimoine
        ]);
        return new Response($html);
    }


    /**
     * @Route("/{slug}/edit",name="native.admin.patrimoine.informatique.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $patrimoine = $this->patrimoineInformatiqueHandler->getPatrimoineWithDetail($request,'slug');
        $form = $this->patrimoineInformatiqueHandler->createForm(PatrimoineInformatiqueFormType::class,$patrimoine);
        $html = $this->twig->render('Admin/Patrimoine/Childs/Informatique/edit.html.twig',[
            'form' => $form->createView(),
            'edit' => true
        ]);

        if($this->patrimoineInformatiqueHandler->process($form,$request)){
            $patrimoine = $this->patrimoineInformatiqueHandler->getPatrimoine();
            return new RedirectResponse($this->patrimoineInformatiqueHandler->generateUrl('native.admin.patrimoine.informatique.show',[
                'slug' => $patrimoine->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.patrimoine.informatique.delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,PatrimoineInformatique $patrimoine)
    {
        $form = $this->patrimoineInformatiqueHandler->createDeleteForm($patrimoine->getId(),'native.admin.patrimoine.informatique.delete');

        if($this->patrimoineInformatiqueHandler->process($form,$request,$patrimoine)){
            $url = $this->patrimoineInformatiqueHandler->generateUrl('native.admin.patrimoine.informatique.index');
        }else{
            $url = $this->patrimoineInformatiqueHandler->generateUrl('native.admin.patrimoine.informatique.show',[
                'slug' => $patrimoine->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }
}