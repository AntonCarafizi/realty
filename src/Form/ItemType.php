<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'title'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'type',
                'choices' => [
                    'apartment' => 0,
                    'house' => 1,
                    'garage' => 2
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'description'
            ])
            ->add('area', IntegerType::class, [
                'label' => 'area'
            ])
            ->add('price', MoneyType::class, [
                'label' => 'price'
            ])
            ->add('images', FileType::class, [
                'label' => 'images.jpg',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr'     => [
                    'accept' => 'image/jpeg',
                    'multiple' => 'multiple'
                ],
            ])
            ->add('room_qty', NumberType::class, [
                'label' => 'room.qty'
            ])
            ->add('is_for_rent', ChoiceType::class, [
                'label' => 'is.for',
                'choices'  => [
                    'rent' => true,
                    'sale' => false,
                ],
            ])
            ->add('location', LocationType::class, [
                'label' => 'location',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
