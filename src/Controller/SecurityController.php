<?php

namespace App\Controller;

use App\Form\RegistrationType;
//use http\Client;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Client;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

//use Symfony\Component\Security\Core\Encoder
//use Symfony\Component\Security\Core\Encoder\ClientPasswordEncoderInterface;



class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface  $em, UserPasswordEncoderInterface $encoder)
    {
        // 1) build the form
        $client = new Client();
        $form = $this->createForm(RegistrationType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder ->encodePassword($client, $client->getPassword());
            $client->setPassword($hash);
            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('connexion');
        }
        return $this->render('security/registration.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()/*(AuthenticationUtils $authenticationUtils): Response*/
    {
        /*/ retrouver une erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // retrouver le dernier identifiant de connexion utilisé
        $lastUsername = $authenticationUtils->getLastUsername();*/

        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
       /* throw new \Exception('This should never be reached!');*/
    }
}

/*
    /**
     * @Route("/logout", name="app_logout")

    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

   /**
    * @Route("/login", name="app_login")

   public function login(AuthenticationUtils $authenticationUtils): Response
   {
       // if ($this->getUser()) {
       //     return $this->redirectToRoute('target_path');
       // }

       // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername();

       return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
   }*/


