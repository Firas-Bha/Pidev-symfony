<?php

namespace App\Form;

use App\Entity\Commandes;
use App\Entity\Panniers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('titre')
            ->add('prix')
            ->add('Total')
            ->add('modepay')
            ->add('Date')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('Panniers', EntityType::class,
                ['class'=>Panniers::class , 'choice_label'=>'nom'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
