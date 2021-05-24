<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('prix', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'class' => 'form-control'
                ],
                'html5' => true
            ])
            ->add('resume', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('nbPages', NumberType::class, [
                'attr' => [
                    'min' => 1,
                    'class' => 'form-control'
                ],
                'html5' => true,
            ])
            ->add('dateParution', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
            ])
            ->add('auteur', EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'class' => Auteur::class,
                'choice_label' => function ($auteur) {
                    return $auteur;
                },
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success m-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
