<?php
namespace IIM\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ArticleAdmin extends Admin
{
    //liste des champs modifiables dans l'edit
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('title')
            ->add('content')
            ->add('enabled')
            ->end()
        ;
    }

    //liste des champs qui seront visibles dans la liste des enregistrements
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('content')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    //liste des champs qui pourraient servir à trier les enregistrements dans la liste des enregistrements
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('author', null, array('field_options' => array('expanded' => true, 'multiple' => true)))
            ->add('content')
            ->add('enabled')
        ;
    }

    // champs visibles dans show
    protected function configureShowField(ShowMapper $show)
    {
        $show
            ->add('title')
            ->add('content')
            ->add('enabled')
        ;
    }
}