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

use JMS\Serializer\SerializationContext;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;



/**
 * Article controller.
 *
 * @Route("/api")
 */
class APIController extends FOSRestController
{
    /**
     * Get a single article with API controller.
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
     *
     * @throws NotFoundHttpException when article not exist
     */
    public function getArticleAction($id)
    {
        $entity = $this->get('article.manager')->find($id,true);

        $view = new View($entity);
        //$view->setSerializationContext($this->getContext());

        return $this->handleView($view);

    }


    /**
     * List all articles with API Controller.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "IIM\BlogBundle\Form\SearchType",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @return array
     *
     */
    public function getArticlesAction(Request $request)
    {
        $form = $this->createForm('search',null,[
            'csrf_protection' => false,
            'method' =>'GET'
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $articles = $this->get('article.manager')->search($form->getData());
        } else {
            $articles = $this->get('article.manager')->findAll();
        }
        $view = new View($articles);
        return $this->handleView($view);
    }

    /**
     * Create a new article with API controller.
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
     */
    public function postArticlesAction(Request $request)
    {
        $form = $this->createForm('article',null,[
            'csrf_protection' => false
        ]);
        $form->submit($request);
        if ($form->isValid()) {
            $entity = $form->getData();
            $entity->setAuthor($this->getUser());
            $this->get('article.manager')->update($entity);
            $view = new View($entity);
            return $this->handleView($view);
        }
        $view = new View($form);
        return $this->handleView($view);
    }


    /**
     * update an article with API controller.
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
     */
    public function putArticlesAction(Request $request, $id)
    {
        $entity = $this->get('article.manager')->find($id, true);
        $form = $this->createForm('article', $entity,[
            'csrf_protection' => false
        ]);
        $form->submit($request);
        if ($form->isValid()) {
            $this->get('article.manager')->update($entity);
            $view = new View($entity);
            return $this->handleView($view);
        }
        $view = new View($form);
        return $this->handleView($view);
    }

    /**
     * Delete an article with API controller.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful",
     *   }
     * )
     *
     * @param int     $id      the article id
     */
    public function deleteArticleAction(Article $article)
    {
        $this->get('article.manager')->delete($article);
    }

    protected function getContext()
    {
        $context = new SerializationContext();
        $context->shouldSerializeNull();
        $context->setVersion(1);
        $context->setGroups(['FULL']);

        return $context;
    }


}
