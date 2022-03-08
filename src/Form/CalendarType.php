<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\Evenement;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Dompdf\Dompdf;
use Dompdf\Options;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('start')
            ->add('end')
            ->add('description')
            ->add('all_day')
            ->add('background_color',ColorType::class)
            ->add('border_color',ColorType::class)
            ->add('text_color',ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
