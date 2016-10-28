<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EditBook extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('cover', FileType::class, array('data_class' => null, 'required' => false,))
            ->add('deletecover', CheckboxType::class, array('mapped' => false,'required' => false,))
            ->add('bookfile', FileType::class, array('data_class' => null,'required' => false,))
            ->add('deletebookfile', CheckboxType::class, array('mapped' => false,'required' => false,))
            ->add('readdate', DateType::class, array(
                'placeholder' => 'Select a value'))
            ->add('ispublic', CheckboxType::class, array('required' => false,))
            ->add('save', SubmitType::class)
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Book',
        ));
    }
}