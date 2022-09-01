<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Departement;
use App\Entity\Festival;
use App\Form\Field\SearchableEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FestivalType extends AbstractType
{

    public function __construct(private UrlGeneratorInterface $url)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('affiche', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false
            ])
            ->add('lieu')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('departement', EntityType::class,[
                'class' => 'App\Entity\Departement',
                'choice_label' => function (Departement $departement) {
                    return $departement->getNum()." - ".$departement->getNom();
                }
            ])
            ->add('artistes', SearchableEntityType::class,[
                'class' => 'App\Entity\Artiste',
                'search' => $this->url->generate('app_get_artiste'),
                'label_property' => 'nomScene',
                'value_property' => 'id',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
