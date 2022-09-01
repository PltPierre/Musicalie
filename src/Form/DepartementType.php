<?php

namespace App\Form;

use App\Entity\Departement;
use App\Form\Field\SearchableEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DepartementType extends AbstractType
{

    public function __construct(private UrlGeneratorInterface $url)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num')
            ->add('nom')
            ->add('festivals', SearchableEntityType::class,[
                'class' => 'App\Entity\Festival',
                'search' => $this->url->generate('app_get_festival'),
                'label_property' => 'nom',
                'value_property' => 'id',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Departement::class,
        ]);
    }
}
