<?php

namespace IIM\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use IIM\BlogBundle\Entity\Article;
use IIM\BlogBundle\Form\ArticleType;
use IIM\BlogBundle\Form\SearchType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

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
        $browser = $this->get('buzz');
        $response = $browser->get('http://localhost/SymfonyProject1/web/api/articles.json');

        if($response->isSuccessful()){
            $articles = json_decode($response->getContent());
        } else {
            throw new \Exception ('API failure');
        }

        return ['entities' => $articles];
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
                $fields = $request->request->all();
                unset($fields['article']['_token']);
                unset($fields['article']['submit']);

                $browser = $this->get('buzz');
                $response = $browser->submit('http://localhost/SymfonyProject1/web/api/articles.json',  $fields);

                if($response->isSuccessful()){
                    $article = json_decode($response->getContent(),true);
                } else {
                    throw new \Exception ('API failure : '.$response->getContent());
                }
                return $this->redirect($this->generateUrl('article_show', array('id' => $article['id'])));
            }
        }
        return array(
            'form'   => $form->createView()
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="article_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $browser = $this->get('buzz');
        $response = $browser->get('http://localhost/SymfonyProject1/web/api/articles/'.$id.'.json');

        if($response->isSuccessful()){
            $article = json_decode($response->getContent());
        } else {
            throw new \Exception ('API failure');
        }

        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $article,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="article_edit")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->get('article.manager')->find($id);

        $form = $this->createForm('article', $entity, array(
            'action' => $this->generateUrl('article_edit',array('id' => $entity->getId())),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'edit'));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $fields = $request->request->all();
                unset($fields['article']['_token']);
                unset($fields['article']['submit']);

                $browser = $this->get('buzz');
                $response = $browser->submit('http://localhost/SymfonyProject1/web/api/articles/'.$id.'.json',  $fields, 'PUT');
                if($response->isSuccessful()){
                    $article = json_decode($response->getContent(), true);
                } else {
                    throw new \Exception ('API failure : '.$response->getContent());
                }
                return $this->redirect($this->generateUrl('article_show', array('id' => $article['id'])));
            }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $browser = $this->get('buzz');
        $response = $browser->delete('http://localhost/SymfonyProject1/web/api/articles/'.$id.'.json');

        return $this->redirect($this->generateUrl('article'));

    }

    /**
     * search a new Article entity.
     *
     * @Route("/search", name="article_search")
     * @Template("IIMBlogBundle:Article:search.html.twig")
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(new SearchType(), null, array(
            'action' => $this->generateUrl('article_search'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Search'));


        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $fields = $request->request->all();
                unset($fields['search']['_token']);
                unset($fields['search']['submit']);
                $browser = $this->get('buzz');
                $response = $browser->submit('http://localhost/SymfonyProject1/web/api/articles.json',  $fields, 'GET');

                if($response->isSuccessful()){
                    $articles = json_decode($response->getContent(),true);
                } else {
                    throw new \Exception ('API failure : '.$response->getContent());
                }
                return array(
                    'articles' => $articles,
                    'search_form' => $form->createView(),
                    'search' => $fields['search']['content'],
                );
            }
        }

        return array(
            'search_form' => $form->createView(),
        );
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

}
