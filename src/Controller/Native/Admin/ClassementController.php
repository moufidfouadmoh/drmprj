<?php

namespace App\Controller\Native\Admin;
use App\Entity\Classement;
use App\Entity\User;
use App\Form\Type\ClassementFormType;
use App\Handler\Native\Admin\ClassementHandler;
use App\Repository\ClassementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class ClassementController
 * @package App\Controller\Native\Admin
 * @Route("/n/admin/classement")
 * @Security("is_granted('ROLE_ADMIN_PERSONNEL')",message="exception.authorization")
 */
class ClassementController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var ClassementHandler
     */
    private $classementHandler;

    public function __construct(Environment $twig, ClassementHandler $classementHandler, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->twig = $twig;
        $this->classementHandler = $classementHandler;
    }

    /**
     * @Route("/",name="native.admin.classement.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = $this->classementHandler;
        $datatable = $handler->buildDatatable();

        if ($request->isXmlHttpRequest()) {
            /** @var ClassementRepository $repository */
            $repository = $handler->getRepository();
            $qb = $handler->setQueryBuilderList(function () use ($repository){
                return $repository->selectAll();
            });
            return $handler->buildResponse($datatable,$qb);
        }
        $html = $this->twig->render('Admin/User/Actions/Classement/index.html.twig',[
            'datatable' => $datatable
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/add", name="native.admin.classement.add")
     */
    public function add(Request $request,User $user)
    {
        $form = $this->classementHandler->createForm(ClassementFormType::class,new Classement($user));
        $html = $this->twig->render('Admin/User/Actions/Classement/add.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);

        if($this->classementHandler->process($form,$request)){
            $url = $this->classementHandler->generateUrl('native.admin.classement.show',[
                'slug' => $this->classementHandler->getClassement()->getSlug()
            ]);
            return new RedirectResponse($url);
        }

        return new Response($html);
    }

    /**
     * @Route("/{slug}/show", name="native.admin.classement.show")
     */
    public function show(Request $request)
    {
        $classement = $this->classementHandler->getClassementWithDetail($request,'slug');
        $html = $this->twig->render('Admin/User/Actions/Classement/show.html.twig', [
            'classement' => $classement
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{slug}/edit", name="native.admin.classement.edit")
     */
    public function edit(Request $request)
    {
        $classement = $this->classementHandler->getClassementWithDetail($request,'slug');
        $form = $this->classementHandler->createForm(ClassementFormType::class,$classement);
        $html = $this->twig->render('Admin/User/Actions/Classement/edit.html.twig', [
            'classement' => $classement,
            'form' => $form->createView()
        ]);
        if($this->classementHandler->process($form,$request)){
            $url = $this->classementHandler->generateUrl('native.admin.classement.show',[
                'slug' => $this->classementHandler->getClassement()->getSlug()
            ]);
            return new RedirectResponse($url);
        }
        return new Response($html);
    }

    /**
     * @Route("/{id}/delete", name="native.admin.classement.delete")
     * @param Request $request
     * @param Classement $classement
     * @return RedirectResponse
     */
    public function delete(Request $request,Classement $classement)
    {
        $form = $this->classementHandler->createDeleteForm($classement->getId(),'native.admin.classement.delete');

        if($this->classementHandler->process($form,$request,$classement)){
            $url = $this->classementHandler->generateUrl('native.admin.user.show',[
                'slug' => $classement->getUser()->getSlug()
            ]);
        }else{
            $url = $this->classementHandler->generateUrl('native.admin.classement.show',[
                'slug' => $classement->getSlug()
            ]);
        }

        return new RedirectResponse($url);
    }
}