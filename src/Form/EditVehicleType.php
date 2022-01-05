<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez le nom de votre vehicule']
        ])

        ->add('brand', TextType::class, [
            'label' => 'Marque',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez la marque de votre vehicule']
        ])
        ->add('year', IntegerType::class, [
            'label' => 'Année',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez l\'année de la voiture']
        ])
        ->add('model', TextType::class, [
            'label' => 'model ',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez le model de la voiture']
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Caractéristique',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez les caratéristique de la voiture']
        ])
        ->add('town', TextType::class, [
            'label' => 'ville',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez la ville de la voiture']
        ])
        ->add('price', MoneyType::class, [
            'label' => 'Prix',
            'attr' => ['placeholder' => 'Entrez votre  prix']
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
             'choice_label' => 'name',
             'required' => true,
             'attr' => ['placeholder' => 'Entrez la catégory de vôtre voiture']
         ])
        ->add('fuel', TextType::class, [
            'label' => 'carburant',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez le carburant utiliser']
        ])
        ->add('picture', FileType::class, [
            'label' => 'Photo',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez votre photo']
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Ajouter',
            'attr' => ['class' => 'btn btn-warning d-block mx-auto my-3 col-4']
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
            'allow_file_upload' => true,
        ]);
    }
}
