<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategorieType;


class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }



    /**
     * @Route("/afficheCA", name="afficheCA")
     */
    public function afficheC(): Response
    {
        //rÃ©cupÃ©rer le repository pour utiliser la fonction findAll
        $r=$this->getDoctrine()->getRepository(Categorie::class);
        //faire appel Ã  la fonction findAll()
        $categories=$r->findAll();

        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render('categorie/afficheC.html.twig', [
            'c' => $categories,
        ]);
    }

    /**
     * @Route("/suppCat/{id}", name="suppC")
     */
    public function supp($id): Response

    {
        //récupérer le classroom à supprimer
        $categories=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        //on passe à la suppression
        $em=$this->getDoctrine()->getManager();
        $em->remove($categories);
        $em->flush();

        return $this->redirectToRoute('afficheCA');
    }

    /**
     * @Route("/ajoutC", name="ajoutC")
     */
    public function ajoutC(Request $request,FlashyNotifier $flashy): Response
    {
        //crÃ©ation du formulaire

        $c= new Categorie();
        $form = $this->createForm(CategorieType::class, $c);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
            $flashy->success('categorie created!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('afficheCA');
        }


        return $this->render('categorie/ajoutC.html.twig', [
            'f' => $form->createView(),
        ]);

    }



    /**
     * @Route("/modifC/{id}", name="modifC")
     */
    public function modifP(Request $request,$id): Response
    {
        //rÃ©cupÃ©rer le classroom Ã  modifier
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        //crÃ©ation du formulaire rempli
        $form=$this->createForm(CategorieType::class,$categorie);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('afficheCA');
        }

        return $this->render('categorie/ajoutC.html.twig', [
            'f' => $form->createView(),
        ]);
    }

}
