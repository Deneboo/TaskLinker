<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\UserContract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'Nom',
            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'Prénom',
            ])
            ->add('email', TextType::class, [
                'required' => false,
                'label' => 'Email',
            ])
            ->add('hiredAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date d\'embauche',
            ])
            ->add('contract', EnumType::class, [
                'class' => UserContract::class,
                'label' => 'Type de contrat',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
