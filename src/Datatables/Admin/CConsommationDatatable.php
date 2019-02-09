<?php

namespace App\Datatables\Admin;

use App\Entity\CConsommation;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class CConsommationDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['owner'] = $this->twig->render('Admin/Conge/Consommation/includes/table/columns/owner.html.twig',[
                'cconsommation' => $row
            ]);
            $row['delai'] = $this->twig->render('Admin/Conge/Consommation/includes/table/columns/delai.html.twig',[
                'cconsommation' => $row
            ]);
            $row['debut'] = $this->twig->render('Admin/Conge/Consommation/includes/table/columns/debut.html.twig',[
                'cconsommation' => $row
            ]);
            $row['modele'] = $this->twig->render('Admin/Conge/Consommation/includes/table/columns/modele.html.twig',[
                'cconsommation' => $row
            ]);
            $row['fin'] = $this->twig->render('Admin/Conge/Consommation/includes/table/columns/fin.html.twig',[
                'cconsommation' => $row
            ]);
            $row['actions'] = $this->twig->render('Admin/Conge/Consommation/includes/table/columns/actions.html.twig',[
                'cconsommation' => $row
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
            ->add('modele', VirtualColumn::class,[
                'title' => $this->translator->trans('app.cconsommation.table.fields.modele')
            ])
            ->add('owner', VirtualColumn::class,[
                'title' => $this->translator->trans('app.cconsommation.table.fields.owner')
            ])
            ->add('debut', VirtualColumn::class,[
                'title' => $this->translator->trans('app.cconsommation.table.fields.date.debut')
            ])
            ->add('delai', VirtualColumn::class,[
                'title' => $this->translator->trans('app.cconsommation.table.fields.delai')
            ])
            ->add('fin', VirtualColumn::class,[
                'title' => $this->translator->trans('app.cconsommation.table.fields.date.fin')
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
        return CConsommation::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_cconsommation';
    }
}