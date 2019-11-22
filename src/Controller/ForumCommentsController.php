<?php

namespace App\Controller;


use App\Entity\ForumTopics;
use App\Entity\ForumComments;
use App\Form\ForumCommentsType;
use App\Repository\ForumTopicsRepository;
use App\Repository\ForumCommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/forum/comment")
 */
class ForumCommentsController extends AbstractController
{
    /**
     * @Route("/new", name="new_comments_api", methods={"POST"})
     */
    public function newComment(Request $request, ForumTopicsRepository $repo, ObjectManager $manager): JsonResponse
    {
        if($data = $request->getContent())
        {
            $jsonArray = json_decode($data, true); 
            $content = $jsonArray["content"];
            $article = $repo->findOneBy(array('id' => $jsonArray["article_id"]));
            
            if($content)
            {
                $comment = new ForumComments();
                $comment->setForumTopic($article)
                        ->setCreatedAt(new \DateTime())
                        ->setContent(htmlspecialchars($content))
                        ->setAuthor($this->getUser());            
                
                $manager->persist($comment);
                $manager->flush();
                
                return new JsonResponse([
                    'status' => '200',
                    'message' => 'commentaire ajouté'
                ], 200);
            }

        } else {
            
            return new JsonResponse([
                'status' => '400',
                'message' => 'erreur'
            ], 400);
        }
    }

    /**
     * @Route("/{id}", name="get_comments_api", methods={"POST"})
     */
    public function getComments($id, ForumCommentsRepository $repo, Request $request): JsonResponse
    {
        $comments = $repo->findBy(['forumTopic' => $id]);

        if($comments !== null)
        {
            $data = $this->render('forum_comments/show.html.twig', array('comments' => $comments));
            $html = $data->getContent();

            return new JsonResponse([
                'status' => '200',
                'template' => $html,
            ], 200);
        } else {
            return new JsonResponse([
                'status' => '400',
                'error' => 'no comments found'
            ], 400);
        }
    }

    /**
     * @Route("/delete/{id}", name="delete_comment_api")
     */
    public function deleteComments(ForumComments $forumComment, Request $request, ObjectManager $manager): JsonResponse
    {
        if($data = $request->getContent())
        {
            $jsonArray = json_decode($data, true); 
            $token = $jsonArray["token"];

            if ($this->isCsrfTokenValid('delete'.$forumComment->getId(), $token ))
            {
                $manager->remove($forumComment);
                $manager->flush();
                
                return new JsonResponse([
                    'status' => '200',
                    'message' => 'commentaire supprimé'
                ], 200);
            }
            
        }
            
        return new JsonResponse([
            'status' => '400',
            'message' => 'erreur'
        ], 400);
    }
    
}
