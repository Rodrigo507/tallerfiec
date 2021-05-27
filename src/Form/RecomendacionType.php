<?php

namespace App\Form;

use App\Entity\Recomendacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecomendacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto')
            ->add('detalle')
            ->add('enviar',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-success'
                ]
            ])

//            ->add('fecha')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recomendacion::class,
        ]);
    }
}
