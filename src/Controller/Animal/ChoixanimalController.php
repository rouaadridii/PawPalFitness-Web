<?php

namespace App\Controller\Animal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AnimalRepository;
use App\Repository\FavorisRepository;
use App\Repository\CategorieRepository;
use App\Form\AnimalsType;
use App\Form\ModifType;
use App\Form\CategorieanimalType;
use App\Entity\Animal;
use App\Entity\Favoris;
use App\Entity\Categorie;
use Twilio\Rest\Client;


class ChoixanimalController extends AbstractController
{
    #[Route('/choisir', name: 'app_choixanimal')]
    public function index(): Response
    {
        return $this->render('Animal/choixanimal.html.twig');
    }

    #[Route('/afficher', name: 'app_afficher')]
    public function app_afficher(AnimalRepository $repository, FavorisRepository $f, CategorieRepository $c): Response
    {
    $animal = $repository->findAll();
    $fav = $f->findAll();
    $categories = $c->findAll();

    return $this->render('Animal/choixanimal.html.twig', [
        'animal' => $animal,
        'fav' => $fav,
        'categories' => $categories]);
    }

    #[Route('/formAjout', name: 'app_affformajout',methods: ['GET','POST'])]
    public function app_affformajout(Request $request,ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $animal = new Animal();
        $form = $this->createForm(AnimalsType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $existingAnimal = $em->getRepository(Animal::class)->findOneBy(['nom' => $animal->getNom()]);

           if ($existingAnimal) {
            $this->addFlash('error', 'Ce nom d\'animal existe déjà');
            return $this->redirectToRoute('app_affformajout');}

           $em->persist($animal);
           $em->flush();
           $this->addFlash('success', 'L\'animal a été ajouté avec succès.');
           
           $phoneNumber = $request->request->get('phoneNumber');

           //Twilio credentials
           $sid = 'AC7e1d12e81f7a2723888b8032e75dc0a6';
           $token = '24634f6972ab11fbe1011470312dac5e';

           //Twilio client
           $twilio = new Client($sid, $token);
           $message = $twilio->messages->create(
               $phoneNumber,
               [
                   'from' => '+13343397782', //virtual num
                   'body' => 'You added a pet to your pawpalfitness account'
               ]
           );
           return $this->redirectToRoute('app_afficher');
        }
        
        return $this->renderForm("Animal/ajouteranimal.html.twig", ["form" => $form->createView()]);
        
    }

    #[Route('/favoris/{nom}', name: 'animal_favoris')]
    public function animal_favoris(ManagerRegistry $doctrine,AnimalRepository $repository, Request $request,$nom): Response
    {
        $em=$doctrine->getManager();
        $animal = $repository->findOneBy(['nom' => $nom]);
        $fav = new Favoris();
        $favRepository = $em->getRepository(Favoris::class);
        $existingFavoris = $favRepository->findOneBy(['ida' => $animal]);

        if ($existingFavoris) {
            $em->remove($existingFavoris);
            $em->flush();
        } else {
            $fav = new Favoris();
            $fav->setIda($animal);
            $fav->setNoma($animal->getNom());
            $em->persist($fav);
            $em->flush();
        }
        return $this->redirectToRoute('app_afficher');
    }

    #[Route('/details/{nom}', name: 'app_details')]
    public function app_details($nom,AnimalRepository $repository,Request $request,ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $animal = $repository->findOneBy(['nom' => $nom]);
        $animaux = new Animal();
        $form = $this->createForm(ModifType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->render('Animal/detailsanimal.html.twig',["form" => $form,'nom'=>$nom,'animal'=>$animal]);
        }
        
        return $this->renderForm('Animal/detailsanimal.html.twig',["form" => $form,'nom'=>$nom,'animal'=>$animal]);
        
    }

    #[Route('/delete/{nom}', name: 'animal_del')]
    public function animal_del(ManagerRegistry $doctrine, AnimalRepository $repository, Request $request,$nom): Response
    {
        $em=$doctrine->getManager();
        $animal = $repository->findOneBy(['nom' => $nom]);
        $em->remove($animal);
        $em->flush();
        return $this->redirectToRoute('app_afficher');
    }

    #[Route('/categories', name: 'app_affichercategories')]
    public function app_affichercategories(CategorieRepository $repository, AnimalRepository $animalRepository, EntityManagerInterface $entityManager): Response
    {
        $categories = $repository->findAll();

        // Fetch the count of animals for each category
        $animalCounts = [];
        foreach ($categories as $category) {
            $animalCount = $animalRepository->countAnimalsByCategoryId($category->getIdc());
            $animalCounts[$category->getIdc()] = $animalCount;
        }

        return $this->render('backAnimal/showcategories.html.twig', [
            'categories' => $categories,
            'animalCounts' => $animalCounts,
        ]);
    }

    #[Route('/Ajoutcateg', name: 'app_ajoutcateg',methods: ['GET','POST'])]
    public function app_ajoutcateg(Request $request,ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $categorie = new Categorie();
        $form = $this->createForm(CategorieanimalType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $existingcategorie = $em->getRepository(Categorie::class)->findOneBy(['nomc' => $categorie->getNomc()]);

           if ($existingcategorie) {
            $this->addFlash('error', 'Cette catégorie d\'animal existe déjà');
            return $this->redirectToRoute('app_ajoutcateg');}

           $em->persist($categorie);
           $em->flush();
           $this->addFlash('success', 'La catégorie a été ajouté avec succès.');
           return $this->redirectToRoute('app_affichercategories');
        }
        
        return $this->renderForm("backAnimal/ajoutercategorie.html.twig", ["form" => $form->createView()]);
        
    }

    #[Route('/deletec/{nomc}', name: 'categorie_del')]
    public function categorie_del(ManagerRegistry $doctrine, CategorieRepository $repository, Request $request, $nomc): Response
    {
        $em=$doctrine->getManager();
        $categories = $repository->findOneBy(['nomc' => $nomc]);
        $em->remove($categories);
        $em->flush();
        return $this->redirectToRoute('app_affichercategories');
    }
   
    #[Route('/search', name: 'animal_search')]
    public function search(Request $request, ManagerRegistry $doctrine): Response
    {
        $searchTerm = $request->query->get('search_term');
        $entityManager = $doctrine->getManager();
        $animalRepository = $entityManager->getRepository(Animal::class);
        if (!$searchTerm) {
            $this->addFlash('error', 'Write The pet s name that you want to search');
            return $this->redirectToRoute('app_afficher');
        }
        // Call the custom search method in the repository
        $animal = $animalRepository->searchByName($searchTerm);
        if (!$animal) {
            $this->addFlash('error', 'The searched animal doesnt exsist');
            return $this->redirectToRoute('app_afficher');
        }
        return $this->render('Animal/search.html.twig', ['searchTerm' => $searchTerm,'animals' => $animal ? [$animal] : [],]);
}

#[Route('/animalstats', name: 'generateGraph')]
public function generateGraph(AnimalRepository $animalRepository): Response
{
    $data = $animalRepository->countAnimalsByCategories();

    // Prepare the data for the chart
    $chartData = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'Nombre d\'animaux par catégorie',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
                'data' => [],
            ],
        ],
    ];

    // Populate chartData with values from the query result
    foreach ($data as $row) {
        $chartData['labels'][] = $row['categoryName'];
        $chartData['datasets'][0]['data'][] = $row['count'];
    }

    return $this->render('backAnimal/stats.html.twig', [
        'data' => $chartData,
    ]);
}
    

}
