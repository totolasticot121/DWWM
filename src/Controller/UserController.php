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
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $user->setIsAuth(false);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if($file = $form->get('photo')->getData())
            {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('user_photos_folder'), $fileName);
                $user->setPhoto($fileName);
            } else {
                $user->setPhoto('/anonym.jpg');
            }
            
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_login');
        }
              
        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
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
    public function delete(Request $request, ObjectManager $manager, User $user)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')))
        {
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, ForumTopicsRepository $repo, UserPasswordEncoderInterface $encoder)
    {
        $currentPhoto = $user->getPhoto();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if($file = $form->get('photo')->getData())
            {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('user_photos_folder'), $fileName);
                $user->setPhoto($fileName);
            } else {
                $user->setPhoto($currentPhoto);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', [
                'id' => $user->getId()
            ]);
        }

        $user_topics = null;

        if($this->getUser() !== null)
        {
            $user_topics = $repo->findBy(
                ['author' => $this->getUser()]
            );
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'user_topics' => $user_topics,
        ]);
    }
}



