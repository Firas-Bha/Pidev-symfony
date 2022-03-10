<?php

namespace App\Controller;
use App\Entity\Commandes;
use App\Form\CommandesType;
use App\Form\ContactType;
use App\Repository\CommandesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;


class CommandesController extends AbstractController
{
    /**
     * @Route("/commandes", name="commandes")
     */
    public function index(): Response
    {
        return $this->render('commandes/index.html.twig', [
            'controller_name' => 'CommandesController',
        ]);
    }

    /**
     * @return Response
     * @Route("/Affiche" , name="affiche")
     */
    public function AfficheCmd(Request  $request,PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Commandes::class);
        $commande = $repository->findAll();
        $commande=$paginator->paginate(
            $commande,
            $request->query->getInt('page',1) ,
            2
        );
        return $this->render('base-back/commandes/Affiche.html.twig', ['commande' => $commande]);
    }

    /**
     * @return Response
     * @Route("/AfficheC" , name="AfficheC")
     */
    public function AfficheC(Request  $request,PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Commandes::class);
        $commande = $repository->findAll();
        $commande = $repository->findAll();
        $commande=$paginator->paginate(
            $commande,
            $request->query->getInt('page',1) ,
            2
        );
        return $this->render('base-back/commandes/AfficheC.html.twig', ['commande' => $commande]);
    }

    /**
     * @return Response
     * @Route("/listee" , name="liste")
     */
    public function ListeCmd(CommandesRepository $repository)

    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commande = $repository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('base-front/commandes/listc.html.twig', ['commande' => $commande ]);

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

    /**
     * @param $id
     * @param CommandesRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/del/{id}",name="de")
     */
    function Delete($id, CommandesRepository $repository)
    {
        $commande = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commande);
        $em->flush();
        return $this->redirectToRoute('affiche');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/AddC",name="add")
     */
    function addcommande(Request $request)
    {
        $commande = new Commandes();
        $form = $this->createForm(CommandesType::class, $commande);
        /*$form->add('Ajouter' ,SubmitType::class);*/
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('base-front/commandes/Ajout.html.twig',
            array('f' => $form->createView()));
    }

    /**
     * @Route("command/Update/{id}",name="updatee")
     */
    function Update(CommandesRepository $repository,$id,Request $request)
    {
        $commande = $repository->find($id);
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affiche');

        }
        return $this->render('base-front/commandes/Update.html.twig',
            array('f' => $form->createView()));

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
                        'base-front/commandes/hey.html.twig', compact('contact')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.'); // Permet un message flash de renvoi
             return $this->redirectToRoute('rec');

        }
        return $this->render('base-front/commandes/contact.html.twig',['contactForm' => $form->createView()]);
    }

}
