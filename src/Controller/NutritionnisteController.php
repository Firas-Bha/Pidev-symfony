<?php

namespace App\Controller;

use App\Entity\Nutritionniste;
use App\Form\NutritionnisteType;
use Symfony\Component\Security\Core\Security;
use App\Repository\NutritionnisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NutritionnisteController extends AbstractController
{
    /**
     * @Route("/nutritionniste", name="nutritionniste")
     */
    public function index(Security $security)
    {
        dump($security->getUser());
        return $this->render('nutritionniste/index.html.twig', [
            'controller_name' => 'NutritionnisteController',
        ]);
    }

    /**
     * @param NutritionnisteRepository $repository
     * @return Response
     * @Route ("/AfficheNut",name="AfficheNut")
     */
    public function AfficheNut (NutritionnisteRepository $repository)
    {


        $nutritionniste = $repository->findAll();
        return $this->render('nutritionniste/AfficheNut.html.twig',
            ['Nutritionniste' => $nutritionniste]);
    }

    /**
     * @param $id
     * @param NutritionnisteRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/SupprimeNut/{id}",name="n")
     */
    function DeleteNut ($id, NutritionnisteRepository $repository)
    {
        $nutritionniste = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($nutritionniste);
        $em->flush();
        return $this->redirectToRoute('AfficheNut');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/AddNut",name="Addnut")
     */
    function AddNut (Request $request)
    {
        $nutritionniste= new Nutritionniste();
        $form=$this->createForm(NutritionnisteType::class,$nutritionniste);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($nutritionniste);
            $em->flush();
            return $this->redirectToRoute('AfficheNut');
        }
        return $this->render('nutritionniste/AddNut.html.twig',[
            'f'=>$form->createView()
        ]);

    }

    /**
     * @param NutritionnisteRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route (" /UpdateNut/{id}",name="updatenut")
     */
    function UpdateNut (NutritionnisteRepository $repository,$id, Request $request)
    {
        $nutritionniste=$repository->find($id);
        $form=$this->createForm(NutritionnisteType::class, $nutritionniste);
        $form->add('UpdateNut',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficheNut');
        }
        return $this->render('nutritionniste/UpdateNut.html.twig',
            [
                'f'=>$form->createView()
            ]);
    }

}
