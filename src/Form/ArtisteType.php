<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Form\Field\SearchableEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArtisteType extends AbstractType
{

    public function __construct(private UrlGeneratorInterface $url)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomScene')
            ->add('style')
            ->add('festivals', SearchableEntityType::class,[
                'class' => 'App\Entity\Festival',
                'search' => $this->url->generate('app_get_festival'),
                'label_property' => 'nom',
                'value_property' => 'id',
                'required' => false
            ])
            ->add('instruments',CollectionType::class,[
                'entry_type'=>InstrumentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,
        ]);
    }
}
