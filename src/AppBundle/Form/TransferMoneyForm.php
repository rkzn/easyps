<?php

namespace AppBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Class TransferMoneyForm.
 */
class TransferMoneyForm extends AbstractType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'receiver',
                TextType::class,
                [
                    'label' => 'Получатель',
                    'attr' => [
                        'class' => '',
                        'autocomplete' => 'off',
                        'ng-model' => 'transfer.receiver'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 45]),
                        new Regex(['pattern' => "/^[a-z-' ]+$/i"]),
                    ],
                ]
            )
            ->add(
                'amount',
                TextType::class,
                [
                    'label' => 'Сумма',
                    'attr' => [
                        'autocomplete' => 'off',
                        'ng-model' => 'transfer.amount'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 45]),
                        new Regex(['pattern' => "/^[a-z-' ]+$/i"]),
                    ],
                ]
            )
            ->add(
                'currency',
                CurrencyType::class,
                [
                    'label' => 'Валюта',
                    'attr' => [
                        'autocomplete' => 'off',
                        'ng-model' => 'transfer.currency'
                    ],
                    'constraints' => [new NotBlank()],
                    'empty_value' => '---',
                ]
            )
        ;


        $builder->add(
            'submitButton',
            SubmitType::class,
            [
                'label' => 'Перевести деньги',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'ng-click' => 'transferMoney(transfer_money_form)'
                ],
            ]
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'attr' => [
                    'class' => 'form',
                    'novalidate' => '',
                ],
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'transfer_money_form';
    }
}
