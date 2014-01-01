<?php

namespace IIM\BlogBundle\Controller;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use IIM\BlogBundle\Entity\Article;
use IIM\BlogBundle\Form\ArticleType;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;




/**
 * Article controller.
 *
 * @Route("/api")
 */
class APIController extends FOSRestController
{

    public function getArticleAction($id)
    {
        $entity = $this->get('article.manager')->find($id);

        if (!isset($entity)) {
            throw $this->createNotFoundException("Article does not exist.");
        }

        $view = new View($entity);
        return $this->handleView($view);

        return $entity;
    }


    public function getArticlesAction()
    {
        $entities = $this->get('article.manager')->findAll();

        $view = new View($entities);
        return $this->handleView($view);
    }




    public function postArticlesAction(Request $request)
    {
        $form = $this->createForm(new ArticleType());
        $form->submit($request);
        if ($form->isValid()) {
            $entity = $form->getData();
            $this->get('article.manager')->update($entity);

        }
        $view = new View($entity);
        return $this->handleView($view);


    }


    public function putArticlesAction(Request $request, $id)
    {
       /* $entity = $this->getEntity($id);

        $form = $this->createForm(new ArticleType(),$entity, array('method' => 'PUT'));
        $form->submit($request);
        if ($form->isValid()) {
            $entity = $form->getData();
            $this->get('article.manager')->update($entity);

        }
        $view = new View($entity);
        return $this->handleView($view);
*/

        $entity = $this->get('article.manager')->find($id);
        if (isset($entity)) {

            $form = $this->createForm(new ArticleType(), $entity);

            $form->submit($request);
            if ($form->isValid()) {
               // $article = $form->getData();
                $this->get('article.manager')->update($entity);
            }
        }



    }


    public function removeArticlesAction($id)
    {
        $entity = $this->get('article.manager')->find($id);

        if (!isset($entity)) {
            throw $this->createNotFoundException("Article does not exist.");
        }

        $entity->delete();
    }




}
