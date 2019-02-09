<?php

namespace App\Controller\Native\Admin;
use App\Entity\CModele;
use App\Form\Type\CModeleFormType;
use App\Handler\Native\Admin\CModeleHandler;
use App\Repository\CModeleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class CModeleController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/modele")
 * @Security("is_granted('ROLE_ADMIN_CONGE')",message="exception.authorization")
 */
class CModeleController
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
     * @var CModeleHandler
     */
    private $modeleHandler;

    public function __construct(Environment $twig,
                                CModeleHandler $modeleHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->modeleHandler = $modeleHandler;
    }

    /**
     * @Route("/index",name="native.admin.modele.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->modeleHandler;
        $form = $handler->createSaveForm(CModeleFormType::class,new CModele(),'native.admin.modele.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var CModeleRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Conge/Modele/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.modele.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->modeleHandler->createSaveForm(CModeleFormType::class,new CModele(),$request->get('_route'));
        $html = $this->twig->render('Admin/Conge/Modele/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->modeleHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.modele.show")
     * @param CModele $modele
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(CModele $modele)
    {
        $html = $this->twig->render('Admin/Conge/Modele/show.html.twig', [
            'modele' => $modele
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.modele.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request,CModele $modele)
    {
        $form = $this->modeleHandler->createForm(CModeleFormType::class,$modele);
        $html = $this->twig->render('Admin/Conge/Modele/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->modeleHandler->process($form,$request)){
            $modele = $this->modeleHandler->getModele();
            return new RedirectResponse($this->modeleHandler->generateUrl('native.admin.modele.show',[
                'slug' => $modele->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.modele.delete")
     * @param Request $request
     * @param CModele $modele
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,CModele $modele)
    {
        $form = $this->modeleHandler->createDeleteForm($modele->getId(),'native.admin.modele.delete');

        if($this->modeleHandler->process($form,$request,$modele)){
            $url = $this->modeleHandler->generateUrl('native.admin.modele.index');
        }else{
            $url = $this->modeleHandler->generateUrl('native.admin.modele.show',[
                'slug' => $modele->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}