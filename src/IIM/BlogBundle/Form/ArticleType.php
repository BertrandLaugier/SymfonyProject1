<?php

namespace IIM\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title')
            ->add('content')
            ->add('enabled')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IIM\BlogBundle\Entity\Article',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'article';
    }
}
