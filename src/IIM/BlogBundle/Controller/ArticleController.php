<?php

namespace IIM\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IIM\BlogBundle\Entity\Article;
use IIM\BlogBundle\Form\ArticleType;
use Symfony\Component\Validator\Constraints\DateTime;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;
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
 * @Route("/article")
 */
class ArticleController extends Controller
{

    /**
     * List all articles.
     * @Rest\View
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
     */
    public function allAction()
    {
        $articles = $this->get('article.manager')->findAll();

        return array('articles' => $articles);
    }

    /**
     * Get a single article.
     * @Rest\View
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
    public function getAction($id,$exception = false)
    {
        $article = $this->get('article.manager')->find($id, true);

        return array('article' => $article);

    }


    /**
     * Create a new article.
     * @Rest\View
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
    public function newAction(Request $request)
    {
        $form = $this->createForm('article');
        $form->submit($request);
        if ($form->isValid()) {
            $entity = $form->getData();
            $this->get('article.manager')->update($entity);

        }
        return array('article' => $entity);
    }


    /**
     * update an article.
     * @Rest\View
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
    public function editAction(Request $request, $id)
    {

        $entity = $this->get('article.manager')->find($id);

        if (null != $entity) {

            $form = $this->createForm('article', $entity);


            $form->submit($request);
            if ($form->isValid()) {
                $article = $form->getData();
                $this->get('article.manager')->update($article);
            }
        }
    }

    /**
     * Delete an article.
     * @Rest\View(statusCode=204)
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
     * @throws NotFoundHttpException when note not exist
     */
    public function removeAction(Article $article)
    {
        $this->get('article.manager')->delete($article);
    }

}
