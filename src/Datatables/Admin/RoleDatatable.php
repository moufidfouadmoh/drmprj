<?php

namespace App\Datatables\Admin;


use App\Entity\User;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class RoleDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['username'] = $this->twig->render('Admin/User/includes/table/columns/username.html.twig',[
                'user' => $row
            ]);

            $row['nom_complet'] = $this->twig->render('Admin/User/includes/table/columns/nom_complet.html.twig',[
                'user' => $row
            ]);

            $row['gender'] = $this->twig->render('Admin/User/includes/table/columns/gender.html.twig',[
                'user' => $row
            ]);

            /*$row['statut'] = $this->twig->render('Admin/User/includes/table/columns/statut.html.twig',[
                'user' => $row
            ]);*/

            $row['age'] = $this->twig->render('Admin/User/includes/table/columns/age.html.twig',[
                'user' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/User/includes/table/columns/actions.html.twig',[
                'user' => $row
            ]);

            return $row;
        };
        return $formatter;
    }

    /**
     * @param array $options
     * @throws \Exception
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            'cdn_language_by_locale' => true
        ));

        $this->options->set(array(
            'classes' => 'table table-hover table-bordered',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true
        ));

        $this->ajax->set(array());

        $this->features->set(array(
            'searching' => false
        ));

        $this->extensions->set(array(
            /*'select' => array(
                'blurable' => false,
                'class_name' => 'selected',
                'info' => true,
                'items' => 'row',
                'selector' => 'td, th',
                'style' => 'os',
            )*/
        ));

        $this->events->set(array(

        ));

        $this->columnBuilder
            ->add('username', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.user.table.fields.username')
            ))
            ->add('nom_complet', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.user.table.fields.nom_complet')
            ))
            /*->add('statut', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.user.table.fields.statut')
            ))*/
            ->add('age', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.user.table.fields.age')
            ))
            ->add('gender', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.user.table.fields.sexe')
            ))
            ->add('actions', VirtualColumn::class, array(
                'title' => $this->translator->trans('actions'),
                'width' => '20px'
            ))

        ;
    }

    /**
     * Returns the name of the entity.
     *
     * @return string
     */
    public function getEntity()
    {
        return User::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_user';
    }
}