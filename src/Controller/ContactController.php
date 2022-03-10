<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        $contact = $form ->getData();
        //ici nous enverrons le mail
           $message= (new \Swift_Message('nouveau Contact'))
               //on attribue l'expéditeur
            ->setFrom($contact['email'])
               //on attribue le destinataire
            ->setTo('votre@adresse.fr')
               // on crée le message avec  vue twig
            ->setBody(
                $this->renderView(
                    'emails/contact.html.twig', compact('contact')
                ),
                   'text/html'
               );
           //on envoie le message
            $mailer->send($message);
            $this->addFlash('message','le message a bien été envoyé');
            //return $this->redirectToRoute('acceuil');

        }
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
