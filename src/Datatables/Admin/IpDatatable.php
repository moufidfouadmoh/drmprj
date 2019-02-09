<?php

namespace App\Datatables\Admin;

use App\Entity\Ip;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class IpDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['address'] = $this->twig->render('Admin/Ip/includes/table/columns/address.html.twig',[
                'ip' => $row
            ]);

            $row['bureau'] = $this->twig->render('Admin/Ip/includes/table/columns/bureau.html.twig',[
                'ip' => $row
            ]);

            $row['agence'] = $this->twig->render('Admin/Ip/includes/table/columns/agence.html.twig',[
                'ip' => $row
            ]);

            $row['actions'] = $this->twig->render('Admin/Ip/includes/table/columns/actions.html.twig',[
                'ip' => $row
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
            ->add('address', VirtualColumn::class,[
                'title' => $this->translator->trans('app.ip.table.fields.address'),
            ])
            ->add('bureau', VirtualColumn::class,[
                'title' => $this->translator->trans('app.ip.table.fields.bureau'),
            ])
            ->add('agence', VirtualColumn::class,[
                'title' => $this->translator->trans('app.ip.table.fields.agence'),
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
        return Ip::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_datatable_ip';
    }
}