<?php

namespace App\Controller\Native\Admin;

use App\Entity\Includes\Search\MaterielMobilierSearch;
use App\Entity\MaterielMobilier;
use App\Form\Search\MaterielMobilierSearchForm;
use App\Form\Type\MaterielMobilierFormType;
use App\Handler\Native\Admin\MaterielMobilierHandler;
use App\Repository\MaterielMobilierRepository;
use App\Utils\PrintAbleTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class MaterielMobilierController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/materiel/mobilier")
 * @Security("is_granted('ROLE_ADMIN_MATERIEL_MOBILIER')",message="exception.authorization")
 */
class MaterielMobilierController
{
    use PrintAbleTrait;
    const LIST_SESSION = 'materiel_mobilier_list';
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var MaterielMobilierHandler
     */
    private $materielMobilierHandler;

    public function __construct(Environment $twig,
                                MaterielMobilierHandler $materielMobilierHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->materielMobilierHandler = $materielMobilierHandler;
    }

    /**
     * @Route("/index",name="native.admin.materiel.mobilier.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request,SessionInterface $session)
    {
        $handler = $this->materielMobilierHandler;
        $form = $handler->createSaveForm(MaterielMobilierFormType::class,new MaterielMobilier(),'native.admin.materiel.mobilier.create');
        $datatable = $handler->buildDatatable();
        $searched = new MaterielMobilierSearch();
        $search = $this->materielMobilierHandler->createForm(MaterielMobilierSearchForm::class,$searched);
        $search->handleRequest($request);
        $session->set(self::LIST_SESSION,$search->getData());
        if ($request->isXmlHttpRequest()) {
            /** @var MaterielMobilierRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository,$session){
                return $repository->selectAll($session->get(self::LIST_SESSION));
            });
            return $handler->buildResponse($datatable,$qb);
        }

        $html = $this->twig->render('Admin/Materiel/Childs/Mobilier/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView(),
            'search' => $search->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.materiel.mobilier.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->materielMobilierHandler->createSaveForm(MaterielMobilierFormType::class,new MaterielMobilier(),$request->get('_route'));
        $html = $this->twig->render('Admin/Materiel/Childs/Mobilier/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->materielMobilierHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.materiel.mobilier.show")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $materiel = $this->materielMobilierHandler->getMaterielBySlugWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Materiel/Childs/Mobilier/show.html.twig', [
            'materiel' => $materiel
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.materiel.mobilier.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $materiel = $this->materielMobilierHandler->getMaterielBySlugWithDetail($request,'slug');
        $form = $this->materielMobilierHandler->createForm(MaterielMobilierFormType::class,$materiel);
        $html = $this->twig->render('Admin/Materiel/Childs/Mobilier/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->materielMobilierHandler->process($form,$request)){
            $materiel = $this->materielMobilierHandler->getMateriel();
            return new RedirectResponse($this->materielMobilierHandler->generateUrl('native.admin.materiel.mobilier.show',[
                'slug' => $materiel->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.materiel.mobilier.delete")
     * @param Request $request
     * @param MaterielMobilier $materiel
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,MaterielMobilier $materiel)
    {
        $form = $this->materielMobilierHandler->createDeleteForm($materiel->getId(),'native.admin.materiel.mobilier.delete');

        if($this->materielMobilierHandler->process($form,$request,$materiel)){
            $url = $this->materielMobilierHandler->generateUrl('native.admin.materiel.mobilier.index');
        }else{
            $url = $this->materielMobilierHandler->generateUrl('native.admin.materiel.mobilier.show',[
                'slug' => $materiel->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

    /**
     * @Route("/pdf/list",name="native.admin.materiel.mobilier.pdf.list")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
	public function pdfList(SessionInterface $session)
    {
        $qb = $this->materielMobilierHandler->getRepository()->selectAll($session->get(self::LIST_SESSION));
        $list = $qb->getQuery()->getResult();

        $html = $this->twig->render('Admin/Materiel/Childs/Mobilier/includes/pdf/list.html.twig', [
            'list' => $list
        ]);
        $this->print($html,"materiels.pdf");
	}

}