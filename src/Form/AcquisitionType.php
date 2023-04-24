<?php

namespace App\Form;

use App\Entity\Acquisition;
use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcquisitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /* ->add('user')
            ->add('post') */
            ->add('address', EntityType::class,
            [
                'class' => Address::class,
                'choice_label' => function ($address) {
                    return $address->getDisplayFullAddress();
                },
            ])
            ->add('Buy', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Acquisition::class,
        ]);
    }
}
