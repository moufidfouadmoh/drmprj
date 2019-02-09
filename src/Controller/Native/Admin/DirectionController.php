<?php

namespace App\Controller\Native\Admin;
use App\Entity\Direction;
use App\Form\Type\DirectionFormType;
use App\Handler\Native\Admin\DirectionHandler;
use App\Repository\DirectionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class DirectionController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/direction")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class DirectionController
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
     * @var DirectionHandler
     */
    private $directionHandler;

    public function __construct(Environment $twig,
                                DirectionHandler $directionHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->directionHandler = $directionHandler;
    }

    /**
     * @Route("/index",name="native.admin.direction.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->directionHandler;
        $form = $handler->createSaveForm(DirectionFormType::class,new Direction(),'native.admin.direction.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var DirectionRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Bureau/Childs/Direction/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.direction.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->directionHandler->createSaveForm(DirectionFormType::class,new Direction(),$request->get('_route'));
        $html = $this->twig->render('Admin/Bureau/Childs/Direction/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->directionHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.direction.show")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $direction = $this->directionHandler->getDirectionWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Bureau/Childs/Direction/show.html.twig', [
            'direction' => $direction
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.direction.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $direction = $this->directionHandler->getDirectionWithDetail($request,'slug');
        $form = $this->directionHandler->createForm(DirectionFormType::class,$direction);
        $html = $this->twig->render('Admin/Bureau/Childs/Direction/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->directionHandler->process($form,$request)){
            $direction = $this->directionHandler->getDirection();
            return new RedirectResponse($this->directionHandler->generateUrl('native.admin.direction.show',[
                'slug' => $direction->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.direction.delete")
     * @param Request $request
     * @param Direction $direction
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Direction $direction)
    {
        $form = $this->directionHandler->createDeleteForm($direction->getId(),'native.admin.direction.delete');

        if($this->directionHandler->process($form,$request,$direction)){
            $url = $this->directionHandler->generateUrl('native.admin.direction.index');
        }else{
            $url = $this->directionHandler->generateUrl('native.admin.direction.show',[
                'slug' => $direction->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}