<?php


namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Salle;
use App\Repository\CoursRepository;
use App\Controller\SalleRepository;
use App\Form\CoursType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\isSubmitted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\HttpFoundation;
use Symfony\Component\HttpFoundation\File\File;
use Dompdf\Dompdf ;
use Dompdf\Options;


class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="cours")
     */
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    /**
     * @return Response
     * @Route("/cours/afficher", name="afficheC")
     */
    public function afficheC(CoursRepository $coursRep, Request $request)
    {
        $limitC = 2;
        //num page
        $pageC = (int)$request->query->get("pageC", 1);
        //les salle de la page
        $cours = $coursRep->getPaginatedCours($pageC, $limitC);
        //nb total de salle
        $totalC=$coursRep->getTotalCours();

        return $this->render('Cours/AfficheC.html.twig',compact('cours','totalC', 'limitC','pageC'));



        /*$r=$this->getDoctrine()->getRepository(Cours::class);
        $cours=$r->findAll();
        return $this->render('cours/AfficheC.html.twig',
        ['cours'=>$cours]);*/
    }

    /**
     * @return Response
     * @Route("/cours/list", name="listC", methods={"GET"})
     */
    public function listC(CoursRepository $coursRep, Request $request)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $context= stream_context_create([
            'ssl'=>[
                'verify_peer'=>False,
                'verify_peer_name'=>False,
                'allow_self_signed'=>true
            ]
        ]);
        $dompdf->setHttpContext($context);

        $r=$this->getDoctrine()->getRepository(Cours::class);
        $cours=$r->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('cours/listCours.html.twig',
            ['cours'=>$cours
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
        return new Response();
    }

    /**
     * @return Response
     * @Route("/cours/affichef", name="afficheCF")
     */
    public function afficheCF():Response
    {$r=$this->getDoctrine()->getRepository(Cours::class);
        $s=$this->getDoctrine()->getRepository(Salle::class);
        $cours=$r->findAll();
        $salle=$s->findAll();
        return $this->render('cours/AfficheCF.html.twig',
            ['cours'=>$cours, 'salle'=>$salle]);
    }

    /**
     * @Route("/suppC/{Id}",name="d")
     */
    function Delete($Id, CoursRepository $repository){
        $cours=$repository->find($Id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($cours);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice','Suppression faite avec succés');

        return $this->redirectToRoute('afficheC');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("cours/ajouter", name="ajoutercours")
     */
    function ajoutC(Request $request){
        $cours=new Cours();
        $form=$this->createForm(CoursType::class,$cours);

        //récupérer les données saisies dans les champs
        $form->handleRequest($request);

        //action ajout
        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('ImageC')->getData();
            $fileName=md5(uniqid()).'.'.$uploadedFile->guessExtension();
            try {
                $uploadedFile->move(
                    $this->getParameter('Uploads_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $em = $this->getDoctrine()->getManager();
            $cours->setImageC($fileName);

            $em->persist($cours);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice','Validation faite avec succés');

            //$this->addFlash('message','ajout avec succès');
            //return new Response('ajout avec succes');
            return $this->redirectToRoute('afficheC');
        }
        //Retourner le formulaire a remplir
        return $this->render('cours/AjoutC.html.twig',[
            'f'=>$form->createView()
            ]);
    }

    /**
     * @Route("cours/modifier/{Id}", name="update")
     */
    public function modifierC(CoursRepository $repository,$Id,Request $request)
    {
        //créer le formulaire rempli
        $c = $this->getDoctrine()->getRepository(Cours::class)->find($Id);
        $form=$this->createForm(CoursType::class,$c);

        //récupérer les données saisies dans les champs
        $form->handleRequest($request);
        //action ajout
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('ImageC')->getData();
            $fileName=md5(uniqid()).'.'.$uploadedFile->guessExtension();
            try {
                $uploadedFile->move(
                    $this->getParameter('Uploads_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $em = $this->getDoctrine()->getManager();
            $c->setImageC($fileName);
            $em->persist($c);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice','Modification faite avec succés');
            //return new Response('modifier avec succes');
            return $this->redirectToRoute('afficheC');
        }
        return $this->render('cours/ModifierC.html.twig',
            ['f'=>$form->createView()
            ]);
    }

    /**
     * @Route("/cours/stats", name="statss")
     */
    public function statistiques(CoursRepository $coursRepo){
        //cherhcer cours
        $categories = $coursRepo->findAll();
        $coursNom = [];
        $coursColor = [];
        $coursCount = [];

        foreach($categories as $categorie) {
            $coursNom[] = $categorie->getTypeC();
            $coursColor[] = $categorie->getCouleur();
            $coursCount[] = $categorie->getSerieC();
        }
        $datecounts= $coursRepo->countByDate();
        $dates=[];
        $courscountbydate=[];
        foreach($datecounts as $datecount) {
            $dates[]=$datecount['DateC'];
            $courscountbydate[]=$datecount['count'];
        }


        return $this->render('cours/stats.html.twig', [
            'coursNom' => json_encode($coursNom),
            'coursColor' => json_encode($coursColor),
            'coursCount' => json_encode($coursCount),
            'dates' => json_encode($dates),
            'courscountbydate' => json_encode($courscountbydate),


        ]);
    }

    /**
     * @Route("/cours/statsF", name="statsF")
     */
    public function statistiquesF(CoursRepository $coursRepo){
        //cherhcer cours
        $categories = $coursRepo->findAll();
        $coursNom = [];
        $coursColor = [];
        $coursCount = [];

        foreach($categories as $categorie) {
            $coursNom[] = $categorie->getTypeC();
            $coursColor[] = $categorie->getCouleur();
            $coursCount[] = $categorie->getSerieC();
        }
        $datecounts= $coursRepo->countByDate();
        $dates=[];
        $courscountbydate=[];
        foreach($datecounts as $datecount) {
            $dates[]=$datecount['DateC'];
            $courscountbydate[]=$datecount['count'];
        }


        return $this->render('cours/statsF.html.twig', [
            'coursNom' => json_encode($coursNom),
            'coursColor' => json_encode($coursColor),
            'coursCount' => json_encode($coursCount),
            'dates' => json_encode($dates),
            'courscountbydate' => json_encode($courscountbydate),


        ]);
    }

}
