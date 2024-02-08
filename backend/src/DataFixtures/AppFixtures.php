<?php
namespace App\DataFixtures;

use App\Entity\Adherent;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Auteur;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Entity\Reservations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class AppFixtures extends Fixture
{

    private $faker;


      

    public function load(ObjectManager $manager): void
    {
        $categoriesfr = [
            "Science-fiction",
            "Roman",
            "Thriller",
            "Fantasy",
            "Mystère",
            "Aventure",
            "Historique",
            "Biographie",
            "Science",
            "Poésie",
            "Humour",
            "Philosophie",
            "Auto-assistance",
            "Cuisine",
            "Art",
            "Musique",
            "Drame",
            "Jeunesse",
            "Science-fiction",
            "Horreur",
            "Suspense",
        ];

        $this->faker = Factory::create();

        $categories = [];
        $auteurs = [];
        $livres = new ArrayCollection();
        $adherents = [];
          
        //Create categories
        foreach ($categoriesfr as $cat ) {
            $categorie = new Categorie();
            $categorie
                ->setNom($cat)
                ->setDescription($cat ." description");
            $categories[] = $categorie;
            $manager->persist($categorie);
        }

        for ($i = 0; $i < 10; $i++) {
            $auteur = new Auteur();
            $auteur
                ->setNom($this->faker->lastName)
                ->setPrenom($this->faker->firstName)
                ->setDateNaissance(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-80 years', '-18 years')))
                ->setDateDeces(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-80 years', '-18 years')))
                ->setNationalite($this->faker->country)
                ->setDescription($this->faker->sentence())
                ->setPhoto($this->faker->imageUrl( 200, 200, 'people'));
            $auteurs[] = $auteur;
            $manager->persist($auteur);
        }

        

        for ($i = 0; $i < 200; $i++) {
            $livre = new Livre();
            
            $livre
                ->setTitre($this->faker->sentence())
                ->setDateSortie(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-80 years', '-18 years')))
                ->setLangue($this->faker->languageCode)
                ->setPhotoCouverture($this->faker->imageUrl( 200, 200, 'nature'))
                
                ->addAuteur($this->faker->randomElement($auteurs));

            //add at random max 3 categories
            $nbCategories = $this->faker->numberBetween(1, 3);
            for ($j = 0; $j < $nbCategories; $j++) {
                $categorie = $this->faker->randomElement($categories);
                $livre->addCategory($categorie);
                $categorie->addLivre($livre);
                $manager->persist($categorie);
            }
            
            $manager->persist($livre);
            $livres->add($livre);
        }

        for ($i = 0; $i < 10; $i++) {
            $adherent = new Adherent();
            $adherent
                ->setDateAdhesion(\DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear()))
                ->setNom($this->faker->lastName)
                ->setPrenom($this->faker->firstName)
                ->setDateNaissance(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-80 years', '-18 years')))
                ->setEmail($this->faker->email)
                ->setAdressePostale($this->faker->address)
                ->setNumTel($this->faker->phoneNumber)
                ->setPhoto($this->faker->imageUrl());

            
            $adherents[] = $adherent;
            $manager->persist($adherent);
        }

        //creer des utilisateurs par défaut
        $defaultuser1 = new Utilisateur();
        $defaultuser1
            ->setEmail('admin@example.com') //TODO
            ->setPassword('$2y$13$jNflVNn0M8bDtspdhkCpxOxqtl8QIOlye0/HBBtA3SzHH66dhCG0O')
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($defaultuser1);

        #$manager->flush();
        #return;
        //for adherent add Emprunts and reservations of books
        //for each adherent in adherents

        foreach ($adherents as $adherent) {
            //add random emprunts

            $nbEmprunts = $this->faker->numberBetween(0, 3);
            for ($i = 0; $i < $nbEmprunts; $i++) {
                $livre = $this->faker->randomElement($livres);
                //remove livre from livres
                $livres->removeElement($livre);
                
                //break if no more livre
                if (count($livres) == 0) {
                    break;
                }
                $emprunt = new Emprunt();
                // Generate random dates
                $dateEmprunt = \DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear());
                $dateRetour = \DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear());

                // Ensure the order of dates during random generation
                if ($dateEmprunt > $dateRetour) {
                    $dateRetour = null;
                }
                $emprunt
                    ->setDateEmprunt($dateEmprunt)
                    ->setDateRetour($dateRetour)
                    ->setAdherent($adherent)
                    ->setLivre($livre);
                $manager->persist($emprunt);
            }
            $nbReservations = $this->faker->numberBetween(0, 3);
            for ($i = 0; $i < $nbReservations; $i++) {
                $livre = $this->faker->randomElement($livres);
                //remove livre from livres
                $livres->removeElement($livre);
                //break if no more livre
                if (count($livres) == 0) {
                    break;
                }
                $reservation = new Reservations();
                $reservation
                    ->setDateResa(\DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear()))
                    ->setAdherent($adherent)
                    ->setLivre($livre);
                $manager->persist($reservation);
            }
        }

        



        $manager->flush();
        return;
    }
}
