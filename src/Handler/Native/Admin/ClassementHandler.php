<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\ClassementDatatable;
use App\Entity\Classement;
use App\Event\Subscriber\ClassementEvent;
use App\Manager\ClassementManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class ClassementHandler extends AdminDatatableHandler
{
    /**
     * @var Classement
     */
    private $classement;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var ClassementManagerInterface
     */
    private $classementManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                ClassementManagerInterface $classementManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->dispatcher = $dispatcher;
        $this->classementManager = $classementManager;
    }

    /**
     * @return Classement
     */
    public function getClassement(): Classement
    {
        return $this->classement;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(ClassementDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->classementManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,Classement $classement = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($classement)){
                $data = $form->getData();
                $event = new ClassementEvent($data);
                $this->dispatcher->dispatch(ClassementEvent::ON_ADD_CLASSEMENT,$event);
                $saved = $this->classementManager->save($form->getData());
                if(!is_null($saved)){
                    $this->classement = $saved;
                    return true;
                }
            }else{
                return $this->classementManager->delete($classement);
            }

        }
        return false;
    }

    public function getClassementWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $classement = $this->classementManager->getClassementBySlugWithDetail($slug);
        if(!is_null($classement)){
            return $classement;
        }
        throw  new NotFoundHttpException();
    }
}