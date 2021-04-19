<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StackoverflowProxyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');
        $builder
            ->add('tagged',TextType::class,[
                'label'=>'Tagged: (TAB for sepation)'
            ])
            ->add('fromdate',DateType::class,[
                'label'=>'From date:',
                'format' => 'dd MM yyyy',
                'placeholder' => [
                     'day' => 'Day', 'month' => 'Month', 'year' => 'Year'
                ]
            ])
            ->add('todate',DateType::class,[
                'label'=>'To date:',
                'format' => 'dd MM yyyy',
                'placeholder' => [
                    'day' => 'Day', 'month' => 'Month', 'year' => 'Year'
                ]
            ])
            ->add('order',ChoiceType::class,[
                'choices'=> [
                    'desc'=>'desc',
                    'asc'=>'asc'
                ]
            ])
            ->add('sort',ChoiceType::class,[
                'choices'=> [
                    'activity'=>'activity',
                    'votes'=>'votes',
                    'creation'=>'creation',
                    'hot'=>'hot',
                    'week'=>'week',
                    'month'=>'month',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
