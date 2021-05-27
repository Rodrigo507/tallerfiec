<?php

namespace App\Controller;

use App\Entity\Recomendacion;
use App\Entity\Tarea;
use App\Form\RecomendacionType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $tareas =$this->getDoctrine()->getRepository(Tarea::class)
        ->findByUserCurrent($user);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'FIEC',
            'tareas'=>$tareas
        ]);
    }

    /**
     * @Route("/detalle/{idTarea}", name="detalle",defaults={"idTarea":0})
     */
    public function detalle($idTarea): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $tarea =$this->getDoctrine()->getRepository(Tarea::class)
        ->findOneBy(array('id'=>$idTarea,'userasing'=>$user));
        return $this->render('home/detalle.html.twig',compact("tarea"));
    }


    /**
     * @Route("/terminadas", name="terminadas")
     */
    public function terminadas(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $tareas =$this->getDoctrine()->getRepository(Tarea::class)
            ->findBy(array('userasing'=>$user,'estado'=>false));
        return $this->render('home/terminadas.html.twig',compact("tareas"));
    }

    /**
     * @Route ("/cambio-estado", name="cambioestado",options = { "expose" = true })
     */
    public function cambioEstado(Request $request){
        if ($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $id = $request->get('id');
            $tarea =$em->getRepository(Tarea::class)->find($id);
            $tarea->setEstado(false);
            $em->flush();

            return new JsonResponse(['id'=>$id]);
        }
    }
    /**
     * @Route ("/recomendaciones", name="recomendaciones")
     */
    public function recomendaciones(Request $request):Response{
        $recomendacionesForm = new Recomendacion();
        $form = $this->createForm(RecomendacionType::class,$recomendacionesForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($recomendacionesForm);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('home/recomendiacones.html.twig',[
            'form'=>$form->createView()
        ]);

    }




}
