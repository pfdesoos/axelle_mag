<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route ("categorie/{id}", name="categorie")
     */

    public function categorieAction()
    {
        return $this->render('default/categorie.html.twig');
    }
    /**
     * @Route("categorie/{catId}/article/{id}", name="article")
     */

    public function articleAction()
    {
        return $this->render('default/article.html.twig');
    }
}
