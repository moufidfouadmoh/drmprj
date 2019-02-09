<?php

namespace App\Controller\Native\Admin;

use App\Entity\Categorie;
use App\Form\Type\CategorieFormType;
use App\Handler\Native\Admin\CategorieHandler;
use App\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class CategorieController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/categorie")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class CategorieController
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
     * @var CategorieHandler
     */
    private $categorieHandler;

    public function __construct(Environment $twig,
                                CategorieHandler $categorieHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->categorieHandler = $categorieHandler;
    }

    /**
     * @Route("/index",name="native.admin.categorie.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->categorieHandler;
        $form = $handler->createSaveForm(CategorieFormType::class,new Categorie(),'native.admin.categorie.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var CategorieRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->createQueryBuilder('categorie');
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Categorie/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.categorie.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->categorieHandler->createSaveForm(CategorieFormType::class,new Categorie(),$request->get('_route'));
        $html = $this->twig->render('Admin/Categorie/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->categorieHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.categorie.show")
     * @param Categorie $categorie
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Categorie $categorie)
    {
        $html = $this->twig->render('Admin/Categorie/show.html.twig', [
            'categorie' => $categorie
        ]);
        return new Response($html);
    }

    /**
     * @Route("/categorie/{slug}/edit",name="native.admin.categorie.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request,Categorie $categorie)
    {
        $form = $this->categorieHandler->createForm(CategorieFormType::class,$categorie);
        $html = $this->twig->render('Admin/Categorie/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->categorieHandler->process($form,$request)){
            $categorie = $this->categorieHandler->getCategorie();
            return new RedirectResponse($this->categorieHandler->generateUrl('native.admin.categorie.show',[
                'slug' => $categorie->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.categorie.delete")
     * @param Request $request
     * @param Categorie $categorie
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Categorie $categorie)
    {
        $form = $this->categorieHandler->createDeleteForm($categorie->getId(),'native.admin.categorie.delete');

        if($this->categorieHandler->process($form,$request,$categorie)){
            $url = $this->categorieHandler->generateUrl('native.admin.categorie.index');
        }else{
            $url = $this->categorieHandler->generateUrl('native.admin.categorie.show',[
                'slug' => $categorie->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}