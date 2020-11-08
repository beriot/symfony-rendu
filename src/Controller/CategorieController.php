<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Service\Utile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;


class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, Utile $utile): Response
    {
        $em = $this->getDoctrine()->getManager();

        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $slug = $utile->generateUniqueSlug($categorie->getNom(), 'Categorie');
            $categorie->setSlug($slug);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée');
        }

        $categories = $em->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'ajout' => $form->createView()
        ]);
    }

    /**
     * @Route("/categorie/{slug}", name="show_categorie")
     */
    public function show(Categorie $categorie = null,  Request $request){
        if($categorie == null){
            $this->addFlash('error', 'Catégorie introuvable');
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie modifiée');
        }


        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'maj' => $form->createView()
        ]);
    }
    /**
     * @Route("/categorie/delete/{id}", name="delete_categorie")
     */
    public function delete(Categorie $categorie = null){
        if($categorie == null){
            $this->addFlash('error', 'Catégorie introuvable');
            return $this->redirectToRoute('categorie');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('success', 'Catégorie supprimée');
        return $this->redirectToRoute('home');
    }


}
