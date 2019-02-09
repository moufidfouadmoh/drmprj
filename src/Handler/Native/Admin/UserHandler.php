<?php

namespace App\Handler\Native\Admin;


use App\Datatables\Admin\UserDatatable;
use App\Entity\User;
use App\Manager\UserManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class UserHandler extends AdminDatatableHandler
{
    /** @var User */
    private $user;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var UserManagerInterface
     */
    private $userManager;


    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                UserManagerInterface $userManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->dispatcher = $dispatcher;
        $this->userManager = $userManager;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param null $table
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable($table = null)
    {
        $datatable = $this->datatableFactory->create(is_null($table) ? UserDatatable::class : $table);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->userManager->getRepository();
    }

    public function process(FormInterface $form,Request $request)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $saved = $this->userManager->save($form->getData());
            if(!is_null($saved)){
                $this->user = $saved;
                return true;
            }
            return false;
        }
        return false;
    }

    public function getUserBySlugWithDetail(Request $request,$param)
    {
        $slug = $request->get($param);
        $user = $this->userManager->getUserBySlugWithDetail($slug);

        if(!is_null($user)){
            return $user;
        }
        throw new NotFoundHttpException();
    }
}