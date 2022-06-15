<?php

namespace App\DataFixtures;

use Faker;
use DateTimeImmutable;
use App\Entity\Meeting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i<200; $i++){
            $meeting = new Meeting();
            $meeting->setPlace($faker->address);
            $meeting->setName($faker->text($maxNbChars = 10));
            $endDate = $faker->dateTime($max = 'now', $timezone = null);
            $startDate = $faker->dateTime($max = $endDate, $timezone = null);
            $meeting->setStartDate(DateTimeImmutable::createFromMutable($startDate));
            $meeting->setEndDate(DateTimeImmutable::createFromMutable($endDate));
            $meeting->setPriority($faker->numberBetween($min = 0, $max = 3));

            $manager->persist($meeting);
        }

        $manager->flush();
    }
}
