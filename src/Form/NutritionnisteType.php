<?php

namespace App\Form;

use App\Entity\Nutritionniste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NutritionnisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_nut')
            ->add('PrenomNut')
            ->add('adresseNut')
            ->add('num_nut')
           /* ->add('client',EntityType::class,
                [
                    'class'=>Client::class,
                    'choice_label'=>'Nom',
                    'multiple'=>false,
                ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nutritionniste::class,
        ]);
    }
}
