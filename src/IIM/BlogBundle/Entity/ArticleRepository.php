<?php
/**
 * Created by PhpStorm.
 * User: Bertrand
 * Date: 15/01/14
 * Time: 16:28
 */

namespace IIM\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository{

    function getFindQueryBuilder(){
        return $this->createQueryBuilder('a')
            ->where('a.content LIKE :content');
    }
} 