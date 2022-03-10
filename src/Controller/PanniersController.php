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



}
