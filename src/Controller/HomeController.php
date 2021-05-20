<?php

namespace App\Controller;

use App\Entity\Tarea;
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
        $user = $this->getUser();
        dump($user);
        $tareas =$this->getDoctrine()->getRepository(Tarea::class)
        ->findByUserCurrent($user);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'FIEC',
            'tareas'=>$tareas
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
