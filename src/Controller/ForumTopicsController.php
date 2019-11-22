<?php

namespace App\Controller;

use Michelf\Markdown;
use App\Entity\ForumTopics;
use App\Entity\ForumComments;
use App\Form\ForumTopicsType;
use App\Form\ForumCommentsType;
use App\Repository\ForumTopicsRepository;
use App\Repository\ForumCategoryRepository;
use Doctrine\DBAL\Driver\Connection;
use App\Repository\ForumCommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/forum")
 */
class ForumTopicsController extends AbstractController
{
    /**
     * @Route("/", name="forum_topics_index")
     */
    public function forumIndex()
    {
        return $this->render('forum_topics/forum_index.html.twig');
    }

    /**
     * @Route("/category/{slug}", name="forum_topics_category")
     */
    public function showCategory($slug, ForumCategoryRepository $repo, Request $request): Response
    {
        $category = $request->get('category');

        $forumCategory = $repo->findOneBy(
            ['title' => $slug]
        );

        return $this->render('forum_topics/category.html.twig', [
            'forumTopics' => $forumCategory->getTopics(),
            'category_title' => $forumCategory->getTitle()
        ]);
    }

    /**
     * @Route("/new", name="forum_topics_new", methods={"GET","POST"})
     * @Route("/{id}/edit", name="forum_topics_edit", methods={"GET","POST"})
     */
    public function addEditTopicForm(ForumTopics $forumTopic = null, Request $request, ObjectManager $manager)
    {
        if(!$forumTopic)
        {
            $forumTopic = new ForumTopics();
        }

        $form = $this->createForm(ForumTopicsType::class, $forumTopic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if(!$forumTopic->getId())
            {
                $forumTopic->setCreatedAt(new \DateTime())
                           ->setAuthor($this->getUser())
                           ->setContent(htmlspecialchars($forumTopic->getContent()));             
            }

            $manager->persist($forumTopic);
            $manager->flush();

            return $this->redirectToRoute('forum_topics_show', [
                'id' => $forumTopic->getId()
            ]);
        }

        return $this->render('forum_topics/new.html.twig', [
            'forum_topic' => $forumTopic,
            'form' => $form->createView(),
            'edit' => $forumTopic->getId() !== null,
        ]);
    }

    /**
     * @Route("/{id}", name="forum_topics_show", methods={"GET","POST"})
     */
    public function showTopic(ForumTopics $forumTopic)
    {                        
        return $this->render('forum_topics/show.html.twig', [
            'forum_topic' => $forumTopic,
        ]);
    }

    /**
     * @Route("/{id}", name="forum_topics_delete", methods={"DELETE"})
     */
    public function deleteTopic(Request $request, ForumTopics $forumTopic, ObjectManager $manager)
    {
        if ($this->isCsrfTokenValid('delete'.$forumTopic->getId(), $request->request->get('_token')))
        {
            $manager->remove($forumTopic);
            $manager->flush();
        }
        
        return $this->redirectToRoute('forum_topics_index');
    }

}
