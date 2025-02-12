<?php
// src/Controller/SimpleProgramController.php

namespace App\Controller;

use App\Entity\Program;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgramController extends AbstractController
{
    #[Route('/api/contextprogram/{id}', name: 'get_program', methods: ['GET'])]
    public function getProgram($id,ProgramRepository $programRepository, Request $request): Response
    {
        $program = $programRepository->find($id);

        if (!$program) {
            return $this->json(['error' => 'Program not found'], Response::HTTP_NOT_FOUND);
        }

        $programData = [
            'id' => $program->getId(),
            'place' => $program->getPlace(),
            'dateStart' => $program->getDateStart() ? $program->getDateStart()->format('Y-m-d') : null,
            'dateEnd' => $program->getDateEnd() ? $program->getDateEnd()->format('Y-m-d') : null,
            'numberPerson' => $program->getNumberPerson(),
            'focus' => $program->getFocus(),
            'theme' => $program->getTheme(),
            'activity'=> $program->getActivityIds(),

        ];

        return $this->json($programData);
    }
}
