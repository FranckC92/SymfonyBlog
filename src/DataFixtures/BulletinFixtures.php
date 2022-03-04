<?php

namespace App\DataFixtures;

use App\Entity\Bulletin;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BulletinFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Nous créons un tableau "$categories" possédant la liste des catégories possibles
        $categories = ["Général", "Divers", "Urgent"];

        $contents = [
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras at elit quis nulla ullamcorper sollicitudin. Vestibulum vitae nisi magna. Aliquam diam arcu, efficitur at sodales non, volutpat sed est.",
            "Maecenas congue purus placerat, suscipit nisl id, mollis leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aenean sit amet lorem eu felis auctor imperdiet.",
            "Proin ultrices lectus ut nunc venenatis porta. Aenean sodales mattis libero id volutpat. Aenean justo arcu, cursus sit amet tellus ac, dapibus cursus tellus. Aliquam ultricies suscipit auctor.",
            "Pellentesque mollis luctus augue ut auctor. Maecenas erat odio, viverra et tempor sit amet, fermentum id elit. Donec in elit maximus, maximus libero sed, interdum lorem. Curabitur a congue nunc, eu consectetur augue. Nunc mattis ex non ex egestas consequat. Integer diam velit, suscipit in aliquet vestibulum, tristique ut neque.",
            "Ut a tortor a nisl malesuada aliquet a non enim. Curabitur hendrerit enim at dolor viverra, vitae aliquam mi tristique. Aliquam et mi nisl. Aliquam vel varius nibh. Curabitur gravida leo vel fermentum sodales. Nulla pretium tristique pharetra. Sed sollicitudin metus in velit volutpat malesuada. Etiam felis nisl, gravida in rutrum sed, suscipit eget tellus.",
            "Proin quis feugiat augue. Cras suscipit leo ac volutpat rhoncus. Maecenas mollis ligula nec nunc cursus, nec placerat lorem mollis. Duis at massa scelerisque, tincidunt metus eget, rhoncus nisi. Maecenas quis interdum dui, a imperdiet quam. Pellentesque bibendum sem eget risus faucibus.",
            "Phasellus ex ipsum, convallis vitae ante in, aliquam ultrices massa. Maecenas non blandit turpis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi eget eros dapibus, commodo orci id, pellentesque nibh. ",
            " Vestibulum rutrum lorem fringilla laoreet iaculis. Pellentesque tincidunt finibus quam in mattis. Curabitur eros turpis, iaculis id neque sed, bibendum pretium felis. Praesent feugiat quam felis, non aliquam quam laoreet quis. Etiam sit amet condimentum ante. Maecenas ac suscipit neque. Donec non ornare diam, a lacinia lorem.",
        ];
        
        //Nous créons une boucle afin de pouvoir générer autant de bulletins que désiré:
        for($i=0; $i<30; $i++){
            //Nous créons et renseignons notre Bulletin
            $bulletin = new Bulletin;
            $bulletin->setTitle("Bulletin #0" . rand(100,999));
            //Sélection au hasard d'une entrée du tableau categories entre l'index 0 et l'index équivalent à la taille totale du tableau - 1 (étant donné que l'indexation de notre tableau commence à partir de 0)
            $bulletin->setCategory($categories[rand(0, (count($categories) - 1))]);
            $bulletin->setContent($contents[0] . $contents[rand(1, (count($contents) - 1))] . $contents[rand(1, (count($contents) - 1))] . $contents[rand(1, (count($contents) - 1))]);
            //Demande de persistance pour cette itération de la boucle
            $manager->persist($bulletin);
        }
        
        //La méthode flush() du Manager applique toutes les demandes au sein de notre boucle
        $manager->flush();
        //On applique cette fonction load() grâce à la commande
        //  php bin/console doctrine:fixtures:load (yes)
    }
}
