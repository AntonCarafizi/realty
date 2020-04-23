<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', CountryType::class, [
                'label' => 'country',
                'mapped'  => true,
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'zipCode',
                'mapped'  => true,
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'address',
                'mapped'  => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
