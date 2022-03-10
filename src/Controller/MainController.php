<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/change~locale~/{locale}", name="change_locale")
     */
    public function changelocale($locale, Request $request)
    {
        //on stocke lang dans session
        $request->getSession()->set('_locale', $locale);

        //on revient sur la page precedente
        return $this->redirect($request->headers->get('referer'));
    }




}
