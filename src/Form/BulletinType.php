<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Bulletin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BulletinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du Bulletin',
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    // Valeur affichée => Valeur retenue
                    'Général' => 'Général',
                    'Divers' => 'Divers',
                    'Urgent' => 'Urgent', 
                ],
                'expanded' => false, //Menu déroulant ou Case à cocher
                'multiple' => false, //Choix multiple, ici FALSE sous peine d'erreur
            ])
            ->add('tags', EntityType::class, [ 
                //EntityType est un champ apparenté à ChoiceType proposant une liste d'instances d'une autre Entity à lier à notre Entity
                'label' => 'Tags', //Désignation du champ
                'class' => Tag::class, //Type de l'Entity à lier
                'choice_label' => 'name', //Attribut représentant notre objet visé
                'expanded' => true, //Boutons plutôt que liste
                'multiple' => true, //Plusieurs choix, nécessaire en raison du ManyToMany
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu du Bulletin',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider', //Détermine le nom du champ/bouton
                'attr' => [
                    'class' => 'btn btn-success', //Nous permet de choisir une classe CSS
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bulletin::class,
        ]);
    }
}
