<?php

namespace AppBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RateUsdForm.
 */
class RateUsdForm extends AbstractType implements ContainerAwareInterface
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
                'currency',
                CurrencyType::class,
                [
                    'label' => 'Валюта',
                    'attr' => [
                        'autocomplete' => 'off',
                        'ng-model' => 'rate.currency'
                    ],
                    'constraints' => [new NotBlank()],
                    'empty_value' => '---',
                    'choices' => $this->container->get('app_currency')->getWalletCurrencyChoices()
                ]
            )
            ->add(
                'rateValue',
                NumberType::class,
                [
                    'label' => 'Курс',
                    'attr' => [
                        'class' => '',
                        'autocomplete' => 'off',
                        'ng-model' => 'rate.rateValue'
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )

            ->add(
                'rateDate',
                DateType::class,
                [
                    'label' => 'Дата',
                    'constraints' => [new NotBlank()],
                    'attr' => [
                        'ng-model' => 'rate.rateDate'
                    ],
                ]
            )
        ;


        $builder->add(
            'submitButton',
            SubmitType::class,
            [
                'label' => 'Добавить курс',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'ng-click' => 'addRate(rate_usd_form)'
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
        return 'rate_usd_form';
    }
}
