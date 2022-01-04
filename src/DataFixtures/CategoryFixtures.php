<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;



/* 

Les fixtures sont un jeu de données.
Elles servent à remplir la BDD juste après la 
création de la BDD, pour pouvoir manipuler des
données dans mon code => des entités

*/

class CategoryFixtures extends Fixture
{
private SluggerInterface $slugger;

public function __construct(SluggerInterface $slugger) {
        
$this->slugger = $slugger;
}

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Ancêtres ou vétérans (de 1885 à 1900)', 
            'Edwardians ou Édouardiennes (de 1901 à 1917)', 
            'Vintage (de 1918 à 1929)',
            'Avant-guerre (de 1930 à 1942)', 
            'Après-guerre (de 1945 à 1974)', 
            'Youngtimers (à partir de 1975)'
        ];
        
      
        foreach($categories as $category) { 
  
        $cat = new Category();

        $cat->setName($category);
        $cat->setAlias($this->slugger->slug($category));


        $manager->persist($cat);

       }

        $manager->flush();

    }
}

