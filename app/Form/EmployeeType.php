<?php

namespace App\Form;

use App\ReadModel\Department\Query\ListDepartmentsChoices;
use Payroll\Shared\QueryBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EmployeeType extends AbstractType
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('employmentDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])
            ->add('baseSalary', MoneyType::class, [
                'required' => true,
                'divisor' => 100,
                'currency' => 'USD',
            ])
            ->add('department', ChoiceType::class, [
                'choices'  => $this->queryBus->query(new ListDepartmentsChoices()),
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
