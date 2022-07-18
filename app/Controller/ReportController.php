<?php

namespace App\Controller;

use App\ReadModel\Report\Query\ListReports;
use Payroll\Shared\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    public function __construct(private QueryBus $queryBus)
    {}

    #[Route('/', name: 'app_report')]
    public function index(): Response
    {
        $reports = $this->queryBus->query(new ListReports());

        return $this->render('report/index.html.twig', [
            'reports' => $reports,
        ]);
    }

    #[Route('/report/{id}', name: 'report')]
    public function report(string $id): Response
    {

    }
}
