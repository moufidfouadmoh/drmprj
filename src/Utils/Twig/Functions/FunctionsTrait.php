<?php


namespace App\Utils\Twig\Functions;

use Twig\TwigFunction;

trait FunctionsTrait
{
    use FormFunctionTrait;

    private function functionsTrait()
    {
        $functions = [
            new TwigFunction('print_link', array($this, 'displayPrintLink'), array(
                'is_safe' => array('html')
            )),
            //new TwigFunction('conge_nom', array($this, 'displayCongeNom')),
        ];

        return array_merge(
            $functions,
            $this->formFunctionTrait()
        );
    }

    public function displayPrintLink($path,$parameters = array(),$label,$class,$icon)
    {
        return $this->twig->render('print/print.html.twig',array(
            'path' => $path,
            'parameters' => $parameters,
            'label' => $label,
            'class' => $class,
            'icon' => $icon
        ));
    }
}