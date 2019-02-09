<?php

namespace App\Datatables\Admin;


use App\Entity\Affectation;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class AffectationDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['username'] = $this->twig->render('Admin/User/Actions/Affectation/includes/table/columns/username.html.twig',[
                'affectation' => $row
            ]);

            $row['nom_complet'] = $this->twig->render('Admin/User/Actions/Affectation/includes/table/columns/nom_complet.html.twig',[
                'affectation' => $row
            ]);

            $row['day'] = $this->twig->render('Admin/User/Actions/Affectation/includes/table/columns/day.html.twig',[
                'affectation' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/User/Actions/Affectation/includes/table/columns/actions.html.twig',[
                'affectation' => $row
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

        $this->features->set(array(
            'searching' => false
        ));

        $this->options->set(array(
            'classes' => 'table table-hover table-bordered',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true
        ));

        $this->ajax->set(array());

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
                'title' => $this->translator->trans('app.affectation.table.fields.username')
            ))
            ->add('nom_complet', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.affectation.table.fields.nom_complet')
            ))
            ->add('day', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.affectation.table.fields.date')
            ))

            ->add('actions', VirtualColumn::class, array(
                'title' => $this->translator->trans('actions'),
                'width' => '250px'
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
        return Affectation::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_affectation';
    }
}