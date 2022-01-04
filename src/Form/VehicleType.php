<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('year', TextType::class, [
                'label' => 'Année',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez l\'année de la voiture']
            ])
            ->add('model', TextType::class, [
                'label' => 'model ',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez le model de la voiture']
            ])
            ->add('description', TextArea::class, [
                'label' => 'Caractéristique',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez les caratéristique de la voiture']
            ])
            ->add('town', TextType::class, [
                'label' => 'ville',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez la ville de la voiture']
            ])
            ->add('category', EntityType::class, [
                'label' => 'catégory',
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
            ->add('createdAt',SubmitType::class,[
                'label' => 'Créer',
                'required' => true,
                'attr' => ['class' => 'btn btn-warning d-block mx-auto my-3 col-4']
            ])
            ->add('updatedAt',SubmitType::class,[
                'label' => 'Modifier',
                'required' => true,
                'attr' => ['class' => 'btn btn-warning d-block mx-auto my-3 col-4']
            ])
            ->add('deletedAt',SubmitType::class,[
                    'label' => 'supprimer',
                    'required' => true,
                    'attr' => ['class' => 'btn btn-warning d-block mx-auto my-3 col-4']
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
        ]);
    }
}
