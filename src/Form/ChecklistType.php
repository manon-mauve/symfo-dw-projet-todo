<?php

namespace App\Form;

use App\Entity\Checklist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ChecklistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('imageFile', VichImageType::class, [
                'label' => 'PNG or JPG File',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Checklist::class,
        ]);
    }
}
