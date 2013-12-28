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
     * Lists all Article entities.
     *
     * @Route("/", name="article")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->get('article.manager')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Article entity.
     *
     * @Route("/create", name="article_create")
     * @Template("IIMBlogBundle:Article:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('article', null, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $article = $form->getData();
                $article->setAuthor($this->getUser());

                $this->get('article.manager')->update($article);

                return $this->redirect($this->generateUrl('article_show', array('id' => $article->getId())));
            }
        }

        return array(
            'form'   => $form->createView()
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}", name="article_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->get('article.manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }



    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->get('article.manager')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Article entity.');
            }

            $this->get('article.manager')->delete($entity);
        }

        return $this->redirect($this->generateUrl('article'));
    }

    /**
     * Creates a form to delete a Article entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

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
    public function getAction($id)
    {
        $article = $this->get('article.manager')->find($id);

        if (!$article instanceof Article) {
            throw new NotFoundHttpException('User not found');
        }

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
        $form = $this->createForm(new ArticleType());
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
        if (isset($entity)) {

            $form = $this->createForm(new ArticleType(), $entity);

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
        $article->delete();
    }

}
