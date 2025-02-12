<?php
// src/Controller/FlightController.php

namespace App\Controller;

use App\Entity\Flight;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FlightController extends AbstractController
{
    #[Route('/api/flights/search', name: 'flight_search', methods: ['POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $airportStart = $data['airport_start'] ?? null;
        $airportEnd = $data['airport_end'] ?? null;
        $dateStart = $data['date_start'] ?? null;
        $dateEnd = $data['date_end'] ?? null;

        if (!$airportStart || !$airportEnd || !$dateStart || !$dateEnd) {
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
            'SELECT f
            FROM App\Entity\Flight f
            WHERE f.airportStart = :airportStart
            AND f.airportEnd = :airportEnd
            AND f.dateStart >= :dateStart
            AND f.dateEnd <= :dateEnd
            ORDER BY f.dateStart ASC'
        )->setParameter('airportStart', $airportStart)
            ->setParameter('airportEnd', $airportEnd)
            ->setParameter('dateStart', $dateStart)
            ->setParameter('dateEnd', $dateEnd);

        $flights = $query->getResult();

        if (!$flights) {
            return $this->json(['error' => 'No flights found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($flights);
    }

    #[Route('/api/flights/filter', name: 'flight_filter', methods: ['POST'])]
    public function filter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $airportStart = $data['airport_start'] ?? null;
        $airportEnd = $data['airport_end'] ?? null;
        $dateStart = $data['date_start'] ?? null;
        $dateEnd = $data['date_end'] ?? null;

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('f')
            ->from(Flight::class, 'f')
            ->orderBy('f.dateStart', 'ASC');

        if ($airportStart) {
            $queryBuilder->andWhere('f.airportStart = :airportStart')
                ->setParameter('airportStart', $airportStart);
        }

        if ($airportEnd) {
            $queryBuilder->andWhere('f.airportEnd = :airportEnd')
                ->setParameter('airportEnd', $airportEnd);
        }

        if ($dateStart) {
            try {
                $dateStart = new \DateTime($dateStart);
                $queryBuilder->andWhere('f.dateStart >= :dateStart')
                    ->setParameter('dateStart', $dateStart);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($dateEnd) {
            try {
                $dateEnd = new \DateTime($dateEnd);
                $queryBuilder->andWhere('f.dateEnd <= :dateEnd')
                    ->setParameter('dateEnd', $dateEnd);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
            }
        }

        $queryBuilder->setMaxResults(5);

        $flights = $queryBuilder->getQuery()->getResult();

        if (!$flights) {
            return $this->json(['error' => 'No flights found'], Response::HTTP_NOT_FOUND);
        }

        // Find return flights
        $returnFlights = [];
        if ($airportEnd && $dateEnd) {
            $returnQueryBuilder = $entityManager->createQueryBuilder();
            $returnQueryBuilder->select('f')
                ->from(Flight::class, 'f')
                ->where('f.airportStart = :airportEnd')
                ->andWhere('f.airportEnd = :airportStart')
                ->andWhere('f.dateStart >= :dateEnd')
                ->setParameter('airportEnd', $airportEnd)
                ->setParameter('airportStart', $airportStart)
                ->setParameter('dateEnd', $dateEnd)
                ->orderBy('f.dateStart', 'ASC')
                ->setMaxResults(5);

            $returnFlights = $returnQueryBuilder->getQuery()->getResult();
        }

        return $this->json([
            'flights' => $flights,
            'return_flights' => $returnFlights
        ]);
    }
}
