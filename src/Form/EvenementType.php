<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('Nom')
            ->add('Capacite')
            ->add('Date')
            ->add('Description')
            ->add('Image',FileType::class,[
                'label'=>'Image Evenement',
                'mapped'=> false])
            ->add('Adresse')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
