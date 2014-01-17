<?php

namespace IIM\BlogBundle\Manager;


class ArticleManager extends Manager{

    public function search($args)
    {
        $args['content'] = "%".$args['content']."%";

        $articles = $this->repository->getFindQueryBuilder()
            ->setParameters($args)
            ->getQuery()
            ->getResult();

        return $articles;
    }
} 