<?php

namespace App\Controller;

use App\Entity\Reservation;

use App\Form\ContactType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use App\Form\ReservationSearchType;
use App\Form\ReservationTypeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface ;
use Symfony\Component\HttpFoundation\File\File;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Form\FormBuilderInterface;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @Route("/ajoutR/", name="ajoutR")
     */
    public function resnew (Request  $request): Response
    {   //Création du formulaire
        $re=new Reservation();
        $form=$this->createForm(ReservationSearchType::class,$re);
        //recupérer les données saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   $em=$this->getDoctrine()->getManager();
            $em->persist($re);
            $em->flush();

        }
        return $this->render('reservation/ajoutRes.html.twig', [
            're' => $form->createView(),
        ]);
    }


    /**
     * @Route("/afficheRadmin", name="afficheRadmin")
     */
    public function afficheEvenement(Request $request): Response
    {   //récuperer le repository pour utiliser la fonction findall
        $Reservation=$this->getDoctrine()->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/afficheResAdmin.html.twig', [
            'r' => $Reservation
        ]);
    }


    /**
     * @Route("/ajoutResAdmin/", name="ajoutResAdmin")
     */
    public function res (Request  $request): Response
    {   //Création du formulaire
        $r=new Reservation();
        $form=$this->createForm(ReservationTypeFormType::class,$r);
        //recupérer les données saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   $em=$this->getDoctrine()->getManager();
            $em->persist($r);
            $em->flush();
            return $this->redirectToRoute('afficheRadmin');
        }
        return $this->render('reservation/addResAdmin.html.twig', [
            'r' => $form->createView(),
        ]);
    }

    /**
     * @Route("/suppRes/{id}", name="suppRes")
     */
    public function suppres($id): Response
    {   //récuperer le classroom à supprimer en utilisant la fonction find($id)
        $Reservation=$this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        //on passe à la supression
        $em->remove($Reservation);
        // pour que la suppression soit faite au niveau de la base de donnée
        $em->flush();
        return $this->redirectToRoute( 'afficheRadmin');
    }

    /**
     * @Route("/modifRes/{id}", name="modifRes")
     */
    public function modifier(Request $request,$id)
    {
//je récupère la classe à modifier
        $Reservation=$this->getDoctrine()
            ->getRepository(Reservation::class)->find($id);
//récupération du formulaire
        $form = $this->createForm(ReservationTypeFormType::class, $Reservation);
//récupérer les données saisies dans les champs
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheRadmin');
        }
        return $this->render("reservation/addResAdmin.html.twig",
            array('r'=>$form->createView()));


    }
    /**
     * @Route("/ResSearch", name="ResSearch")
     */
    public function liseress(Request $request, ReservationRepository $repository)
    {
//All of Student
        $Reservation= $repository->findAll();
//search
        $searchForm = $this->createForm(ReservationSearchType::class);
        $searchForm->add("Recherche",SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $event = $searchForm['event']->getData();
            $resultOfSearch = $repository->searchr($event);
            return $this->render('reservation/SearchRes.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "searchRes" => $searchForm->createView()));
        }
        return $this->render('reservation/SearchRes.html.twig', array(
            "r" => $Reservation,
            "searchReservation" => $searchForm->createView()));
    }


    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(ReservationRepository $ResRepo,EvenementRepository $EvRepo){
        // On va chercher toutes les catégories
        $reservations = $ResRepo->findAll();
        $evenement=$EvRepo->findAll();

        $nbrPersonnes= [];
        $event= [];
        $eventCount= [];
        $color=[];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($reservations as $reservation){
            $nbrPersonnes[] = $reservation->getnbrPersonnes();
            $color[] = $reservation->getColor();
            $event[] = $reservation->getevent()->getNom();
            $eventCount[] = count([$reservation->getEvent()]);
        }

        return $this->render('reservation/stats.html.twig', [
            'nbrPersonnes' => json_encode($nbrPersonnes),
            'color' => json_encode($color),
            'event' => json_encode($event),
            'eventCount' => json_encode($eventCount),


        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request,\Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // Ici nous enverrons l'e-mail
            $message = (new \Swift_Message('Nouveau contact'))
                // On attribue l'expéditeur

                ->setFrom($contact['email'])

                // On attribue le destinataire
                ->setTo('firas.benhadjalouane@gmail.com')

                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'reservation/contact.html.twig', compact('contact')
                    ),
                    'text/html'
                )
            ;
            //On envoie le mail
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.'); // Permet un message flash de renvoi
        }
        return $this->render('reservation/contact.html.twig',['contactForm' => $form->createView()]);
    }


}
