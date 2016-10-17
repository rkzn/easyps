<?php

namespace AppBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Class RegistrationForm.
 */
class RegistrationForm extends AbstractType implements ContainerAwareInterface
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
                'username',
                TextType::class,
                [
                    'label' => 'Имя',
                    'attr' => [
                        'class' => '',
                        'autocomplete' => 'off',
                        'ng-model' => 'client.username'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 45]),
                        new Regex(['pattern' => "/^[a-z-' ]+$/i"]),
                    ],
                ]
            )

            ->add(
                'country',
                CountryType::class,
                [
                    'label' => 'Страна',
                    'constraints' => [new NotBlank()],
                    'empty_value' => '---',
                    'attr' => [
                        'class' => '',
                        'autocomplete' => 'off',
                        'ng-model' => 'client.country'
                    ],
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'label' => 'Город',
                    'attr' => [
                        'class' => '',
                        'autocomplete' => 'off',
                        'ng-model' => 'client.city'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 45]),
                        new Regex(['pattern' => "/^[a-z-' ]+$/i"]),
                    ]
                ]
            )
            ->add(
                'currency',
                CurrencyType::class,
                [
                    'label' => 'Валюта кошелька',
                    'attr' => [
                        'class' => '',
                        'ng-model' => 'client.currency'
                    ],
                    'constraints' => [new NotBlank()],
                    'empty_value' => '---',
                    'choices' => $this->container->get('app_currency')->getList()
                ]
            )
        ;


        $builder->add(
            'submitButton',
            SubmitType::class,
            [
                'label' => 'Открыть кошелек',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'ng-click' => 'registerClient(registration_form)'
                ],
            ]
        );

        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'validateCustom']);
    }

    /**
     * @param FormEvent $event
     */
    public function validateCustom(FormEvent $event)
    {
        $data = $event->getData();

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
                    'role' => 'form',
                ],
                'currency' => null,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'registration_form';
    }
}
