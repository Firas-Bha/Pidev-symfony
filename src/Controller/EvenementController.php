<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface ;
use Symfony\Component\HttpFoundation\File\File;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;






class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
    /**
     * @Route("/EvSalle", name="EvSalle")
     */
    public function EvSalle(Request $request, PaginatorInterface $paginator){
        $repository=$this->getDoctrine()->getRepository(Evenement::class);
        $Evenement=$repository->findAll();
        $EvenementT=$paginator->paginate(
            $Evenement, //on passe les données
            $request->query->getInt('page', 1), //num de la page en cours, 1 par défaut
            4 //nbre d'articles par page
        );
        return $this->render('evenement/AfficheEFront.html.twig',
            ['ev'=>$EvenementT]);
    }

    /**
     * @Route("/afficheE", name="afficheE",methods={"GET"})
     */
    public function afficheEvenement(Request $request): Response
    {   //récuperer le repository pour utiliser la fonction findall
        $Evenement=$this->getDoctrine()->getRepository(Evenement::class)->findAll();



        return $this->render('evenement/afficheE.html.twig', [
            'e' => $Evenement
        ]);
    }
    /**
     * @Route("/afficheCalendar", name="afficheCalendar",methods={"GET"})
     */
    public function afficheCalendar(Request $request): Response
    {$events =$this->getDoctrine()->getRepository(Calendar::class)->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
                'evenement'=> $event->getEvenement()
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('Evenement/afficheCalendar.html.twig', compact('data'));

    }

    /**
     * @Route("/ajoutE", name="ajoutE")
     * @Method("POST")
     */
    public function ajoutE(Request  $request): Response
    {   //Création du formulaire
        $e=new Evenement();
        $form=$this->createForm(EvenementType::class,$e);
        //recupérer les données saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   $em=$this->getDoctrine()->getManager();
            $uploadedFile = $form['Image']->getData();
            $fileName=md5(uniqid()).'.'.$uploadedFile->guessExtension();
            try {
                $uploadedFile->move(
                    $this->getParameter('Uploads_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $e->setImage($fileName);
            $em->persist($e);
            $em->flush();


            return $this->redirectToRoute('afficheE');

        }
        return $this->render('evenement/ajoutE.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supp/{id}", name="suppE")
     */
    public function supp($id): Response
    {   //récuperer le classroom à supprimer en utilisant la fonction find($id)
        $Evenement=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        //on passe à la supression
        $em->remove($Evenement);
        // pour que la suppression soit faite au niveau de la base de donnée
        $em->flush();
        return $this->redirectToRoute( 'afficheE');
    }

    /**
     * @Route("/modifE/{id}", name="modifE")
     */
    public function modifE(Request $request,$id)
    {
//je récupère la classe à modifier
        $Evenement=$this->getDoctrine()
            ->getRepository(Evenement::class)->find($id);
//récupération du formulaire
        $form = $this->createForm(EvenementType::class, $Evenement);
//récupérer les données saisies dans les champs
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheE');
        }
        return $this->render("evenement/ajoutE.html.twig",
            array('f'=>$form->createView()));


    }

    /**
     * @Route("/Calendar/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());


        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)


        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$calendar){
                // On instancie un rendez-vous
                $calendar = new Calendar;

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStart(new \DateTime($donnees->start));
            if($donnees->allDay){
                $calendar->setEnd(new \DateTime($donnees->start));
            }else{
                $calendar->setEnd(new \DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $calendar->setBorderColor($donnees->borderColor);
            $calendar->setTextColor($donnees->textColor);


            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }


        return $this->render('evenement/afficheCalendar.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }


    /**
     * @Route("/pdf", name="pdf", methods={"GET"})
     */
    public function pdf()
    {
        $Evenement=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('evenement/pdf.html.twig', [
            'events' => $Evenement,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     *
     *
     * @Route("/search", name="ajax_search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities = $this->getDoctrine()->getRepository(Evenement::class)->findEntitiesByString($requestString);

        if(!$entities) {
            //$result['entities']['error'] = "reservation not found";
            return new JsonResponse(["message" => 'Aucun evenement existant!']);
        } else {
            $serializer = new Serializer([new ObjectNormalizer()]);
            $test = $serializer->normalize($entities, 'json', ['attributes' => ['id', 'Nom', 'Capacite', 'Date', 'Description', 'Image', 'Adresse']]);
            return new JsonResponse($test);
            //$result['entities'] = $this->getRealEntities($entities);
        }

    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getNom();
        }

        return $realEntities;
    }

    /**
     * @Route("/afficheETriCapacite", name="afficheETriCapacite",methods={"GET"})
     */
    public function afficheEvenementTri(Request $request): Response
    {   //récuperer le repository pour utiliser la fonction findall
        $Evenement=$this->getDoctrine()->getRepository(Evenement::class)->OrderByCapaciteDQL();

        return $this->render('evenement/afficheE.html.twig', [
            'e' => $Evenement
        ]);
    }
    /**
     * @Route("/afficheETriNom", name="afficheETriNom",methods={"GET"})
     */
    public function afficheEvenementTriNom(Request $request): Response
    {$Evenement=$this->getDoctrine()->getRepository(Evenement::class)->OrderByNomDQL();
        return $this->render('evenement/afficheE.html.twig', [
            'e' => $Evenement
        ]);
    }
    /**
     * @Route("/afficheETriDate", name="afficheETriDate",methods={"GET"})
     */
    public function afficheEvenementTriDate(Request $request): Response
    {
        $Evenement=$this->getDoctrine()->getRepository(Evenement::class)->OrderByDateDQL();

        return $this->render('evenement/afficheE.html.twig', [
            'e' => $Evenement
        ]);
    }
    /**
     * @Route("/Paginator", name="/Paginator")
     */
    public function Tri(Request $request,PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $donnees = $this->getDoctrine()->getRepository(Evenement::class)->findBy([],['Nom' => 'desc']);

        $Evenement = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            4
        );

        return $this->render('evenement/afficheEFront.html.twig',
            array('ev' => $Evenement));

    }





    //Partie JSON/////////////////////////////////////////

    /**
     * @Route("/ajoutEJSON", name="ajoutEJSON")
     * @Method("POST")
     */
    public function ajoutEJSON(Request  $request): Response
    {   //Création du formulaire
        $e=new Evenement();
        $form=$this->createForm(EvenementType::class,$e);
        //recupérer les données saisies depuis la requete http
        $form->handleRequest($request);
        $nom = $request->query->get("Nom");
        $capacite = $request->query->get("Capacite");
        $description = $request->query->get("Description");
        $Image = $request->query->get("Image");
        $Adresse = $request->query->get("Adresse");
        $date = new \DateTime('now');

         $em=$this->getDoctrine()->getManager();
        $e->setNom($nom);
        $e->setCapacite(2200);
        $e->setDate($date);
        $e->setDescription($description);
        $e->setImage($Image);
        $e->setAdresse($Adresse);

            $em->persist($e);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($e);
            return new JsonResponse($formatted);
    }

    /**
     * 
     * @Route("/displayEvenementJSON", name="displayEvenementJSON")
     * @Method("GET")
     */
    public function allEVAction()
    {

        $Evenement = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Evenement);

        return new JsonResponse($formatted);

    }

    /**
     * @Route("/deleteEvenement", name="delete_evenement")
     * @Method("DELETE")
     */

    public function deleteEvenementAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id);
        if($evenement!=null ) {
            $em->remove($evenement);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Evenement a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id Evenement invalide.");


    }


    /******************Modifier Evenement*****************************************/
    /**
     * @Route("/updateEvenement", name="update_evenement")
     * @Method("PUT")
     */
    public function modifierEvenementAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $evenement = $this->getDoctrine()->getManager()
            ->getRepository(Evenement::class)
            ->find($request->get("id"));

        $evenement->setNom($request->get("nom"));
        $evenement->setDescription($request->get("description"));
        $evenement->setCapacite($request->get("capacite"));

        $em->persist($evenement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse("Evenement a ete modifiee avec success.");

    }


}
