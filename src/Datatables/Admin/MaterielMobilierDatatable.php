<?php

namespace App\Datatables\Admin;

use App\Entity\MaterielMobilier;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class MaterielMobilierDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['equipement'] = $this->twig->render('Admin/Materiel/Childs/Mobilier/includes/table/columns/equipement.html.twig',[
                'materiel' => $row
            ]);

            $row['modele'] = $this->twig->render('Admin/Materiel/Childs/Mobilier/includes/table/columns/modele.html.twig',[
                'materiel' => $row
            ]);

            $row['quantite'] = $this->twig->render('Admin/Materiel/Childs/Mobilier/includes/table/columns/quantite.html.twig',[
                'materiel' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/Materiel/Childs/Informatique/includes/table/columns/actions.html.twig',[
                'materiel' => $row
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
            ->add('equipement', VirtualColumn::class,[
                'title' => $this->translator->trans('app.materiel.table.fields.equipement'),
            ])
            ->add('modele', VirtualColumn::class,[
                'title' => $this->translator->trans('app.materiel.table.fields.modele'),
            ])
            ->add('quantite', VirtualColumn::class,[
                'title' => $this->translator->trans('app.materiel.table.fields.quantite'),
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
        return MaterielMobilier::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_materiel_mobilier';
    }
}