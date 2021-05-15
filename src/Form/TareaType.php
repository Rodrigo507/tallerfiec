<?php

namespace App\Form;

use App\Entity\Tarea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('descripcion')
            ->add('prioridad')
            ->add('estado')
//            ->add('file')
            ->add('userasing')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tarea::class,
        ]);
    }
}
