<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder A FormBuilderInterface instance.
     * @param array                $options List options.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($options);
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('middleName', TextType::class, [
                'label' => 'Middle Name',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('books', EntityType::class, [
                'class'    => Book::class,
                'multiple' => true,
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver A OptionsResolver instance.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
