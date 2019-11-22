<?php

namespace App\Form;

use App\Entity\ForumTopics;
use App\Entity\ForumCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ForumTopicsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title')
        ->add('content')
        ->add('forumCategory', EntityType::class, [
            'class' => ForumCategory::class,
            'choice_label' => 'title'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumTopics::class,
        ]);
    }
}
