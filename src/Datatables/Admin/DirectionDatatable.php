<?php

namespace App\Datatables\Admin;

use App\Entity\Direction;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class DirectionDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['title'] = $this->twig->render('Admin/Bureau/Childs/Direction/includes/table/columns/title.html.twig',[
                'direction' => $row
            ]);

            $row['tel'] = $this->twig->render('Admin/Bureau/Childs/Direction/includes/table/columns/tel.html.twig',[
                'direction' => $row
            ]);

            $row['departements'] = $this->twig->render('Admin/Bureau/Childs/Direction/includes/table/columns/departements.html.twig',[
                'direction' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/Bureau/Childs/Direction/includes/table/columns/actions.html.twig',[
                'direction' => $row
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
           ->add('title', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.direction.table.fields.nom')
            ))
            ->add('tel', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.direction.table.fields.telephone')
            ))
            ->add('departements', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.direction.table.fields.departements')
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
        return Direction::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_direction';
    }
}