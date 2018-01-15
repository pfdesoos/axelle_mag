<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private function articleRightMenu()
    {
        return  $this->getDoctrine()
            ->getRepository(Article::class)
            ->articlesRightMenu();
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
        ));
    }

    /**
     * @Route ("/{catId}", name="categorie")
     */

    public function categorieAction()
    {return $this->render('default/categorie.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
        ));
    }

    /**
     * @Route("/article/{id}", name="article")
     */

    public function articleAction()
    {
        return $this->render('default/article.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
        ));
    }

    /**
     * @Route ("/recherche/", name="recherche")
     */

    public function searchAction()
    {
        return $this->render('default/search.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
        ));
    }
}
