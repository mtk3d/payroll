<?php

namespace App\Controller;

use App\Form\DepartmentType;
use App\ReadModel\Department\Query\ListDepartments;
use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Shared\CommandBus;
use Payroll\Shared\QueryBus;
use Payroll\Shared\UUID\DepartmentId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    public function __construct(private QueryBus $queryBus, private CommandBus $commandBus)
    {
    }

    #[Route('/department', name: 'app_department', methods: 'GET')]
    public function index(): Response
    {
        $departments = $this->queryBus->query(new ListDepartments());

        return $this->render('department/index.html.twig', [
            'departments' => $departments,
        ]);
    }

    #[Route('/department/new', name: 'app_department_add', methods: 'GET')]
    public function new(): Response
    {
        $form = $this->createForm(DepartmentType::class, null, [
            'action' => $this->generateUrl('app_department_create'),
            'method' => 'POST',
        ]);

        return $this->render('department/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/department/create', name: 'app_department_create', methods: 'POST')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(DepartmentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $departmentId = DepartmentId::newOne();
            $this->commandBus->dispatch(new CreateDepartment($departmentId, $data['name']));
            $this->commandBus->dispatch(new SetDepartmentBonus($departmentId, $data['bonusType'], $data['bonusFactor']));

            return $this->redirectToRoute('app_department');
        }

        return $this->redirectToRoute('app_department_add');
    }
}
