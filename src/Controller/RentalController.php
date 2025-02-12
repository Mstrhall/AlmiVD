<?php
// src/Controller/RentalController.php

namespace App\Controller;

use App\Entity\Rental;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RentalController extends AbstractController
{
    #[Route('/api/rentals/search', name: 'rental_search', methods: ['POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $place = $data['place'] ?? null;
        $minPrice = $data['min_price'] ?? null;
        $maxPrice = $data['max_price'] ?? null;
        $dateStart = $data['date_start'] ?? null;
        $dateEnd = $data['date_end'] ?? null;

        if (!$place || !$minPrice || !$maxPrice || !$dateStart || !$dateEnd) {
            return $this->json(['error' => 'Missing parameters'], Response::HTTP_BAD_REQUEST);
        }

        // Convert date strings to DateTime objects
        try {
            $dateStart = new \DateTime($dateStart);
            $dateEnd = new \DateTime($dateEnd);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
        }

        $query = $entityManager->createQuery(
            'SELECT r
            FROM App\Entity\Rental r
            WHERE r.place LIKE :place
            AND r.price BETWEEN :minPrice AND :maxPrice
            AND r.dateStart >= :dateStart
            AND r.dateEnd <= :dateEnd
            ORDER BY r.dateStart ASC'
        )->setParameters([
            'place' => '%' . $place . '%',
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd
        ]);

        $rentals = $query->getResult();

        if (!$rentals) {
            return $this->json(['error' => 'No rentals found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($rentals);
    }
    #[Route('/api/rentals/filter', name: 'rental_filter', methods: ['POST'])]
    public function filter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $place = $data['place'] ?? null;
        $dateStart = $data['date_start'] ?? null;
        $dateEnd = $data['date_end'] ?? null;

        if (!$place) {
            return $this->json(['error' => 'Missing parameter: place'], Response::HTTP_BAD_REQUEST);
        }

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('r')
            ->from(Rental::class, 'r')
            ->where('r.place LIKE :place')
            ->setParameter('place', '%' . $place . '%');

        if ($dateStart) {
            try {
                $dateStart = new \DateTime($dateStart);
                $queryBuilder->andWhere('r.dateStart >= :dateStart')
                    ->setParameter('dateStart', $dateStart);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($dateEnd) {
            try {
                $dateEnd = new \DateTime($dateEnd);
                $queryBuilder->andWhere('r.dateEnd <= :dateEnd')
                    ->setParameter('dateEnd', $dateEnd);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
            }
        }

        $rentals = $queryBuilder->getQuery()->getResult();

        if (!$rentals) {
            return $this->json(['error' => 'No rentals found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($rentals);
    }
}
