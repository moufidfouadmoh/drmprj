<?php

namespace App\Controller\Native\Admin;

use App\Entity\Includes\Search\MaterielInformatiqueSearch;
use App\Entity\MaterielInformatique;
use App\Form\Search\MaterielInformatiqueSearchForm;
use App\Form\Type\MaterielInformatiqueFormType;
use App\Handler\Native\Admin\MaterielInformatiqueHandler;
use App\Repository\MaterielInformatiqueRepository;
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
 * Class MaterielInformatiqueController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/materiel/informatique")
 * @Security("is_granted('ROLE_ADMIN_MATERIEL_INFORMATIQUE')",message="exception.authorization")
 */
class MaterielInformatiqueController
{
    use PrintAbleTrait;
    const LIST_SESSION = 'materiel_informatique_list';
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var MaterielInformatiqueHandler
     */
    private $materielInformatiqueHandler;

    public function __construct(Environment $twig,
                                MaterielInformatiqueHandler $materielInformatiqueHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->materielInformatiqueHandler = $materielInformatiqueHandler;
    }

    /**
     * @Route("/index",name="native.admin.materiel.informatique.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request,SessionInterface $session)
    {
        $handler = $this->materielInformatiqueHandler;
        $form = $handler->createSaveForm(MaterielInformatiqueFormType::class,new MaterielInformatique(),'native.admin.materiel.informatique.create');
        $datatable = $handler->buildDatatable();
        $searched = new MaterielInformatiqueSearch();
        $search = $this->materielInformatiqueHandler->createForm(MaterielInformatiqueSearchForm::class,$searched);
        $search->handleRequest($request);
        $session->set(self::LIST_SESSION,$search->getData());
        if ($request->isXmlHttpRequest()) {
            /** @var MaterielInformatiqueRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository,$session){
                return $repository->selectAll($session->get(self::LIST_SESSION));
            });
            return $handler->buildResponse($datatable,$qb);
        }

        $html = $this->twig->render('Admin/Materiel/Childs/Informatique/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView(),
            'search' => $search->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.materiel.informatique.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->materielInformatiqueHandler->createSaveForm(MaterielInformatiqueFormType::class,new MaterielInformatique(),$request->get('_route'));
        $html = $this->twig->render('Admin/Materiel/Childs/Informatique/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->materielInformatiqueHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.materiel.informatique.show")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $materiel = $this->materielInformatiqueHandler->getMaterielBySlugWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Materiel/Childs/Informatique/show.html.twig', [
            'materiel' => $materiel
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.materiel.informatique.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $materiel = $this->materielInformatiqueHandler->getMaterielBySlugWithDetail($request,'slug');
        $form = $this->materielInformatiqueHandler->createForm(MaterielInformatiqueFormType::class,$materiel);
        $html = $this->twig->render('Admin/Materiel/Childs/Informatique/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->materielInformatiqueHandler->process($form,$request)){
            $materiel = $this->materielInformatiqueHandler->getMateriel();
            return new RedirectResponse($this->materielInformatiqueHandler->generateUrl('native.admin.materiel.informatique.show',[
                'slug' => $materiel->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.materiel.informatique.delete")
     * @param Request $request
     * @param MaterielInformatique $materiel
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,MaterielInformatique $materiel)
    {
        $form = $this->materielInformatiqueHandler->createDeleteForm($materiel->getId(),'native.admin.materiel.informatique.delete');

        if($this->materielInformatiqueHandler->process($form,$request,$materiel)){
            $url = $this->materielInformatiqueHandler->generateUrl('native.admin.materiel.informatique.index');
        }else{
            $url = $this->materielInformatiqueHandler->generateUrl('native.admin.materiel.informatique.show',[
                'slug' => $materiel->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

    /**
     * @Route("/pdf/list",name="native.admin.materiel.informatique.pdf.list")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
	public function pdfList(SessionInterface $session)
    {
        $qb = $this->materielInformatiqueHandler->getRepository()->selectAll($session->get(self::LIST_SESSION));
        $list = $qb->getQuery()->getResult();

        $html = $this->twig->render('Admin/Materiel/Childs/Informatique/includes/pdf/list.html.twig', [
            'list' => $list
        ]);
        $this->print($html,"materiels.pdf");
	}

}