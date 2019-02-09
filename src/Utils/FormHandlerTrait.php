<?php

namespace App\Utils;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;

trait FormHandlerTrait
{
    use RouterHandlerTrait;
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    public function createForm($type,$data = null,$options = [])
    {
        $form = $this->formFactory->create($type,$data,$options);
        return $form;
    }

    public function createSaveForm($type,$entity,$route,$routeParams = array(),$formOptions = array())
    {
        $options = array_merge(
            $formOptions,
            array('action' => $this->generateUrl($route,$routeParams))
        );
        $form = $this->createForm($type, $entity, $options);
        return $form;
    }

    public function createDeleteForm($id, $route)
    {
        return $this->formFactory->createBuilder(FormType::class,null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}