<?php
// src/Controller/ActivityController.php

namespace App\Controller;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActivityController extends AbstractController
{
#[Route('/api/activities/search', name: 'activity_search', methods: ['POST'])]
public function search(Request $request, EntityManagerInterface $entityManager): Response
{
$data = json_decode($request->getContent(), true);

$place = $data['place'] ?? null;
$period = $data['period'] ?? null;

if (!$place || !$period) {
return $this->json(['error' => 'Missing parameters'], Response::HTTP_BAD_REQUEST);
}

$query = $entityManager->createQuery(
'SELECT a
FROM App\Entity\Activity a
WHERE a.place = :place
AND a.period = :period
ORDER BY a.id ASC'
)->setParameters([
'place' => $place,
'period' => $period
]);

$activities = $query->getResult();

if (!$activities) {
return $this->json(['error' => 'No activities found'], Response::HTTP_NOT_FOUND);
}

// Choose a different activity (this logic can be customized)
$activity = $activities[array_rand($activities)];

return $this->json($activity);
}
    #[Route('/api/activities/filter', name: 'activity_filter', methods: ['POST'])]
    public function filter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $place = $data['place'] ?? null;
        $period = $data['period'] ?? null;
        $numberOfPeople = $data['number_of_people'] ?? null;
        $name = $data['name'] ?? null;

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('a')
            ->from(Activity::class, 'a')
            ->orderBy('a.id', 'ASC');

        if ($place) {
            $queryBuilder->andWhere('a.place = :place')
                ->setParameter('place', $place);
        }

        if ($period) {
            $queryBuilder->andWhere('a.period = :period')
                ->setParameter('period', $period);
        }

        if ($numberOfPeople) {
            $queryBuilder->andWhere('a.numberSpace >= :number_of_people')
                ->setParameter('number_of_people', $numberOfPeople);
        }

        if ($name) {
            $queryBuilder->andWhere('a.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        $queryBuilder->setMaxResults(5);

        $activities = $queryBuilder->getQuery()->getResult();

        if (!$activities) {
            return $this->json(['error' => 'No activities found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($activities);
    }
}
