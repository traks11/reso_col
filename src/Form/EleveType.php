<?php

namespace App\Form;

use App\Entity\Eleves;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nom', TextType::class, ['label'=>'nom'])
            ->add('Prenom', TextType::class, ['label'=>'Prenom'])
            ->add('email', TextType::class, ['label'=>'Email'])
            ->add('classe', TextType::class, ['label'=>'Classe'])
            ->add('niveau', TextType::class, ['label'=>'Année'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Eleves::class,
        ]);
    }
}
