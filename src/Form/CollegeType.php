<?php

namespace App\Form;

use App\Entity\College;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollegeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('name',
                TextType::class,
                [
                    'label'=>'Nom de l\'etablissement'
                ]
            ) ->add('adresse')
            ->add('adresse',
                TextType::class,
                [
                    'label'=>'Adresse de l\'tablissement'
                ]
            )

            ->add('cp',
                TextType::class,
                [
                    'label'=>'Code postal'
                ]
            )


            ->add('ville', TextType::class, ['label'=>'Ville'])


            ->add('presentation', TextType::class, ['label'=>'Presentation'])

            ->add('image', FileType::class, ['label'=>'Choisissez une image pour votre reso',
                'required'   => false,
                'data_class' => null
            ])

       //     ->add('status', TextType::class, ['label'=>'status'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => College::class,
        ]);
    }
}
