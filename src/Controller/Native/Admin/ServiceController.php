<?php

namespace App\Controller\Native\Admin;
use App\Entity\Service;
use App\Form\Type\ServiceFormType;
use App\Handler\Native\Admin\ServiceHandler;
use App\Repository\ServiceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class ServiceController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/service")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class ServiceController
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
     * @var ServiceHandler
     */
    private $serviceHandler;


    public function __construct(Environment $twig,
                                ServiceHandler $serviceHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->serviceHandler = $serviceHandler;
    }

    /**
     * @Route("/index",name="native.admin.service.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->serviceHandler;
        $form = $handler->createSaveForm(ServiceFormType::class,new Service(),'native.admin.service.create');
        $datatable = $handler->buildDatatable();
        if ($request->isXmlHttpRequest()) {
            /** @var ServiceRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/Bureau/Childs/Service/index.html.twig',[
            'datatable' => $datatable,
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/create",name="native.admin.service.create", condition="request.isXmlHttpRequest()")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create(Request $request)
    {
        $form = $this->serviceHandler->createSaveForm(ServiceFormType::class,new Service(),$request->get('_route'));
        $html = $this->twig->render('Admin/Bureau/Childs/Service/includes/_form.html.twig',[
            'form' => $form->createView()
        ]);
        if($this->serviceHandler->process($form,$request)){
            return new Response($this->translator->trans('save.success'));
        }
        return new Response($html);
    }

    /**
     * @Route("/{slug}/show",name="native.admin.service.show")
     * @param Service $service
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request)
    {
        $service = $this->serviceHandler->getDepartementWithDetail($request,'slug');
        $html = $this->twig->render('Admin/Bureau/Childs/Service/show.html.twig', [
            'service' => $service
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.service.edit")
     * @param Request $request
     * @param Service $service
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request)
    {
        $service = $this->serviceHandler->getDepartementWithDetail($request,'slug');
        $form = $this->serviceHandler->createForm(ServiceFormType::class,$service);
        $html = $this->twig->render('Admin/Bureau/Childs/Service/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->serviceHandler->process($form,$request)){
            $service = $this->serviceHandler->getService();
            return new RedirectResponse($this->serviceHandler->generateUrl('native.admin.service.show',[
                'slug' => $service->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.service.delete")
     * @param Request $request
     * @param Service $service
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Service $service)
    {
        $form = $this->serviceHandler->createDeleteForm($service->getId(),'native.admin.service.delete');

        if($this->serviceHandler->process($form,$request,$service)){
            $url = $this->serviceHandler->generateUrl('native.admin.service.index');
        }else{
            $url = $this->serviceHandler->generateUrl('native.admin.service.show',[
                'slug' => $service->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

}