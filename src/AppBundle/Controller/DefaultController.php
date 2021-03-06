<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Mentions;
use AppBundle\Entity\Newsletter;
use AppBundle\Entity\Rubrique;
use AppBundle\Entity\SelectedArticle;
use AppBundle\Entity\SiteInfo;
use AppBundle\Entity\SubRubric;
use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    private function articleShowFirst()
    {
        return $this->getDoctrine()
            ->getRepository(SelectedArticle::class)
            ->articleShowIndex();
    }
    private function articleRightMenu()
    {
        return  $this->getDoctrine()
            ->getRepository(SelectedArticle::class)
            ->articlesRightMenu();
    }

    private function sousRubriqueLeftMenu()
    {
        return  $this->getDoctrine()
            ->getRepository(SubRubric::class)
            ->subRubriqueLeftMenu();
    }

    private function categorieLeftMenu()
    {
        return  $this->getDoctrine()
            ->getRepository(Rubrique::class)
            ->findAll();
    }

    private function articlesIndex()
    {
        return $this->getDoctrine()
            ->getRepository(Article::class)
            ->articlesIndex();
    }

    private function searchArcticles($search)
    {
        return $this->getDoctrine()
            ->getRepository(Article::class)
            ->searchArticle($search);
    }

    private function getReseauxSociaux()
    {
        return $this->getDoctrine()
            ->getRepository(SiteInfo::class)
            ->getReseaux();
    }

    private function getApropos()
    {
        return $this->getDoctrine()
            ->getRepository(SiteInfo::class)
            ->getContentApropos();
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(\Swift_Mailer $mailer)
    {
        return $this->render('default/index.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
            'categoriesLeftMenu' => $this->categorieLeftMenu(),
            'sousRubriqueLeftMenu' => $this->sousRubriqueLeftMenu(),
            'reseaux' => $this->getReseauxSociaux()[0],
            'articlesIndex' => $this->articlesIndex(),
            'articleShowFirst' => $this->articleShowFirst()[0]
        ));
    }

    /**
     * @Route("/mentions", name="mentions" )
     */
    public function mentionsAction ()
    {
        $mentions = $this->getDoctrine()
            ->getRepository(Mentions::class)
            ->findAll();
        return $this->render('default/mentions.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
            'categoriesLeftMenu' => $this->categorieLeftMenu(),
            'sousRubriqueLeftMenu' => $this->sousRubriqueLeftMenu(),
            'reseaux' => $this->getReseauxSociaux()[0],
            'mentions' => $mentions[0]
        ));
    }

    /**
     * @Route("/contact", name="contact" )
     */
    public function contactAction ()
    {
        $contact = $this->getDoctrine()
            ->getRepository(contact::class)
            ->findAll();
        return $this->render('default/contact.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
            'categoriesLeftMenu' => $this->categorieLeftMenu(),
            'sousRubriqueLeftMenu' => $this->sousRubriqueLeftMenu(),
            'reseaux' => $this->getReseauxSociaux()[0],
            'contact' => $contact[0]
        ));
    }

    /**
     * @Route ("/categorie/{catId}", name="categorie")
     */
    public function categorieAction($catId)
    {
        $rubrique = $this->getDoctrine()
            ->getRepository(SubRubric::class)
            ->find($catId);
        if(empty($rubrique))
        {
            return $this->redirectToRoute('index');
        } else {

            return $this->render('default/categorie.html.twig', array(
                'articlesRightMenu' => $this->articleRightMenu(),
                'categoriesLeftMenu' => $this->categorieLeftMenu(),
                'sousRubriqueLeftMenu' => $this->sousRubriqueLeftMenu(),
                'reseaux' => $this->getReseauxSociaux()[0],
                'rubrique' => $rubrique
            ));
        }
    }

    /**
     * @Route("/article/{id}", name="article", requirements={"id"="\d+"})
     */
    public function articleAction($id)
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)->find($id);

        if(empty($article))
        {
            return $this->redirectToRoute('index');
        } else {
            return $this->render('default/article.html.twig', array(
                'articlesRightMenu' => $this->articleRightMenu(),
                'categoriesLeftMenu' => $this->categorieLeftMenu(),
                'sousRubriqueLeftMenu' => $this->sousRubriqueLeftMenu(),
                'reseaux' => $this->getReseauxSociaux()[0],
                'article' => $article
            ));
        }
    }

    /**
     * @Route ("/recherche/", name="recherche")
     */
    public function searchAction(Request $request)
    {
        $search = $request->query->get('search');
        return $this->render('default/search.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
            'categoriesLeftMenu' => $this->categorieLeftMenu(),
            'sousRubriqueLeftMenu' => $this->sousRubriqueLeftMenu(),
            'reseaux' => $this->getReseauxSociaux()[0],
            'articleSearched' => $this->searchArcticles($search),
        ));
    }

    /**
     * @Route ("/apropos/", name="apropos")
     */
    public function aproposAction()
    {
        return $this->render('default/apropos.html.twig', array(
            'articlesRightMenu' => $this->articleRightMenu(),
            'categoriesLeftMenu' => $this->categorieLeftMenu(),
            'sousRubriqueLeftMenu' => $this->sousRubriqueLeftMenu(),
            'reseaux' => $this->getReseauxSociaux()[0],
            'apropos' => $this->getApropos()[0]
        ));
    }

    /**
     * @Route("/subscribe", name="subscribe")
     */
    public function subscribeAction(Request $request)
    {
        $email = htmlspecialchars($request->request->get(('newsLetterMail')));

        $findIfEmailExists = $this->getDoctrine()
            ->getRepository(Newsletter::class)
            ->findBy(['email' => $email]);

        if(count($findIfEmailExists) === 0)
        {
            $message = (new \Swift_Message('Inscription A La Newsletter d\'Axelle Magazine'))
                ->setFrom('tira.nicolas@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'Emails/newsletter.html.twig'
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            $em = $this->getDoctrine()->getManager();
            $mail = new Newsletter();
            $mail->setEmail($email);
            $em->persist($mail);
            $em->flush();
        }
        return $this->redirectToRoute('index');
    }
}
