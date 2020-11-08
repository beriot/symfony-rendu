<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Form\CourType;
use App\Service\Utile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CourController extends AbstractController
{
    /**
     * @Route("/cour", name="cour")
     */
    public function index(Request $request, Utile $utile): Response
    {
        $em = $this->getDoctrine()->getManager();

        $cour = new Cour();
        $form = $this->createForm(CourType::class, $cour);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $slug = $utile->generateUniqueSlug($cour->getTitre(), 'Cour');
            $cour->setSlug($slug);

            $em->persist($cour);
            $em->flush();

            $this->addFlash('success', 'Cour ajouté');
        }

        $cours = $em->getRepository(Cour::class)->findAll();

        return $this->render('cour/index.html.twig', [
            'cours' => $cours,
            'ajout' => $form->createView()
        ]);
    }
    /**
     * @Route("/cour/{slug}", name="show_cour")
     */
    public function show(Cour $cour = null,  Request $request){
        if($cour == null){
            $this->addFlash('error', 'Cour introuvable');
            return $this->redirectToRoute('cour');
        }
        $form = $this->createForm(CourType::class, $cour);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($cour);
            $em->flush();

            $this->addFlash('success', 'Cour modifié');
        }

        return $this->render('cour/show.html.twig', [
            'cour'=> $cour,
            'maj' => $form->createView()
        ]);
    }

    /**
     * @Route("/cour/delete/{id}", name="delete_cour")
     */
    public function delete(Cour $cour = null){
        if($cour == null){
            $this->addFlash('error', 'Cour introuvable');
            return $this->redirectToRoute('cour');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($cour);
        $em->flush();

        $this->addFlash('success', 'Cour supprimé');
        return $this->redirectToRoute('cour');
    }
}
