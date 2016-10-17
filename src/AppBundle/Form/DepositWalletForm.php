<?php

namespace AppBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class DepositWalletForm.
 */
class DepositWalletForm extends AbstractType implements ContainerAwareInterface
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
                'amount',
                NumberType::class,
                [
                    'label' => 'Сумма',
                    'attr' => [
                        'class' => '',
                        'autocomplete' => 'off',
                        'ng-model' => 'deposit.amount'
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'currency',
                CurrencyType::class,
                [
                    'label' => 'Валюта',
                    'attr' => [
                        'class' => '',
                        'autocomplete' => 'off',
                        'ng-model' => 'deposit.currency'
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
                'label' => 'Пополнить кошелек',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'ng-click' => 'depositWallet(deposit_wallet_form)'
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
        return 'deposit_wallet_form';
    }
}
