<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Salle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Id')
            ->add('TypeC',TextType::class,[
                'label'=>'Type',
                'attr'=>[
                    'placeholder'=>'Merci de remplir ce champs',
                    'class'=>'TypeC'
                ]
            ])
            ->add('DateC')
            ->add('HeureDebutC')
            ->add('DureeC')
            ->add('NiveauC',ChoiceType::class,[
                'choices' => $this->getchoices()
                ])
            ->add('ImageC',FileType::class,array('data_class'=>null))
            ->add('couleur', ColorType::class)
            ->add('SerieC')
        ->add('Salle',EntityType::class,[
                'class'=>Salle::class,
                'choice_label'=>'NomS'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }

    public function getchoices()
    {
        $choices= Cours::HEAT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$k]=$v;
        }
        return $output;
    }
}
