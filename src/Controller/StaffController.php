<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffType;
use App\Repository\StaffRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StaffController extends AbstractController
{
    /**
     * @Route("/staff", name="staff")
     */
    public function index()/*(Security $security)*/
    {

        return $this->render('staff/index.html.twig', [
            'controller_name' => 'StaffController',
        ]);
    }

    /**
     * @param StaffRepository $repository
     * @return Response
     * @Route ("/AfficheStaff",name="affichestaff")
     */
    public function AfficheStaff (StaffRepository $repository)
    {
        $staff = $repository->findAll();
        return $this->render('staff/AfficheStaff.html.twig',
            ['Staff' => $staff]);
    }

    /**
     * @param $id
     * @param StaffRepository $repository
     * @return RedirectResponse
     * @Route ("/SupprimeStaff/{id}",name="s")
     */
    function DeleteStaff ($id, StaffRepository $repository)
    {
        $staff = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($staff);
        $em->flush();
        return $this->redirectToRoute('affichestaff');
    }

    /**
     * @param StaffRepository $repository
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("staff/Updatestaff/{id}",name="updatestaff")
     */
    public function Updatestaff (StaffRepository $repository,$id,Request $request)
    {
        $staff=$repository->find($id);
        $form=$this->createForm(StaffType::class,$staff);
        $form->add('updatestaff',SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affichestaff');

        }
        return $this->render('staff/Updatestaff.html.twig',[
            'for'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/Addstaff",name="Addstaff")
     */
    function AddStaff (Request $request)
    {
        $staff= new Staff();
        $form=$this->createForm(StaffType::class , $staff);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($staff);
            $em->flush();
           return $this->redirectToRoute('affichestaff');
           // return $this->redirectToRoute('AfficheClient');
        }
        return $this->render('staff/AddStaff.html.twig',[
            'for'=>$form->createView()
        ]);

    }

    /**
     * @param StaffRepository $StaffRepository
     * @return Response
     * @Route("/listestaff",name="staff_list", methods={"GET"})
     */
    function liststaff(StaffRepository $StaffRepository):Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $staff = $StaffRepository->findAll();



        // Retrieve the HTML generated in our twig file
        $html=$this->render('staff/listestaff.html.twig',
            ['Staff' => $staff]);


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
        return new Response("The PDF file has been succesfully generated !");

    }
}
