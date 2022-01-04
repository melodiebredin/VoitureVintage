<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'attr' => ['placeholder' => 'Entrez un prix']
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
