<?php

namespace App\Datatables\Admin;

use App\Entity\InterventionInterne;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class InterventionInterneDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['date'] = $this->twig->render('Admin/Intervention/Interne/includes/table/columns/date.html.twig',[
                'intervention' => $row
            ]);

            $row['bureaus'] = $this->twig->render('Admin/Intervention/Interne/includes/table/columns/bureaus.html.twig',[
                'intervention' => $row
            ]);

            $row['users'] = $this->twig->render('Admin/Intervention/Interne/includes/table/columns/users.html.twig',[
                'intervention' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/Intervention/Interne/includes/table/columns/actions.html.twig',[
                'intervention' => $row
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
            ->add('date', VirtualColumn::class,[
                'title' => $this->translator->trans('app.intervention.table.fields.date')
            ])
            ->add('bureaus', VirtualColumn::class,[
                'title' => $this->translator->trans('app.intervention.table.fields.bureaus')
            ])
            ->add('users', VirtualColumn::class,[
                'title' => $this->translator->trans('app.intervention.table.fields.users')
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
        return InterventionInterne::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_intervention_interne';
    }
}