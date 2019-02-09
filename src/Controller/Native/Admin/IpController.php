<?php

namespace App\Controller\Native\Admin;

use App\Entity\Includes\Search\IpSearch;
use App\Entity\Ip;
use App\Form\Search\IpSearchForm;
use App\Form\Type\IpFormType;
use App\Handler\Native\Admin\IpHandler;
use App\Repository\IpRepository;
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
 * Class IpController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/ip")
 * @Security("is_granted('ROLE_ADMIN_IP')",message="exception.authorization")
 */
class IpController
{
    use PrintAbleTrait;
    const LIST_SESSION = 'ip_list';
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var IpHandler
     */
    private $ipHandler;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(Environment $twig,
                                IpHandler $ipHandler,
                                TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->ipHandler = $ipHandler;
        $this->translator = $translator;
    }

    /**
     * @Route("/index",name="native.admin.ip.index")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request,SessionInterface $session)
    {
        $handler = $this->ipHandler;
        $datatable = $handler->buildDatatable();
        $searched = new IpSearch();
        $search = $this->ipHandler->createForm(IpSearchForm::class,$searched);
        $search->handleRequest($request);
        $session->set(self::LIST_SESSION,$search->getData());
        if ($request->isXmlHttpRequest()) {
            /** @var IpRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository,$session){
                return $repository->selectAll($session->get(self::LIST_SESSION));
            });
            return $handler->buildResponse($datatable,$qb);
        }

        $html = $this->twig->render('Admin/Ip/index.html.twig',[
            'datatable' => $datatable,
            'search' => $search->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/add", name="native.admin.ip.add")
     */
    public function add(Request $request)
    {
        $form = $this->ipHandler->createForm(IpFormType::class,new Ip());
        $html = $this->twig->render('Admin/Ip/add.html.twig', [
            'form' => $form->createView()
        ]);

        if($this->ipHandler->process($form,$request)){
            $url = $this->ipHandler->generateUrl('native.admin.ip.show',[
                'slug' => $this->ipHandler->getIp()->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }


    /**
     * @Route("/{slug}/show",name="native.admin.ip.show")
     * @param Ip $ip
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Ip $ip)
    {
        $html = $this->twig->render('Admin/Ip/show.html.twig', [
            'ip' => $ip
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit",name="native.admin.ip.edit")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(Request $request,Ip $ip)
    {
        $form = $this->ipHandler->createForm(IpFormType::class,$ip);
        $html = $this->twig->render('Admin/Ip/edit.html.twig',[
            'form' => $form->createView()
        ]);

        if($this->ipHandler->process($form,$request)){
            $ip = $this->ipHandler->getIp();
            return new RedirectResponse($this->ipHandler->generateUrl('native.admin.ip.show',[
                'slug' => $ip->getSlug()
            ]));
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete",name="native.admin.ip.delete")
     * @param Request $request
     * @param Ip $ip
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Ip $ip)
    {
        $form = $this->ipHandler->createDeleteForm($ip->getId(),'native.admin.ip.delete');

        if($this->ipHandler->process($form,$request,$ip)){
            $url = $this->ipHandler->generateUrl('native.admin.ip.index');
        }else{
            $url = $this->ipHandler->generateUrl('native.admin.ip.show',[
                'slug' => $ip->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }

    /**
     * @Route("/pdf/list",name="native.admin.ip.pdf.list")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
	public function pdfList(SessionInterface $session)
    {
        $qb = $this->ipHandler->getRepository()->selectAll($session->get(self::LIST_SESSION));
        $list = $qb->getQuery()->getResult();

        $html = $this->twig->render('Admin/Ip/includes/pdf/list.html.twig', [
            'list' => $list
        ]);
        $this->print($html,"ips.pdf");
	}

}