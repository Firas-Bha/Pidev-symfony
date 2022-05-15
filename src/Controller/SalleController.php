<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SearchCSType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SalleRepository;
use App\Controller\CoursRepository;
use App\Form\SalleType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\isSubmitted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse ;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



use Symfony\Component\Validator\Constraints\Json;


class SalleController extends AbstractController
{
    /**
     * @Route("/salle", name="salle")
     *
     */
    public function index():Response
    {
        return $this->render('salle/index.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }

    /**
     *
     * @Route("/salle/afficher", name="afficheS",methods={"GET"})
     */
    public function afficheS(SalleRepository $salleRep, Request $request)
    {
        $limit = 2;
        //num page
        $page = (int)$request->query->get("page", 1);
        //les salle de la page
        $salle = $salleRep->getPaginatedSalles($page, $limit);
        //nb total de salle
        $total=$salleRep->getTotalSalle();

        return $this->render('Salle/AfficheS.html.twig',compact('salle','total', 'limit','page'));


        /* $r=$this->getDoctrine()->getRepository(Salle::class);
        $salle=$r->findAll();
        return $this->render('salle/AfficheS.html.twig',
            ['salle'=>$salle]);*/
    }
    /**
     * @return Response
     * @Route("/salle/affichef", name="afficheSS")
     */
    public function afficheSF():Response
    {$r=$this->getDoctrine()->getRepository(Salle::class);
        $salle=$r->findAll();
        return $this->render('salle/AfficheSF.html.twig',
            ['salle'=>$salle]);
    }

    /**
     * @Route("/suppS/{Id}",name="sup")
     */
    function Delete($Id, SalleRepository $repository){
        $salle=$this->getDoctrine()->getRepository(Salle::class)->find($Id);
        $em=$this->getDoctrine()->getManager();

        $em->remove($salle);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice','Suppresion faite avec succés');
        return $this->redirectToRoute('afficheS');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("salle/ajouter", name="AjoutSalle")
     */
    function ajoutC(Request $request){
        $salle=new Salle();
        $form=$this->createForm(SalleType::class,$salle);

        //récupérer les données saisies dans les champs
        $form->handleRequest($request);

        //action ajout
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salle);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice','Ajout fait avec succés');
            //return new Response('ajout avec succes');
            return $this->redirectToRoute('afficheS');
        }
        //Retourner le formulaire a remplir
        return $this->render('salle/AjoutS.html.twig',[
            'fo'=>$form->createView()
        ]);
    }

    /**
     * @Route("salle/modifier/{Id}", name="mod")
     */
    public function modifierS(SalleRepository $repository,$Id,Request $request)
    {
        //créer le formulaire rempli
        $c = $repository->find($Id);
        $form=$this->createForm(SalleType::class,$c);

        //récupérer les données saisies dans les champs
        $form->handleRequest($request);
        //action ajout
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice','Modification faite avec succés');
            //return new Response('modifier avec succes');
            return $this->redirectToRoute('afficheS');
        }
        return $this->render('salle/ModifierS.html.twig',
            ['fo'=>$form->createView()
            ]);
    }



    /**
     * @Route("salle/recherche", name="recherche")
     */
    public function recherche(Request $request, SalleRepository $repository)
    {
        $data= $request->get('search');
        $salle=$repository->findBy(['NomS'=>$data]);


        return $this->render('salle/search.html.twig', [
            'salle'=>$salle]);

    }

    /**
     *
     * @Route("get", name="get")
     * @Method("GET")
     */
    public function allSalleAction(SerializerInterface $serializer, SalleRepository $repository)
    {
        //For avoiding Collection issues of ManyToOne || OneToMany || ManyToMany
        //relationship between 2 entities
        return $this->json(
            json_decode(
                $serializer->serialize(
                    $repository->findAll(),
                    'json',
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['cours']]
                ),
                JSON_OBJECT_AS_ARRAY
            )
        );
    }

        //$Salle = $this->getDoctrine()->getManager()->getRepository(Salle::class)->findAll();
        //$serializer = new Serializer([new ObjectNormalizer()],[AbstractNormalizer::INGNORED_ATTRIBUTES =>['cours']]);
        //$formatted = $serializer->normalize($Salle);

        //return new JsonResponse($formatted);



    /**
     * @Route("/addjson", name="addjson")
     */

    public function addjson(Request $request)
    {
        $sal = new Salle();
        //req
        $Id = $request->query->get("Id");
        $Surface = $request->query->get("Surface");
        $NomS = $request->query->get("NomS");
        $CapaciteS = $request->query->get("CapaciteS");
        $nbCoursMaxS= $request->query->get("nbCoursMaxS");
        //$cours = $request->query->get("cours");
        $description = $request->query->get("description");

        //set
        $sal->setId($Id);
        $sal->setSurface($Surface);
        $sal->setNomS($NomS);
        $sal->setCapaciteS($CapaciteS);
        $sal->setNbCoursMaxS($nbCoursMaxS);
        $sal->setDescription($description);

        $em = $this->getDoctrine()->getManager();
        $em->persist($sal);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("Salle ajoutée avec succes.");
        return new JsonResponse($formatted);

    }


    /**
     * @Route("/updatejson", name="updatejson")
     */
    public function updatejson(Request $request) {
        //req
        $Id = $request->get("Id");
        $sal=$this->getDoctrine()->getManager()->getRepository(Salle::class)->find($Id);
        $Surface = $request->query->get("Surface");
        $NomS = $request->query->get("NomS");
        $CapaciteS = $request->query->get("CapaciteS");
        $nbCoursMaxS = $request->query->get("nbCoursMaxS");
        $description= $request->query->get("description");

        //set
        $sal->setId($Id);
        $sal->setSurface($Surface);
        $sal->setNomS($NomS);
        $sal->setCapaciteS($CapaciteS);
        $sal->setNbCoursMaxS($nbCoursMaxS);
        $sal->setDescription($description);

        $em = $this->getDoctrine()->getManager();
        $em->persist($sal);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($sal);
        return new JsonResponse(" modifiee avec succes.");

    }
    /**
     * @Route("/deletejson", name="deletejson")
     */

    public function deletejson(Request $request) {

        $id = $request->get("Id");
        $em = $this->getDoctrine()->getManager();
        $sal = $em->getRepository(Salle::class)->find($id);
        if($sal!=null ) {
            $em->remove($sal);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("supprimé avec succes.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id invalide.");


    }

    /**
     * @Route("/detailjson", name="detailjson")
     */

    public function detailjson(Request $request) {

        $Id = $request->get("Id");
        $em = $this->getDoctrine()->getManager();
        $sal = $em->getRepository(Salle::class)->find($Id);
        $encoder= new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object->getDescription();
        });

    }
}