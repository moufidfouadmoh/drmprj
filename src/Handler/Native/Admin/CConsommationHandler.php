<?php

namespace App\Handler\Native\Admin;

use App\Datatables\Admin\CConsommationDatatable;
use App\Entity\CConsommation;
use App\Event\Subscriber\ConsommationEvent;
use App\Manager\CConsommationManagerInterface;
use App\Manager\CModeleManagerInterface;
use App\Manager\UserManagerInterface;
use App\Utils\AdminDatatableHandler;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class CConsommationHandler extends AdminDatatableHandler
{
    /**
     * @var CConsommation
     */
    private $consommation;
    /**
     * @var CModeleManagerInterface
     */
    private $modeleManager;
    /**
     * @var UserManagerInterface
     */
    private $userManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var CConsommationManagerInterface
     */
    private $consommationManager;

    public function __construct(DatatableFactory $datatableFactory,
                                DatatableResponse $datatableResponse,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                FlashBagInterface $flashBag,
                                CConsommationManagerInterface $consommationManager,
                                CModeleManagerInterface $modeleManager,
                                UserManagerInterface $userManager,
                                EventDispatcherInterface $dispatcher)
    {
        parent::__construct($datatableFactory, $datatableResponse, $formFactory, $router);
        $this->modeleManager = $modeleManager;
        $this->userManager = $userManager;
        $this->flashBag = $flashBag;
        $this->dispatcher = $dispatcher;
        $this->consommationManager = $consommationManager;
    }

    /**
     * @return CConsommation
     */
    public function getConsommation(): CConsommation
    {
        return $this->consommation;
    }

    /**
     * @return CModeleManagerInterface
     */
    public function getModeleManager(): CModeleManagerInterface
    {
        return $this->modeleManager;
    }

    /**
     * @return \Sg\DatatablesBundle\Datatable\DatatableInterface
     * @throws \Exception
     */
    public function buildDatatable()
    {
        $datatable = $this->datatableFactory->create(CConsommationDatatable::class);
        $datatable->buildDatatable();
        return $datatable;
    }

    public function getRepository()
    {
        return $this->consommationManager->getRepository();
    }

    public function process(FormInterface $form,Request $request,CConsommation $consommation = null)
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(is_null($consommation)){
                $event = new ConsommationEvent($form->getData());
                $this->dispatcher->dispatch(ConsommationEvent::ON_CREATE,$event);
                $saved = $this->consommationManager->save($form->getData());
                if(!is_null($saved)){
                    $this->consommation = $saved;
                    return true;
                }
            }else{
                return $this->consommationManager->delete($consommation);
            }

        }
        return false;
    }

    public function handleCheck(FormInterface $form,Request $request){
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CConsommation $data */
            $data = $form->getData();
            $user = $data->getUser();
            $statut = $user->getCurrentStatut();
            if(!is_null($statut)){
                $result = $this->modeleManager->getCModelesByNomAndStatuts(true,[$statut->getNom()]);
                if(!empty($result)){
                    return array(
                        'user' => $user,
                        'result' => $result
                    );
                }else{
                    return array(
                        'user' => $user
                    );
                }
            }else{
                return $user;
            }
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

    public function addFlashMessage($type,$message)
    {
        $this->flashBag->add($type,$message);
    }

    public function getCModeleBySlugWithDetail(Request $request,$param)
    {
        $slug = $request->get($param);
        $modele = $this->modeleManager->getCModeleBySlugWithDetail($slug);
        if(!is_null($modele)){
            return $modele;
        }
        throw new NotFoundHttpException();
    }

    public function getCModelesByNomAndStatuts($etat,$statuts = [])
    {
        $modeles = $this->modeleManager->getCModelesByNomAndStatuts($etat,$statuts);
        if(!empty($modeles)){
            return $modeles;
        }
        throw new NotFoundHttpException();
    }

    public function getCConsommationWithDetail(Request $request, $param)
    {
        $slug = $request->get($param);
        $consommation = $this->consommationManager->getCConsommationBySlugWithDetail($slug);
        if(!is_null($consommation)){
            return $consommation;
        }
        throw  new NotFoundHttpException();
    }
}