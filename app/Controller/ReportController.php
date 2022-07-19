<?php

namespace App\Controller;

use App\ReadModel\Report\Query\GetReport;
use App\ReadModel\Report\Query\ListReportLines;
use App\ReadModel\Report\Query\ListReports;
use App\ReadModel\Shared\FilterBy;
use App\ReadModel\Shared\SortBy;
use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Shared\CQRS\CommandBus;
use Payroll\Shared\CQRS\QueryBus;
use Payroll\Shared\UUID\ReportId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    public function __construct(private QueryBus $queryBus, private CommandBus $commandBus)
    {
    }

    #[Route('/', name: 'app_reports', methods: 'GET')]
    public function index(): Response
    {
        $reports = $this->queryBus->query(new ListReports());

        return $this->render('report/index.html.twig', [
            'reports' => $reports,
        ]);
    }

    #[Route('/report/{id}', name: 'app_report', methods: 'GET')]
    public function report(string $id): Response
    {
        $reportId = ReportId::of($id);
        $report = $this->queryBus->query(new GetReport($reportId));

        if (!$report) {
            throw $this->createNotFoundException('The report does not exist');
        }

        return $this->render('report/show.html.twig', [
            'report' => $report,
        ]);
    }

    #[Route('/report/generate', name: 'app_report_generate', methods: 'POST')]
    public function generate(): Response
    {
        $reportId = ReportId::newOne();
        $this->commandBus->dispatch(new GenerateSalaryReport($reportId));

        return $this->redirectToRoute('app_report', ['id' => $reportId->toString()]);
    }

    #[Route('/api/report/{id}/lines', name: 'api_report_lines', methods: 'GET', format: 'json')]
    public function reportLines(Request $request, string $id): Response
    {
        $reportId = ReportId::of($id);
        $report = $this->queryBus->query(new GetReport($reportId));

        if (!$report) {
            return $this->json([
                'message' => 'The report does not exist',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($report['status'] === 'PROCESSING') {
            return $this->json([
                'message' => 'Report is still processing',
            ], Response::HTTP_ACCEPTED);
        }

        $filters = FilterBy::ofList($request->query->all()['filters'] ?? []);
        $sort = null;
        if ($request->query->has('sort')) {
            $sort = SortBy::fromQueryString($request->query->get('sort'));
        }

        $reportLines = $this->queryBus->query(new ListReportLines($reportId, $sort, $filters));

        return $this->json($reportLines);
    }
}
