<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function articlesRightMenu()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM AppBundle:Article a ORDER BY a.id DESC'
            )
            ->setMaxResults(3)
            ->getResult();
    }
}
