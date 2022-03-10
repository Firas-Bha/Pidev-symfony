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
}