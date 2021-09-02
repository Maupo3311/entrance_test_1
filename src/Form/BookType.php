<?php

namespace App\Form;

use DateTime;
use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder A FormBuilderInterface instance.
     * @param array                $options List options.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($options);
        $builder
            ->add('name', TextType::class, [
                'label' => 'Book title',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('year', IntegerType::class, [
                'label' => 'The year of publishing',
                'attr'  => [
                    'max' => (new DateTime('now'))->format('Y'),
                    'min' => 500,
                    'class' => 'form-control',
                ],
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('numberOfPages', IntegerType::class, [
                'label' => 'Number of pages',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('authors', EntityType::class, [
                'class'    => Author::class,
                'multiple' => true,
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('image', FileType::class, [
                'label'       => 'Image',
                'required'    => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '1024k',
                        'mimeTypes'        => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
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
            'data_class' => Book::class,
        ]);
    }
}
