<?php

namespace App\Datatables\Admin;

use App\Entity\Agence;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class AgenceDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['title'] = $this->twig->render('Admin/Agence/includes/table/columns/title.html.twig',[
                'agence' => $row
            ]);

            $row['lieu'] = $this->twig->render('Admin/Agence/includes/table/columns/lieu.html.twig',[
                'agence' => $row
            ]);

            $row['bureaus'] = $this->twig->render('Admin/Agence/includes/table/columns/bureaus.html.twig',[
                'agence' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/Agence/includes/table/columns/actions.html.twig',[
                'agence' => $row
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
            ->add('title', VirtualColumn::class,[
                'title' => $this->translator->trans('app.agence.table.fields.nom')
            ])
            ->add('lieu', VirtualColumn::class,[
                'title' => $this->translator->trans('app.agence.table.fields.lieu')
            ])
            ->add('bureaus', VirtualColumn::class,[
                'title' => $this->translator->trans('app.agence.table.fields.bureaus')
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
        return Agence::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_agence';
    }
}