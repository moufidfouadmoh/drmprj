<?php

namespace App\Controller\Native\Admin;
use App\Entity\Fonction;
use App\Form\Type\FonctionFormType;
use App\Handler\Native\Admin\FonctionHandler;
use App\Repository\FonctionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class FonctionController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/fonction")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class FonctionController
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
     * @var FonctionHandler
     */
    private $fonctionHandler;


    public function __construct(Environment $twig,
                                FonctionHandler $fonctionHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->fonctionHandler = $fonctionHandler;
    }

    /**
     * @Route("/index",name="native.admin.fonction.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->fonctionHandler;
        $form = $handler->createSaveForm(FonctionFormType::class,new Fonction(),'native.admin.fonction.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var FonctionRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->createQueryBuilder('fonction');
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Fonction/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.fonction.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->fonctionHandler->createSaveForm(FonctionFormType::class,new Fonction(),$request->get('_route'));
        $html = $this->twig->render('Admin/Fonction/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->fonctionHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.fonction.show")
     * @param Fonction $statut
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Fonction $fonction)
    {
        $html = $this->twig->render('Admin/Fonction/show.html.twig', [
            'fonction' => $fonction
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.fonction.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request,Fonction $fonction)
    {
        $form = $this->fonctionHandler->createForm(FonctionFormType::class,$fonction);
        $html = $this->twig->render('Admin/Fonction/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->fonctionHandler->process($form,$request)){
            $fonction = $this->fonctionHandler->getFonction();
            return new RedirectResponse($this->fonctionHandler->generateUrl('native.admin.fonction.show',[
                'slug' => $fonction->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.fonction.delete")
     * @param Request $request
     * @param Fonction $fonction
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Fonction $fonction)
    {
        $form = $this->fonctionHandler->createDeleteForm($fonction->getId(),'native.admin.fonction.delete');

        if($this->fonctionHandler->process($form,$request,$fonction)){
            $url = $this->fonctionHandler->generateUrl('native.admin.fonction.index');
        }else{
            $url = $this->fonctionHandler->generateUrl('native.admin.fonction.show',[
                'slug' => $fonction->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}