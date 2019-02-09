<?php

namespace App\Twig;

use App\Entity\User;
use App\Utils\Twig\Filters\FiltersTrait;
use App\Utils\Twig\Functions\FunctionsTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    use FiltersTrait,FunctionsTrait;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var FormFactoryInterface
     */
    private $form;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorization;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(Environment $twig,
                                TranslatorInterface $translator,
                                AuthorizationCheckerInterface $authorization,
                                FormFactoryInterface $form,
                                RouterInterface $router)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->form = $form;
        $this->authorization = $authorization;
        $this->router = $router;
    }

    public function getFilters(): array
    {
        $filters = [
            new TwigFilter('date_interval', array($this, 'displayDateInterval')),
        ];
        return array_merge(
            $filters,
            $this->filtersTrait()
        );
    }

    public function getFunctions(): array
    {
        $functions = [
            new TwigFunction('navbar_left', [$this, 'displayNavbarLeft'],['is_safe' => array('html')]),
            new TwigFunction('confirm_delete', [$this, 'displayConfirmDelete'],['is_safe' => array('html')]),
            new TwigFunction('fin_delai', [$this, 'displayFinDelai'])
        ];
        return array_merge(
            $functions,
            $this->functionsTrait()
        );
    }

    public function displayNavbarLeft(User $user)
    {
        $isAdmin = $this->authorization->isGranted(User::ROLE_SUPER_ADMIN);
        return $this->twig->render('Global/includes/navbar_left.html.twig',[
            'user' => $user,
            'is_admin' => $isAdmin
        ]);
    }

    public function displayDateInterval(\DateInterval $intervalle = null)
    {
        $result = '';
        if(!is_null($intervalle)){
            $nbre_an = $intervalle->format('%Y');
            $nbre_mois = $intervalle->format('%m');
            $nbre_jour = $intervalle->format('%d');

            if($nbre_an > 0){
                $an = $this->translator->trans(
                    $nbre_an > 1 ? 'age.an.plural' : 'age.an.singular',
                    array(),
                    'messages'
                );
                $result = $nbre_an .  ' '. $an;
            }

            if($nbre_mois > 0){
                $mois = $this->translator->trans(
                    'age.mois.plural',
                    array(),
                    'messages'
                );
                $result = $result .' '. $nbre_mois . ' '. $mois;
            }

            if($nbre_jour > 0){
                $jour = $this->translator->trans(
                    $nbre_jour > 1 ? 'age.jour.plural' : 'age.jour.singular',
                    array(),
                    'messages'
                );
                $result = $result .' '. $nbre_jour .  ' '. $jour;
            }
        }

        return $result;

    }

    public function displayConfirmDelete($entity,$field)
    {
        return $this->twig->render('Admin/Default/confirm_message.html.twig',
            array(
                'entity' => $entity,
                'field' => $field
            )
        );
    }

    public function displayFinDelai(\DateTime $date,\DateInterval $interval)
    {
        $start = clone $date;
        $start->sub(new \DateInterval('P1D'));
        $end = clone $start;
        $interval = clone $interval;
        $end = $end->add($interval);
        return $end;
    }
}