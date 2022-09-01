<?php

namespace App\DataFixtures;

use App\Entity\Artiste;
use App\Entity\Departement;
use App\Entity\Festival;
use App\Entity\Instrument;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;


    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create();
        $departements = [];

        $charente = new Departement();
        $charente->setNom('Charente');
        $charente->setNum('16');
        $manager->persist($charente);
        array_push($departements, $charente);

        $charenteMaritime = new Departement();
        $charenteMaritime->setNom('Charente-Maritime');
        $charenteMaritime->setNum('17');
        $manager->persist($charenteMaritime);
        array_push($departements, $charenteMaritime);

        $correze = new Departement();
        $correze->setNom('Corrèze');
        $correze->setNum('19');
        $manager->persist($correze);
        array_push($departements, $correze);



        $deuxSevres = new Departement();
        $deuxSevres->setNom('Deux-Sèvres');
        $deuxSevres->setNum('79');
        $manager->persist($deuxSevres);
        array_push($departements, $deuxSevres);


        $gironde = new Departement();
        $gironde->setNom('Gironde');
        $gironde->setNum('33');
        $manager->persist($gironde);
        array_push($departements, $gironde);

        $vienne = new Departement();
        $vienne->setNom('Vienne');
        $vienne->setNum('86');
        $manager->persist($vienne);
        array_push($departements, $vienne);

        $creuse = new Departement();
        $creuse->setNom('Creuse');
        $creuse->setNum('23');
        $manager->persist($creuse);
        array_push($departements, $creuse);

        $dordogne = new Departement();
        $dordogne->setNom('Dordogne');
        $dordogne->setNum('24');
        $manager->persist($dordogne);
        array_push($departements, $dordogne);

        $landes = new Departement();
        $landes->setNom('Landes');
        $landes->setNum('40');
        $manager->persist($landes);
        array_push($departements, $landes);

        $lot = new Departement();
        $lot->setNom('Lot-et-Garonne');
        $lot->setNum('47');
        $manager->persist($lot);
        array_push($departements, $lot);

        $pyr = new Departement();
        $pyr->setNom('Pyrénées-Atlantiques');
        $pyr->setNum('64');
        $manager->persist($pyr);
        array_push($departements, $pyr);

        $hautvienne = new Departement();
        $hautvienne->setNom('Haute-Vienne');
        $hautvienne->setNum('87');
        $manager->persist($hautvienne);
        array_push($departements, $hautvienne);

        $artistes = [];

        for ($i = 0; $i < 50; $i++) {
            $artiste = new Artiste();
            $artiste->setNomScene($faker->lastName());
            $artiste->setStyle($faker->word());
            $instrument = new Instrument();
            $instrument->setNom($faker->word());
            $instrument->setArtiste($artiste);
            $artiste->addInstrument($instrument);
            array_push($artistes, $artiste);
            $manager->persist($instrument);
            $manager->persist($artiste);
        }

        for ($i = 0; $i < 50; $i++) {
            $festival = new Festival();
            $festival->setNom($faker->name());
            $festival->setAffiche('placeholder.png');
            $festival->setLieu($faker->city());
            $festival->setDate($faker->dateTimeBetween('now','+1 years'));
            $festival->setPostedTime($faker->dateTimeBetween('-1 years','now'));
            $festival->setDepartement($faker->randomElement($departements));
            for ($j=0;$j<$faker->numberBetween(10,20);$j++){
                $festival->addArtiste($faker->randomElement($artistes));
            }
            $manager->persist($festival);
        }



        $user = new User();
        $user->setLastname($faker->lastName());
        $user->setFirstname($faker->firstName());
        $user->setPhoto('placeholder.png');
        $password = $this->hasher->hashPassword($user, '1234');
        $user->setPassword($password);
        $user->setEmail('user@user.fr');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $user2 = new User();
        $user2->setLastname($faker->lastName());
        $user2->setFirstname($faker->firstName());
        $user2->setPhoto('placeholder.png');
        $password = $this->hasher->hashPassword($user, '1234');
        $user2->setPassword($password);
        $user2->setEmail('admin@admin.fr');
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);

        $manager->flush();
    }
}
