<?php

namespace App\Controller\Native\Admin;
use App\Entity\Situation;
use App\Entity\User;
use App\Form\Type\SituationFormType;
use App\Handler\Native\Admin\SituationHandler;
use App\Repository\SituationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class SituationController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/situation")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class SituationController
{
    /**
     * @var SituationHandler
     */
    private $situationHandler;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig, SituationHandler $situationHandler, TranslatorInterface $translator)
    {
        $this->situationHandler = $situationHandler;
        $this->translator = $translator;
        $this->twig = $twig;
    }

    /**
     * @Route("/",name="native.admin.situation.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->situationHandler;
        $datatable = $handler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            /** @var SituationRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/User/Actions/Situation/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/add", name="native.admin.situation.add")
     */
    public function add(Request $request,User $user)
    {
        $form = $this->situationHandler->createForm(SituationFormType::class,new Situation($user));
        $html = $this->twig->render('Admin/User/Actions/Situation/add.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);

        if($this->situationHandler->process($form,$request)){
            $url = $this->situationHandler->generateUrl('native.admin.situation.show',[
                'slug' => $this->situationHandler->getSituation()->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/show", name="native.admin.situation.show")
     */
    public function show(Request $request)
    {
        $situation = $this->situationHandler->getSituationWithDetail($request,'slug');
        $html = $this->twig->render('Admin/User/Actions/Situation/show.html.twig', [
            'situation' => $situation
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit", name="native.admin.situation.edit")
     */
    public function edit(Request $request)
    {
        $situation = $this->situationHandler->getSituationWithDetail($request,'slug');
        $form = $this->situationHandler->createForm(SituationFormType::class,$situation);
        $html = $this->twig->render('Admin/User/Actions/Situation/edit.html.twig', [
            'situation' => $situation,
            'form' => $form->createView()
        ]);
        if($this->situationHandler->process($form,$request)){
            $url = $this->situationHandler->generateUrl('native.admin.situation.show',[
                'slug' => $this->situationHandler->getSituation()->getSlug()
            ]);
            return new RedirectResponse($url);
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete", name="native.admin.situation.delete")
     * @param Request $request
     * @param Situation $situation
     * @return RedirectResponse
     */
    public function delete(Request $request,Situation $situation)
    {
        $form = $this->situationHandler->createDeleteForm($situation->getId(),'native.admin.situation.delete');

        if($this->situationHandler->process($form,$request,$situation)){
            $url = $this->situationHandler->generateUrl('native.admin.user.show',[
                'slug' => $situation->getUser()->getSlug()
            ]);
        }else{
            $url = $this->situationHandler->generateUrl('native.admin.situation.show',[
                'slug' => $situation->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }
}