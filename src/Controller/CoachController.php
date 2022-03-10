<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Form\CoachType;
use App\Repository\CoachRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


class CoachController extends AbstractController
{
    /**
     * @Route("/coach", name="coach")
     */
    public function index()/*(Security $security)*/
    {
       /* dump($security->getUser());*/
        return $this->render('coach/index.html.twig', [
            'controller_name' => 'CoachController',
        ]);
    }
    /**
     * @param CoachRepository $repository
     * @return Response
     * @Route("/AfficheCoach",name="AfficheCoach")
     */
    public function Affiche(CoachRepository $repository)
    {
        // $repo=$this->getDoctrine()->getRepository(Client::class);

        $coach = $repository->findAll();
        return $this->render('coach/AfficheCoach.html.twig',
            ['Coach' => $coach]);
    }
    /**
     * @Route("/Supprime/{id}",name="c")
     */
    function Delete($id, CoachRepository $repository)
    {
        $Coach = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($Coach);
        $em->flush();
        return $this->redirectToRoute('AfficheCoach');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/Addcoach",name="Addcoach")
     */
     function AddCoach (Request $request)
    {
        $coach= new Coach();
        $form=$this->createForm(CoachType::class , $coach);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
          $em=$this->getDoctrine()->getManager();
          $em->persist($coach);
          $em->flush();
          return $this->redirectToRoute('AfficheCoach');
        }
        return $this->render('coach/Addcoach.html.twig',[
            'f'=>$form->createView()
        ]);

    }

    /**
     * @Route (" /UpdateCoach/{id}",name="updatecoach")
     */
   public function Updatecoach (CoachRepository $repository,$id,Request $request)
   {
      $coach=$repository->find($id);
      $form=$this->createForm(CoachType::class,$coach);
      $form->add('Updatecoach',SubmitType::class);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
          $em=$this->getDoctrine()->getManager();
          $em->flush();
          return $this->redirectToRoute("AfficheCoach");

      }
      return $this->render('coach/Updatecoach.html.twig',[
          'fo'=>$form->createView()
      ]);
   }

    /**
     *
     * @Route("/listeCoach",name="Coach_list", methods={"GET"})
     * @return Response
     * @param $Coachrepository
     */
    function listCoach(CoachRepository $CoachRepository):Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $Coach=$CoachRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html=$this->render('Coach/listecoach.html.twig',
            ['Coach' => $Coach]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);


    }
}
