<?php

declare(strict_types=1);

namespace App\ReadModel\Report;

use App\ReadModel\Report\Query\ListReportLines;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ListReportLinesHandler
{
    private const FILTER_COLUMNS = ['first_name', 'last_name', 'department'];

    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ListReportLines $query): array
    {
        $q = $this->conn->createQueryBuilder()
            ->select('employee_id', 'first_name', 'last_name', 'department', 'base_salary', 'bonus', 'bonus_type', 'salary')
            ->from('report_line_read_model')
            ->where('report_id = :reportId')
            ->setParameter('reportId', $query->reportId->toString());

        if ($query->sortBy) {
            $q->orderBy($query->sortBy->column, $query->sortBy->order());
        }

        foreach ($query->filters as $filter) {
            if (!in_array($filter->column, self::FILTER_COLUMNS)) {
                continue;
            }

            $q->andWhere($q->expr()->like($filter->column, $filter->parameter()));
            $q->setParameter($filter->column, $filter->filterValue());
        }

        return $q->executeQuery()->fetchAllAssociative();
    }
}
