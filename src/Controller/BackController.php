<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Cour;
use App\Form\CategorieType;
use App\Form\CourType;
use App\Repository\CategorieRepository;
use App\Repository\CourRepository;
use App\Repository\UserRepository;
use App\Service\Utile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BackController extends AbstractController
    {
    /**
    * @Route("/back", name="back")
    */
    public function index(Request $request, Utile $utile, CategorieRepository $categorieRepository, CourRepository $courRepository, UserRepository $userRepository): Response
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

        $cour = new Cour();
        $formcour = $this->createForm(CourType::class, $cour);
        $formcour->handleRequest($request);
        if($formcour->isSubmitted() && $formcour->isValid()){

            $slug = $utile->generateUniqueSlug($cour->getTitre(), 'Cour');
            $cour->setSlug($slug);

            $em->persist($cour);
            $em->flush();

            $this->addFlash('success', 'Cour ajouté');
        }

        $categories = $em->getRepository(Categorie::class)->findAll();
        $cours = $em->getRepository(Cour::class)->findAll();


        $count1 = $categorieRepository->findAll();
        $count2 = $courRepository->findAll();
        $count3 = $userRepository->findAll();


        $statCategories = count($count1);
        $statCours = count($count2);
        $statUsers = count($count3);


    return $this->render('back/index.html.twig', [
        'categories' => $categories,
        'ajout' => $form->createView(),
        'cours' => $cours,
        'ajoutcour' => $formcour->createView(),
        'statcours' => $statCours,
        'statcategories' => $statCategories,
        'users' => $statUsers
    ]);


    }

}