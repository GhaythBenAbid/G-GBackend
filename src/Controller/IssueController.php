<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IssueController extends AbstractController
{
    /**
     * @Route("/api/Issues", name="api_GetAll_Issues", methods={"GET"})
     */
    public function getAllIssues(IssueRepository $issueRepository)
    {

        return $this->json($issueRepository->findAll(), 200, [], ['groups' => 'issue']);
    }
}
