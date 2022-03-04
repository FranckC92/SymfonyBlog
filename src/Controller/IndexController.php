<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Entity\Bulletin;
use App\Form\BulletinType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(ManagerRegistry $managerRegistry): Response
    {
        //Cette méthode permet d'afficher la page d'index et la liste des Bulletins de notre application
        //Corps de la fonction
        //Nous récupérons l'Entity Manager
        $entityManager = $managerRegistry->getManager();
        //Le Repository d'une Entity donné nous permet de récupérer des élements de notre base de données concernant l'Entity en question
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous récupérons la liste des catégories de nos Bulletins
        $categories = $bulletinRepository->findEachCategory();
        //Nous récupérons TOUS les éléments de notre table Bulletin
        $bulletins = $bulletinRepository->findAll(); //Rend un tableau de TOUS les Bulletins persistés
        //On inverse l'ordre des Bulletins pour les publier du plus récent au plus ancien
        $bulletins = array_reverse($bulletins);
        //Retour de la fonction
        return $this->render("index/index.html.twig", [
            'categories' => $categories,
            'bulletins' => $bulletins,
        ]);
    }

    /**
     * @Route("/category/{categoryName}", name="index_category")
     */
    public function indexCategory(string $categoryName, ManagerRegistry $managerRegistry): Response
    {
        //Cette page affiche la liste des bulletins correspondant à la catégorie mentionnée dans l'URL
        //Nous récupérons l'Entity Manager et le Repository concerné
        $entityManager = $managerRegistry->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous récupérons la liste des catégories de nos Bulletins
        $categories = $bulletinRepository->findEachCategory();
        //Nous récupérons la liste des Bulletins dont la catégorie correspond au critère de recherche
        $bulletins = $bulletinRepository->findBy(
            ['category' => $categoryName], 
            ['id' => 'DESC']
        );
        //Si notre tableau de bulletin est vide, nous retournons à l'index
        if(!$bulletins){
            return $this->redirectToRoute('app_index');
        }
        //Si notre recherche a mené à des résultats, nous en publions la liste comme nous l'avons fait dans la méthode index()
        return $this->render('index/index.html.twig', [
            'categories' => $categories,
            'bulletins' => $bulletins,
        ]);
    }

    /**
     * @Route("/cheatsheet", name="index_cheatsheet")
     */
    public function cheatsheet(): Response
    {
        //Cette page affiche un rendu de notre page twig cheatsheet.html.twig
        return $this->render('index/cheatsheet.html.twig', [
            'cheatsheet_variable' => true,
        ]);
    }

    /**
     * @Route("/bulletin/display/{bulletinId}", name="bulletin_display")
     */
    public function displayBulletin(int $bulletinId = 0, ManagerRegistry $managerRegistry): Response
    {
        //Cette méthode a pour objectif d'afficher un seul Bulletin sur notre page, récupéré selon le numéro d'ID indiqué dans l'URL
        //Afin de communiquer avec notre base de données et récupérer l'élement Bulletin pertinent, nous avons besoin de l'Entity Manager et du Repository pertinent
        $entityManager = $managerRegistry->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous recherchons notre Bulletin. Si la recherche n'aboutit pas, nous retournons vers la page d'accueil
        $bulletin = $bulletinRepository->find($bulletinId);
        if(!$bulletin){
            return $this->redirectToRoute('app_index');
        }
        //Une fois que nous avons retrouvé notre bulletin, nous le plaçons en tant qu'entité unique d'un tableau et nous l'affichons sur index.html.twig
        return $this->render('index/index.html.twig', [
            'bulletins' => [$bulletin],
        ]);
    }

    /**
     * @Route("/tag/create", name="tag_create")
     */
    public function createTag(Request $request, ManagerRegistry $managerRegistry): Response
    {
        //Cette méthode a pour objectif de présenter un formulaire de création de Tag et d'envoyer le Tag créé en conséquence vers la base de données
        //Pour communiquer avec notre BDD, nous avons de l'Entity Manager
        $entityManager = $managerRegistry->getManager();
        //Nous avons besoin d'un objet Tag vide à lier au futur formulaire
        $tag = new Tag; //Le Tag reste vide
        //Nous créons le formulaire que nous lions à notre objet Tag
        $tagForm = $this->createForm(TagType::class, $tag);
        //Nous transmettons le contenu du formulaire validé à notre Tag si présent
        $tagForm->handleRequest($request);
        //Si notre Tag est validé, nous l'envoyons vers notre base de données
        if($request->isMethod('post') && $tagForm->isValid()){
            $entityManager->persist($tag);
            $entityManager->flush();
            return $this->redirectToRoute('app_index');
        }
        //Nous transmettons le Tag créé à notre template Twig
        return $this->render('index/dataform.html.twig', [
            'formName' => 'Création de Tag',
            'dataForm' => $tagForm->createView(), //prépare le formulaire à être affiché
        ]);
    }

    /**
     * @Route("/bulletin/create", name="bulletin_create")
     */
    public function createBulletin(Request $request, ManagerRegistry $managerRegistry): Response
    {
        //Cette méthode a pour objectif de présenter un formulaire de création de Bulletin et d'envoyer le Bulletin créé en conséquence vers la base de données
        //Pour communiquer avec notre BDD, nous avons de l'Entity Manager
        $entityManager = $managerRegistry->getManager();
        //Nous avons besoin d'un objet Bulletin vide à lier au futur formulaire
        $bulletin = new Bulletin; //Le Bulletin reste vide
        //Nous créons le formulaire que nous lions à notre objet Bulletin
        $bulletinForm = $this->createForm(BulletinType::class, $bulletin);
        //Nous transmettons le contenu du formulaire validé à notre Bulletin si présent
        $bulletinForm->handleRequest($request);
        //Si notre Bulletin est validé, nous l'envoyons vers notre base de données
        if($request->isMethod('post') && $bulletinForm->isValid()){
            $entityManager->persist($bulletin);
            $entityManager->flush();
            return $this->redirectToRoute('app_index');
        }
        //Nous transmettons le Bulletin créé à notre template Twig
        return $this->render('index/dataform.html.twig', [
            'formName' => 'Création de Bulletin',
            'dataForm' => $bulletinForm->createView(), //prépare le formulaire à être affiché
        ]);
    }

    /**
     * @Route("/bulletin/update/{bulletinId}", name="bulletin_update")
     */
    public function updateBulletin(Request $request, int $bulletinId, ManagerRegistry $managerRegistry): Response
    {
        //Cette méthode a pour objectif de modifier les valeurs d'une Entity Bulletin qui a été persistée dans notre base de données, selon son ID renseigné dans la base de données
        //Nous récupérons l'Entity Manager et le Repository concerné
        $entityManager = $managerRegistry->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous récupérons la liste des catégories de nos Bulletins
        $categories = $bulletinRepository->findEachCategory();
        //Nous récupérons le Bulletin dont la catégorie correspond au critère de recherche
        $bulletin = $bulletinRepository->find($bulletinId);
        //Si notre bulletin n'est pas trouvé, nous retournons à l'index
        if(!$bulletin){
            return $this->redirectToRoute('app_index');
        }
        //Nous créons le formulaire que nous lions à notre objet Bulletin
        $bulletinForm = $this->createForm(BulletinType::class, $bulletin);
        //Nous transmettons le contenu du formulaire validé à notre Bulletin si présent
        $bulletinForm->handleRequest($request);
        //Si notre Bulletin est validé, nous l'envoyons vers notre base de données
        if($request->isMethod('post') && $bulletinForm->isValid()){
            $entityManager->persist($bulletin);
            $entityManager->flush();
            return $this->redirectToRoute('app_index');
        }
        //Nous transmettons le Bulletin créé à notre template Twig
        return $this->render('index/dataform.html.twig', [
            'formName' => 'Modification de Bulletin',
            'dataForm' => $bulletinForm->createView(), //prépare le formulaire à être affiché
        ]);
    }

    /**
     * @Route("/bulletin/delete/{bulletinId}", name="bulletin_delete")
     */
    public function deleteBulletin(int $bulletinId, ManagerRegistry $managerRegistry): Response
    {
        //Cette méthode a pour objectif de supprimer un Bulletin de notre base de données selon l'ID fourni dans l'URL
        //Afin de pouvoir communiquer avec notre base de données et la table Bulletin, nous avons besoin de l'Entity Manager et du Repository Bulletin
        $entityManager = $managerRegistry->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //Nous recherchons le bulletin qui nous intéresse
        $bulletin = $bulletinRepository->find($bulletinId);
        //Si une entrée de la table Bulletin existe avec un ID identique à celui passé en paramètre de notre méthode find(), les informations de l'entrées seront récupérées et passées dans un nouvel objet Bulletin automatiquement instancié, rendu par la méthode. Sinon, la valeur rendu est null
        //Si le bulletin n'est pas trouvé, la variable vaut null et notre méthode n'a plus de raison d'être, nous revenons donc à l'index
        if(!$bulletin){
            return $this->redirectToRoute('app_index');
        }
        //Si le bulletin possède une valeur, nous sommes en possession de l'Entity à supprimer. Nous passons donc une requête à l'Entity Manager
        $entityManager->remove($bulletin);
        $entityManager->flush(); //On applique la requête
        //On revient à l'index
        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/bulletin/generate", name="bulletin_generate")
     */
    public function generateBulletin(ManagerRegistry $managerRegistry): Response
    {
        //Cette méthode a pour objectif de générer un seul bulletin
        //Nous récupérons l'Entity Manager via le ManagerRegistry instancé en paramètre
        $entityManager = $managerRegistry->getManager();
        //Ancienne méthode: $entityManager = $this->getDoctrine()->getManager();
        //Nous créons et renseignons notre objet Bulletin
        $bulletin = new Bulletin;
        $bulletin->setTitle("Bulletin #" . rand(100,999));
        $bulletin->setCategory("General");
        $bulletin->setContent("Sed varius est vel feugiat posuere. Quisque ante ante, pretium id dui eget, tempor aliquam erat. Phasellus ut odio ac orci finibus bibendum. Nullam cursus suscipit massa a eleifend. Suspendisse laoreet nunc nulla, vel tempus augue gravida ac. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tincidunt mauris tristique lorem condimentum vulputate. Vestibulum quis ultrices elit. Quisque finibus sodales mi, quis accumsan enim cursus nec. Vestibulum tristique magna mi, id ultricies massa sollicitudin et.");
        //Nous envoyons notre Bulletin vers la base de données:
        $entityManager->persist($bulletin); //Demande de persistance de l'objet Bulletin
        $entityManager->flush(); //Applique TOUTES les demandes déposées depuis le dernier flush
        //Nous repartons vers notre page d'index
        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/square-color/{sqColor}", name="square_color")
     */
    public function squareColor(string $sqColor = ""): Response
    {
        //Cette route a pour objectif de nous présenter un <div> carré dont la couleur change selon la valeur entrée dans notre paramètre de route

        //Afin de pouvoir déterminer la valeur de background-color selon notre paramètre de route, nous allons utiliser une structure de contrôle de type switch
        //La fonction strtolower transforme la chaine de caractères passée en argument et la met en minuscules, ce qui nous permet d'avoir un résultat insensible à la casse
        switch(strtolower($sqColor)){
            case "rouge":
                $divColor = "red"; //Si sqColor a la valeur "rouge", nous plaçons le code CSS correspondant dans une nouvelle variable $divColor (ici, "red")
                break;
            case "bleu":
                $divColor = "blue";
                break;
            case "vert":
                $divColor = "green";
                break;
            case "jaune":
                $divColor = "yellow";
                break;
            case "": //Si $sqColor est laissé vide, nous avons notre carré gris
                $divColor = "gray";
                break;
            default: //Si aucun des cas couverts ne correspond à la valeur de sqColor, nous initialisons $divColor avec la valeur "black", pour un div noir
                $divColor = "black";
        }

        //Rendu du carré
        return new Response("<div style='height:300px; width:300px; background-color:". $divColor ."'></div>");
    }

    /**
     * @Route("/square/{squareValue}", name="index_square")
     */
    public function indexSquare(string $squareValue = "Vide"): Response
    {
        //Cette méthode affiche un div carré rouge, mais prend aussi en compte un paramètre de route lequel est affiché au sein du carré
        //La Valeur par défaut de notre paramètre de route est "Vide", ce qui signifie que si nous ne le renseignons pas dans l'adresse, ce sera la valeur par défaut de $squareValue
        //Retour de la fonction
        return new Response("<div style='width:250px; height:250px; background-color:red'>" .$squareValue . "</div>");
    }
}
