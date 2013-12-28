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

    /**
     * Get a single article.
     *
     * @ApiDoc(
     *   output = "IIM\BlogBundle\Entity\Article",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the article is not found"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param int     $id      the article id
     *
     * @return array
     *
     * @Route("/article/{id}")
     *
     * @throws NotFoundHttpException when article not exist
     */
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


    /**
     * List all articles.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return array
     *
     * @Route("/articles")
     */
    public function getArticlesAction()
    {
        $entities = $this->get('article.manager')->findAll();

        $view = new View($entities);
        return $this->handleView($view);
    }




    /**
     * Creates a new article from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "IIM\BlogBundle\Form\ArticleType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @Route("/article/")
     */

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

    /**
     * update an article from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "IIM\BlogBundle\Form\ArticleType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     *
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request the request object

     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @Route("/article/{id}")
     */

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


    /**
     * Removes an article.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful",
     *   }
     * )
     *
     * @param int     $id      the article id
     *
     *
     *
     * @Route("/article/{id}")
     *
     * @throws NotFoundHttpException when note not exist
     */
    public function removeArticlesAction($id)
    {
        $entity = $this->get('article.manager')->find($id);

        if (!isset($entity)) {
            throw $this->createNotFoundException("Article does not exist.");
        }

        $entity->delete();
    }




}
