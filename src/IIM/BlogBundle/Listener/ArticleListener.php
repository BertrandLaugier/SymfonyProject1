<?php

namespace iim\BlogBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use iim\BlogBundle\Entity\Article;

class ArticleListener {


    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        //agir sur un article
        if ($entity instanceof Article) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $entity->setAuthor($user);
        }

    }
}