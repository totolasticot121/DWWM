<?php

namespace App\Controller;

use Doctrine\DBAL\Driver\Connection;
use App\Repository\ForumCommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('main/home.html.twig');
    }

    /**
     * @Route("/api/search", name="api_search")
     */
    public function search(Connection $connection, Request $request) : JsonResponse 
    {

        if($content = $request->getContent())
        {
            $parametersAsArray = json_decode($content, true); 

            $name = $parametersAsArray["content"];

            $results = $connection->fetchAll("SELECT title, id FROM forum_topics WHERE title LIKE '%$name%'");

            if($results)
            {
                return new JsonResponse([
                    'result' => true,
                    'name' => $name,
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
