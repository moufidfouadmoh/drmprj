<?php

namespace App\Datatables\Admin;

use App\Entity\Situation;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class SituationDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['username'] = $this->twig->render('Admin/User/Actions/Situation/includes/table/columns/username.html.twig',[
                'situation' => $row
            ]);

            $row['nom_complet'] = $this->twig->render('Admin/User/Actions/Situation/includes/table/columns/nom_complet.html.twig',[
                'situation' => $row
            ]);

            $row['statut_nom'] = $this->twig->render('Admin/User/Actions/Situation/includes/table/columns/statut_nom.html.twig',[
                'situation' => $row
            ]);

            $row['day'] = $this->twig->render('Admin/User/Actions/Situation/includes/table/columns/day.html.twig',[
                'situation' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/User/Actions/Situation/includes/table/columns/actions.html.twig',[
                'situation' => $row
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
                'title' => $this->translator->trans('app.situation.table.fields.username')
            ))
            ->add('nom_complet', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.situation.table.fields.nom_complet')
            ))
            ->add('statut_nom', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.situation.table.fields.statut_nom')
            ))
            ->add('day', VirtualColumn::class, array(
                'title' => $this->translator->trans('app.situation.table.fields.date')
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
        return Situation::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_situation';
    }
}