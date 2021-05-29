<?php

namespace App\Controller;

use App\Entity\Recomendacion;
use App\Entity\Tarea;
use App\Form\RecomendacionType;
use Dompdf\Dompdf;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $tareas = $this->getDoctrine()->getRepository(Tarea::class)
            ->findByUserCurrent($user);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'FIEC',
            'tareas' => $tareas
        ]);
    }

    /**
     * @Route("/detalle/{idTarea}", name="detalle",defaults={"idTarea":0})
     */
    public function detalle($idTarea): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $tarea = $this->getDoctrine()->getRepository(Tarea::class)
            ->findOneBy(array('id' => $idTarea, 'userasing' => $user));
        return $this->render('home/detalle.html.twig', compact("tarea"));
    }


    /**
     * @Route("/terminadas", name="terminadas")
     */
    public function terminadas(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $tareas = $this->getDoctrine()->getRepository(Tarea::class)
            ->findBy(array('userasing' => $user, 'estado' => false));
        return $this->render('home/terminadas.html.twig', compact("tareas"));
    }

    /**
     * @Route ("/cambio-estado", name="cambioestado",options = { "expose" = true })
     */
    public function cambioEstado(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $id = $request->get('id');
            $tarea = $em->getRepository(Tarea::class)->find($id);
            $tarea->setEstado(false);
            $em->flush();

            return new JsonResponse(['id' => $id]);
        }
    }

    /**
     * @Route ("/recomendaciones", name="recomendaciones")
     */
    public function recomendaciones(Request $request): Response
    {
        $recomendacionesForm = new Recomendacion();
        $form = $this->createForm(RecomendacionType::class, $recomendacionesForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recomendacionesForm);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('home/recomendiacones.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route ("/download",name="download")
     */
    public function download()
    {


        $em = $this->getDoctrine()->getRepository(Tarea::class);
        $tareas = $em->tareasSinFinalizar();

        //Creacion del PDF
        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);

        //Creacion de pagina
        $pdf->AddPage();



        $encabezado = "\n\n
            Informe de tareas
        ";

        //Escritura del PDF
        $pdf->SetFont('helveticaB', 'B', 14);//Family - style- zsize
        $pdf->Write(0, $encabezado, '', 0, 'C', true, 0, false, false, 0);
        $pdf->Ln(10);

        $pdf->SetFont('times', 'B', 12);//Family - style- zsize PARA LOS ENCABEZADO
        $pdf->SetFillColor(220, 255, 220);//Color de la celda PARA LOS ENCABEZADO

        $pdf->Cell(5, 5, '', 0, 0, 'C', false);
        $pdf->Cell(55, 5, 'Titulo', 1, 0, 'C', true);
        $pdf->Cell(70, 5, 'Descripcion', 1, 0, 'C', true);
        $pdf->Cell(20, 5, 'Prioridad', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Nombre', 1, 1, 'C', true);

        foreach ($tareas as $value) {
            $pdf->Cell(5, 5, '', 0, 0, 'C', false);
            $pdf->Cell(55, 5, substr($value['titulo'],0,20), 1, 0, 'C', false);
            $pdf->Cell(70, 5, substr($value['descripcion'],0,40), 1, 0, 'C', false);
            $pdf->Cell(20, 5, $value['prioridad'], 1, 0, 'C', false);
            $pdf->Cell(25, 5, $value['nombrre'], 1, 1, 'C', false);
        }

        //Salida del pdf
        $pdf->Output('example_001.pdf', 'I');
    }


}
