<?php

namespace App\Datatables\Admin;

use App\Entity\Classement;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class ClassementDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['username'] = $this->twig->render('Admin/User/Actions/Classement/includes/table/columns/username.html.twig',[
                'classement' => $row
            ]);

            $row['nom_complet'] = $this->twig->render('Admin/User/Actions/Classement/includes/table/columns/nom_complet.html.twig',[
                'classement' => $row
            ]);

            $row['classement'] = $this->twig->render('Admin/User/Actions/Classement/includes/table/columns/classement.html.twig',[
                'classement' => $row
            ]);

            $row['day'] = $this->twig->render('Admin/User/Actions/Classement/includes/table/columns/day.html.twig',[
                'classement' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/User/Actions/Classement/includes/table/columns/actions.html.twig',[
                'classement' => $row
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
        $this->language->set([
            'cdn_language_by_locale' => true
        ]);

        $this->features->set([
            'searching' => false
        ]);

        $this->options->set([
            'classes' => 'table table-hover table-bordered',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true
        ]);

        $this->ajax->set(array());

        $this->extensions->set([
            /*'select' => array(
                'blurable' => false,
                'class_name' => 'selected',
                'info' => true,
                'items' => 'row',
                'selector' => 'td, th',
                'style' => 'os',
            )*/
        ]);

        $this->events->set(array(

        ));

        $this->columnBuilder
            ->add('username', VirtualColumn::class,[
                'title' => $this->translator->trans('app.classement.table.fields.username')
            ])
            ->add('nom_complet', VirtualColumn::class,[
                'title' => $this->translator->trans('app.classement.table.fields.nom_complet')
            ])
            ->add('classement', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.classement.table.fields.classement')
            ))
            ->add('day', VirtualColumn::class,[
                'title' => $this->translator->trans('app.classement.table.fields.date')
            ])

            ->add('actions', VirtualColumn::class,[
                'title' => $this->translator->trans('actions'),
                'width' => '250px'
            ])

        ;
    }

    /**
     * Returns the name of the entity.
     *
     * @return string
     */
    public function getEntity()
    {
        return Classement::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_classement';
    }
}