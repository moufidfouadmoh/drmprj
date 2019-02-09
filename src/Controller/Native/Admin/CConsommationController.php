<?php

namespace App\Controller\Native\Admin;
use App\Entity\CConsommation;
use App\Entity\Includes\Search\CConsommationSearch;
use App\Entity\User;
use App\Form\Search\CConsommationSearchForm;
use App\Form\Type\CConsommationFormType;
use App\Handler\Native\Admin\CConsommationHandler;
use App\Handler\Native\Admin\CModeleHandler;
use App\Repository\CConsommationRepository;
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
 * Class CDemandeController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/consommation")
 * @Security("is_granted('ROLE_ADMIN_CONGE')",message="exception.authorization")
 */
class CConsommationController
{
    use PrintAbleTrait;
    const LIST_SESSION = 'cconsommation_list';
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var CConsommationHandler
     */
    private $consommationHandler;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(Environment $twig,
                                TranslatorInterface $translator,
                                CConsommationHandler $consommationHandler)
    {
        $this->twig = $twig;
        $this->consommationHandler = $consommationHandler;
        $this->translator = $translator;
    }

    /**
     * @Route("/index",name="native.admin.consommation.index")
     */
    public function index(Request $request,SessionInterface $session)
    {
        $handler = $this->consommationHandler;
        $datatable = $handler->buildDatatable();
        $searched = new CConsommationSearch();
        $search = $handler->createForm(CConsommationSearchForm::class,$searched);
        $search->handleRequest($request);
        $session->set(self::LIST_SESSION,$search->getData());
        if ($request->isXmlHttpRequest()) {
            /** @var CConsommationRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository,$session){
                return $repository->selectAll($session->get(self::LIST_SESSION));
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Conge/Consommation/index.html.twig',[
            'datatable' => $datatable,
            'search' => $search->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/check",name="native.admin.consommation.check")
     */
    public function check(Request $request)
    {
        $form = $this->consommationHandler->createForm(CConsommationFormType::class,new CConsommation(),[
            'check' => true
        ]);

        $html = $this->twig->render('Admin/Conge/Consommation/check.html.twig',[
            'form' => $form->createView()
        ]);

        $checker = $this->consommationHandler->handleCheck($form,$request);
        if($checker){
            if(is_array($checker)){
                if(count($checker)==2){
                    $url = $this->consommationHandler->generateUrl('native.admin.consommation.request',[
                        'slug'=> $checker['user']->getSlug()
                    ]);
                }else{
                    $this->consommationHandler->addFlashMessage('info',$this->translator->trans('app.cconsommation.check.not.available',[
                        '%user%' => $checker['user']->getNomPrenom()
                    ]));
                    $url = $this->consommationHandler->generateUrl('native.admin.consommation.check');
                }
            }

            if($checker instanceof User){
                $this->consommationHandler->addFlashMessage('info',$this->translator->trans('app.cconsommation.check.not.exist',[
                    '%user%' => $checker->getNomPrenom()
                ]));
                $url = $this->consommationHandler->generateUrl('native.admin.consommation.check');
            }

            return new RedirectResponse($url);

        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/request",name="native.admin.consommation.request")
     */
    public function request(Request $request)
    {
        /** @var User $user */
        $user = $this->consommationHandler->getUserBySlugWithDetail($request,'slug');
        $modeles = $this->consommationHandler->getCModelesByNomAndStatuts(true,[$user->getCurrentStatut()->getNom()]);
        $html = $this->twig->render('Admin/Conge/Consommation/request.html.twig',[
            'user' => $user,
            'modeles' => $modeles
        ]);

        return new Response($html);
    }

    /**
     * @Route("/{user}/add/{modele}",name="native.admin.consommation.add")
     */
    public function add(Request $request)
    {
        $user = $this->consommationHandler->getUserBySlugWithDetail($request,'user');
        $modele = $this->consommationHandler->getCModeleBySlugWithDetail($request,'modele');
        $form = $this->consommationHandler->createForm(CConsommationFormType::class,new CConsommation($user,$modele),CConsommationFormType::buildCreateOptionsFromModele($modele));
        $html = $this->twig->render('Admin/Conge/Consommation/add.html.twig',[
            'form' => $form->createView(),
            'user' => $user,
            'modele' => $modele
        ]);
        if($this->consommationHandler->process($form,$request)){
            $url = $this->consommationHandler->generateUrl('native.admin.consommation.show',[
                'slug' => $this->consommationHandler->getConsommation()->getSlug()
            ]);
            return new RedirectResponse($url);
        }
        return new Response($html);

    }

    /**
     * @Route("/{slug}/show",name="native.admin.consommation.show")
     */
    public function show(Request $request)
    {
        $cconsommation = $this->consommationHandler->getCConsommationWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Conge/Consommation/show.html.twig',[
            'cconsommation' => $cconsommation
        ]);

        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.consommation.edit")
     */
    public function edit(Request $request)
    {
        $cconsommation = $this->consommationHandler->getCConsommationWithDetail($request,'slug');
        $form = $this->consommationHandler->createForm(CConsommationFormType::class,$cconsommation,CConsommationFormType::buildCreateOptionsFromModele($cconsommation->getCModele()));
        $html = $this->twig->render('Admin/Conge/Consommation/edit.html.twig',[
            'form' => $form->createView(),
            'cconsommation' => $cconsommation
        ]);

        if($this->consommationHandler->process($form,$request)){
            $cconsommation = $this->consommationHandler->getConsommation();
            return new RedirectResponse($this->consommationHandler->generateUrl('native.admin.consommation.show',[
                'slug' => $cconsommation->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/show",name="native.admin.consommation.delete")
     */
    public function deleteAction(Request $request,CConsommation $consommation)
    {
        $form = $this->consommationHandler->createDeleteForm($consommation->getId(),'native.admin.consommation.delete');

        if($this->consommationHandler->process($form,$request,$consommation)){
            $url = $this->consommationHandler->generateUrl('native.admin.consommation.index');
        }else{
            $url = $this->consommationHandler->generateUrl('native.admin.consommation.show',[
                'slug' => $consommation->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

    /**
     * @Route("/pdf/list",name="native.admin.consommation.pdf.list")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function pdfList(SessionInterface $session)
    {
        $qb = $this->consommationHandler->getRepository()->selectAll($session->get(self::LIST_SESSION));
        $list = $qb->getQuery()->getResult();
        $html = $this->twig->render('Admin/Conge/Consommation/includes/pdf/list.html.twig', [
            'list' => $list
        ]);
        $this->print($html,"conges.pdf");
    }
}