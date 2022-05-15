<?php


namespace App\Controller;

use App\Entity\Panniers;
use App\Form\ContactType;
use App\Form\PanniersType;
use App\Repository\PanniersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse ;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;

class PanniersController extends AbstractController
{
    /**
     * @Route("/panniers", name="panniers")
     */
    public function index(): Response
    {
        return $this->render('panniers/index.html.twig', [
            'controller_name' => 'PanniersController',
        ]);
    }

    /**
     * @return Response
     * @Route("/Affp",name="affichee")
     */
    public  function AfficheP(Request  $request,PaginatorInterface $paginator )
    {
        $repository=$this->getDoctrine()->getRepository(Panniers::class);
        $pannier= $repository->findAll();
        $pannier=$paginator->paginate(
        $pannier,
        $request->query->getInt('page',1) ,
        2
);
        return $this->render('base-back/panniers/AfficheP.html.twig' , ['pannier' => $pannier]);
    }

    /**
     * @return Response
     * @Route("/Afficp" , name="Afficp")
     */
    public function Affic(Request  $request,PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Panniers::class);
        $pannier = $repository->findAll();
        $pannier=$paginator->paginate(
            $pannier,
            $request->query->getInt('page',1) ,
            2
        );
        return $this->render('base-front/panniers/AffichePS.html.twig', ['pannier' => $pannier]);
    }

    /**
     * @param $id
     * @param PanniersRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delP/{id}",name="dd")
     */
    function Supprimer($id,PanniersRepository $repository){
        $pannier=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($pannier);
        $em->flush();
        return $this->redirectToRoute('affichee');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/AddP",name="adde")
     */
    function addPannier(Request $request)
    {
        $pannier = new Panniers();
        $form = $this->createForm(PanniersType::class, $pannier);
        /*$form->add('Ajouter' ,SubmitType::class);*/
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pannier);
            $em->flush();
            return $this->redirectToRoute('affichee');
        }
        return $this->render('base-front/panniers/AjoutP.html.twig',
            array('f' => $form->createView()));

    }

    /**
     * @param PanniersRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("panniers/UpdateP/{id}",name="updateP")
     */
    function UpdateP(PanniersRepository $repository,$id,Request $request){
        $pannier=$repository->find($id);
        $form=$this->createForm(PanniersType::class,$pannier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affichee');
        }
        return $this->render('base-front/panniers/UpdateP.html.twig',
            array('f'=>$form->createView()));

    }

    /**
     * @Route("/rec", name="rec")
     */
    public function Reclamation(Request $request , \Swift_Mailer $mailer): Response
    { $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // On crée le message
            $message = (new \Swift_Message('Nouveau contact'))
                // On attribue l'expéditeur
                ->setFrom($contact['email'])
                // On attribue le destinataire
                ->setTo('xgym549@gmail.com')
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'base-front/panniers/hey.html.twig', compact('contact')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.'); // Permet un message flash de renvoi
            return $this->redirectToRoute('Afficp');

        }
        return $this->render('base-front/panniers/contact.html.twig',['contactForm' => $form->createView()]);
    }


//json
    /**
     * @Route("getp",name="getp")
     * @Method("GET")
     */

    public function getp(PanniersRepository $repository,SerializerInterface $serializerinterface) {
        //For avoiding Collection issues of ManyToOne || OneToMany || ManyToMany
        //relationship between 2 entities
        return $this->json(
            json_decode(
                $serializerinterface->serialize(
                    $repository->findAll(),
                    'json',
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['commandes']]
                ),
                JSON_OBJECT_AS_ARRAY
            )
        );

       // $pannier = $repository->findAll();
        //$json=$serializerinterface->serialize($pannier,'json',['groups'=>'post:hey']);
        // dump($commande);
        // die;
        //return new Response($json);
    }
    /**
     * @Route("addpjson", name="addpjson")
     */

    public function addpjson(Request $request)
    {
        $pannier = new Panniers();
        //req
        $nom = $request->query->get("nom");
        $etat = $request->query->get("etat");
        $categorie = $request->query->get("categorie");
        $dateexp = new \DateTime('now');
        //set
        $pannier->setNom($nom);
        $pannier->setEtat($etat);
        $pannier->setCategorie($categorie);
        $pannier->setDateexp($dateexp);
        $em = $this->getDoctrine()->getManager();
        $em->persist($pannier);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("panniers ajoute avec succes.");
        return new JsonResponse($formatted);

    }
    /**
     * @Route("/updatepjson", name="updatepjson")
     */
    public function updatepjson(Request $request) {
        //req
        //
        $id = $request->get("id");
        $pannier=$this->getDoctrine()->getManager()->getRepository(Panniers::class)->find($id);

        //req
        $nom = $request->query->get("nom");
        $etat = $request->query->get("etat");
        $categorie = $request->query->get("categorie");
        $dateexp = new \DateTime('now');
        //set
        $pannier->setNom($nom);
        $pannier->setEtat($etat);
        $pannier->setCategorie($categorie);
        $pannier->setDateexp($dateexp);
        $em = $this->getDoctrine()->getManager();
        $em->persist($pannier);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pannier);
        return new JsonResponse("Panniers modifiee avec success.");

    }

    /**
     * @Route("/deletepjson", name="deletepjson")
     */

    public function deletepjson(Request $request) {

        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $pannier = $em->getRepository(Panniers::class)->find($id);
        if($pannier!=null ) {
            $em->remove($pannier);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("pannier supprime avec succes.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id programme invalide.");


    }

}
