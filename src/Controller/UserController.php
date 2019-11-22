<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\ForumTopicsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/register", name="user_register", methods={"GET","POST"})
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function register(ForumTopicsRepository $repo, User $user = null, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder): Response
    {
        if(!$user)
        {
            $user = new User();
            $user->setIsAuth(false);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            if($user->getIsAuth() === false)
            {
                return $this->redirectToRoute('user_login');
    
            } else {
                return $this->redirectToRoute('user_edit', [
                    'id' => $user->getId()
                ]);
            }
        };

        
        $user_topics = null;

        if($this->getUser() !== null)
        {
            $user_topics = $repo->findBy(
                ['author' => $this->getUser()]
            );
        }
        
        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'user_topics' => $user_topics,
            'edit' => $user->getId()
        ]);
    }

    /**
     * @Route("/login", name="user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('user/login.html.twig', [
            'error' => $error
        ]);
    }

    
    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout()
    {
        
    }
    
    /**
     * @Route("/false/{id}", name="auth_false")
     */
    public function authFalse(ObjectManager $manager, User $user)
    {
        $user->setIsAuth(false);
        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('user_logout');
    }

    /**
     * @Route("/true", name="auth_true")
     */
    public function authTrue(ObjectManager $manager)
    {
        $user = $this->getUser()->setIsAuth(true);
        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('home');
    }
    
    
    /**
     * @Route("/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ObjectManager $manager, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')))
        {
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('home');
    }
}



