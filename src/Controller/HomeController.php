<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'FIEC',
        ]);
    }

    /**
     * @Route("/detalle/{nombre}", name="detalle",defaults={"nombre":"Rodrigo"})
     */
    public function detalle($nombre): Response
    {
        return $this->render('home/detalle.html.twig',compact("nombre"));
    }

}
