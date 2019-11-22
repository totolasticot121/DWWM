<?php

namespace App\Controller;

use App\Entity\ForumTopics;
use Doctrine\DBAL\Driver\Connection;
use App\Repository\ForumCommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    
    /**
     * @Route("/", name="home")
     */
    public function home(Connection $connection, ObjectManager $manager)
    {
        $date = new \DateTime('-10 day');
        $stringDate = $date->format('Y-m-d H:i:s');

        $queryBuilder = $manager->createQueryBuilder();
        $queryBuilder->select('t')
                     ->from(ForumTopics::class, 't')
                     ->where('t.createdAt > :date')
                     ->orderBy('t.createdAt', 'ASC')
                     ->setParameter('date', $stringDate);

        $newTopics= $queryBuilder->getQuery()
                                 ->getResult();

        return $this->render('main/home.html.twig', array('new_topics' => $newTopics));
    }

    /**
     * @Route("/api/search", name="api_search")
     */
    public function search(Connection $connection, Request $request) : JsonResponse 
    {

        if($content = $request->getContent())
        {
            $array = json_decode($content, true); 
            $name = $array["content"];

            $results = $connection->fetchAll("SELECT title, id FROM forum_topics WHERE title LIKE '%$name%'");

            if($results)
            {
                return new JsonResponse([
                    'result' => true,
                    'results' => json_encode($results)
                    ]);
            } else {
                return new JsonResponse([
                    'result' => false,
                ]);
            }
        }
    }
}
