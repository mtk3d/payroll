<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\ReadModel\Employee\Query\ListEmployees;
use Money\Money;
use Payroll\Employment\Application\Command\CreateEmployee;
use Payroll\Salary\Application\Command\CreateEmployeeSalary;
use Payroll\Shared\CQRS\CommandBus;
use Payroll\Shared\CQRS\QueryBus;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    public function __construct(private QueryBus $queryBus, private CommandBus $commandBus)
    {
    }

    #[Route('/employee', name: 'app_employee')]
    public function index(): Response
    {
        $employees = $this->queryBus->query(new ListEmployees());

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/employee/new', name: 'app_employee_add', methods: 'GET')]
    public function new(): Response
    {
        $form = $this->createForm(EmployeeType::class, null, [
            'action' => $this->generateUrl('app_employee_create'),
            'method' => 'POST',
        ]);

        return $this->render('department/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employee/create', name: 'app_employee_create', methods: 'POST')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(EmployeeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $employeeId = EmployeeId::newOne();
            $departmentId = DepartmentId::of($data['department']);
            $baseSalary = Money::USD(strval($data['baseSalary']));
            $createEmployee = new CreateEmployee($employeeId, $data['firstName'], $data['lastName'], $departmentId);
            $createEmployeeSalary = new CreateEmployeeSalary($employeeId, $data['employmentDate'], $baseSalary, $departmentId);
            $this->commandBus->dispatch($createEmployee);
            $this->commandBus->dispatch($createEmployeeSalary);

            return $this->redirectToRoute('app_employee');
        }

        return $this->redirectToRoute('app_employee_add');
    }
}
