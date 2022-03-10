<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Coach;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoachType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Id')
            ->add('nom',TextType::class,[
                'label'=>'nom Coach',
                'attr'=> [
                    'placeholder'=>'nom',
                    'class'=>'nom'
                ]
            ])
            ->add('prenom')
            ->add('specialite')
            ->add('adresse')
            ->add('numero')
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
            'data_class' => Coach::class,
        ]);
    }
}
