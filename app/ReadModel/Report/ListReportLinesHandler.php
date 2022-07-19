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
    private const SORT_COLUMNS = ['employee_id', 'first_name', 'last_name', 'department', 'base_salary', 'raw_base_salary', 'bonus', 'raw_bonus', 'bonus_type', 'salary', 'raw_salary'];

    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ListReportLines $query): array
    {
        $q = $this->conn->createQueryBuilder()
            ->select('first_name', 'last_name', 'department', 'base_salary', 'bonus', 'bonus_type', 'salary')
            ->from('report_line_read_model')
            ->where('report_id = :reportId')
            ->setParameter('reportId', $query->reportId->toString());

        if ($query->sortBy && in_array($query->sortBy->column, self::SORT_COLUMNS)) {
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
