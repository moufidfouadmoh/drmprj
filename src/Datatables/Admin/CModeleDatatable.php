<?php

namespace App\Datatables\Admin;


use App\Entity\CModele;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class CModeleDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['title'] = $this->twig->render('Admin/Conge/Modele/includes/table/columns/title.html.twig',[
                'cmodele' => $row
            ]);
            $row['statuts'] = $this->twig->render('Admin/Conge/Modele/includes/table/columns/statuts.html.twig',[
                'cmodele' => $row
            ]);
            $row['actions'] = $this->twig->render('Admin/Conge/Modele/includes/table/columns/actions.html.twig',[
                'cmodele' => $row
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
                'title' => $this->translator->trans('app.cmodele.table.fields.nom')
            ])
            ->add('statuts', VirtualColumn::class,[
                'title' => $this->translator->trans('app.cmodele.table.fields.statuts')
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
        return CModele::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_cmodele';
    }
}