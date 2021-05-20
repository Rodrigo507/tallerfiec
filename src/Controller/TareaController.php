<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/tarea")
 */
class TareaController extends AbstractController
{
    /**
     * @Route("/", name="tarea_index", methods={"GET"})
     */
    public function index(TareaRepository $tareaRepository): Response
    {
        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tarea_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer, SluggerInterface $slugger): Response
    {
        $tarea = new Tarea();
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {//Envio de formulario
            $userForm = $form->get('userasing')->getData();
            $documento = $form->get('file')->getData();

            if ($documento){
                $nombreArchivo = pathinfo($documento->getClientOriginalName(),PATHINFO_FILENAME);
                $nombreNuevo = $slugger->slug($nombreArchivo);
                $nombreSave = $nombreNuevo.'-'.uniqid().'.'.$documento->guessExtension();

                try {
                    $documento->move(
                        $this->getParameter('document_directory'),
                        $nombreSave
                    );
                }catch (FileException $e){

                }
                $tarea->setFile($nombreSave);
            }

//            dump($userForm);
            if ($userForm) {
                $nombreUser = $userForm->getNombrre();
                $apellidoUser = $userForm->getApellido();
                $destinoUser = $userForm->getEmail();

                $tituloTarea = $form->get('titulo')->getData();
                $detalleTarea = $form->get('descripcion')->getData();
                $prioridadTarea = $form->get('prioridad')->getData();

                $email = (new Email())
                    ->from('proyecto.symfony@gmail.com')
                    ->to($destinoUser)
                    ->subject("Nueva tarea asignada")
                    ->html('
                        <!DOCTYPE html>
                        <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Document</title>
                            </head>
                           
                            <body>
                                <h3>Hola'.$nombreUser.' '.$apellidoUser.' </h3>
                                <p>Tienes una nueva tarea asigada</p>
                            
                                <p>Titulo:'. $tituloTarea.  '</p>
                                <p>Descripcion:'. $detalleTarea . '</p>
                                <p>Tiene una prioridad: '. $prioridadTarea.  '</p>
                            </body>
                            </html>
                    ');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {

                }

            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tarea);
            $entityManager->flush();

            return $this->redirectToRoute('tarea_index');
        }

        return $this->render('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarea_show", methods={"GET"})
     */
    public function show(Tarea $tarea): Response
    {
        return $this->render('tarea/show.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tarea_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tarea $tarea): Response
    {
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tarea_index');
        }

        return $this->render('tarea/edit.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarea_delete", methods={"POST"})
     */
    public function delete(Request $request, Tarea $tarea): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tarea->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tarea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tarea_index');
    }
}
