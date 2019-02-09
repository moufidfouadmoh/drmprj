<?php

namespace App\Controller\Native\Admin;

use App\Entity\Includes\Search\LieuSearch;
use App\Entity\Lieu;
use App\Form\Search\LieuSearchForm;
use App\Form\Type\LieuFormType;
use App\Handler\Native\Admin\LieuHandler;
use App\Repository\LieuRepository;
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
 * Class LieuController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/lieu")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class LieuController
{
    use PrintAbleTrait;
    const LIST_SESSION = 'lieu_list';
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var LieuHandler
     */
    private $lieuHandler;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(Environment $twig,
                                LieuHandler $lieuHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->lieuHandler = $lieuHandler;
        $this->translator = $translator;
    }

    /**
     * @Route("/index",name="native.admin.lieu.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request,SessionInterface $session)
    {
        $handler = $this->lieuHandler;
        $form = $handler->createSaveForm(LieuFormType::class,new Lieu(),'native.admin.lieu.create');
        $datatable = $handler->buildDatatable();
        $searched = new LieuSearch();
        $search = $this->lieuHandler->createForm(LieuSearchForm::class,$searched);
        $search->handleRequest($request);
        $session->set(self::LIST_SESSION,$search->getData());
        if ($request->isXmlHttpRequest()) {
            /** @var LieuRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository,$session){
                return $repository->selectAll($session->get(self::LIST_SESSION));
            });
            return $handler->buildResponse($datatable,$qb);
        }

        $html = $this->twig->render('Admin/Lieu/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView(),
            'search' => $search->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.lieu.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->lieuHandler->createSaveForm(LieuFormType::class,new Lieu(),$request->get('_route'));
        $html = $this->twig->render('Admin/Lieu/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->lieuHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.lieu.show")
     * @param Lieu $lieu
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Lieu $lieu)
    {
        $html = $this->twig->render('Admin/Lieu/show.html.twig', [
            'lieu' => $lieu
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.lieu.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request,Lieu $lieu)
    {
        $form = $this->lieuHandler->createForm(LieuFormType::class,$lieu);
        $html = $this->twig->render('Admin/Lieu/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->lieuHandler->process($form,$request)){
            $lieu = $this->lieuHandler->getLieu();
            return new RedirectResponse($this->lieuHandler->generateUrl('native.admin.lieu.show',[
                'slug' => $lieu->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.lieu.delete")
     * @param Request $request
     * @param Lieu $lieu
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Lieu $lieu)
    {
        $form = $this->lieuHandler->createDeleteForm($lieu->getId(),'native.admin.lieu.delete');

        if($this->lieuHandler->process($form,$request,$lieu)){
            $url = $this->lieuHandler->generateUrl('native.admin.lieu.index');
        }else{
            $url = $this->lieuHandler->generateUrl('native.admin.lieu.show',[
                'slug' => $lieu->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

    /**
     * @Route("/pdf/list",name="native.admin.lieu.pdf.list")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
	public function pdfList(SessionInterface $session)
    {
        $qb = $this->lieuHandler->getRepository()->selectAll($session->get(self::LIST_SESSION));
        $list = $qb->getQuery()->getResult();

        $html = $this->twig->render('Admin/Lieu/includes/pdf/list.html.twig', [
            'list' => $list
        ]);
        $this->print($html,"lieux.pdf");
	}

}