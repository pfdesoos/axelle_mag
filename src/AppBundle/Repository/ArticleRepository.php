<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\SubRubric; 

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function articlesIndex()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM AppBundle:Article a ORDER BY a.id DESC'
            )
            ->setMaxResults(20)
            ->getResult();
    }

    public function searchArticle($search)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM AppBundle:Article a WHERE a.titre LIKE :search ORDER BY a.id DESC'
            )
            ->setParameter('search', '%'.$search.'%')
            ->getResult();
    }
}
