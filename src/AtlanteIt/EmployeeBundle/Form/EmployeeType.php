<?php

namespace AtlanteIt\EmployeeBundle\Form;

use AtlanteIt\EmployeeBundle\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EmployeeType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage){

        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user) {
            throw new \LogicException('The SaleFormType cannot be used without an authenticated user****!');
        }

        $builder
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('email', TextType::class)
            ->add('title', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('titleofcourtesy', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('birthdate', DateTimeType::class,
                array('widget'=>'single_text'))
            ->add('hiredate', DateTimeType::class,
                array('widget'=>'single_text',
                'disabled'=>!$user->isAdmin()))
            ->add('RolesCollection', EntityType::class, array(
                'class'=>'AtlanteIt\EmployeeBundle\Entity\Role',
                'multiple' => TRUE,
                'expanded' => TRUE,
                'disabled' =>!$user->isAdmin(),
            ))
            ->add('address', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('city', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('region', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('postalcode', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('country', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('homephone', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('extension', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('notes',TextareaType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('reportsTo', TextType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('photopath')
            ->add('salary', TextType::class, array(
                'disabled'=>!$user->isAdmin())
            )
            ->add('isActive',CheckboxType::class, array(
                'disabled'=>!$user->isAdmin()))
            ->add('plainPassword' , RepeatedType:: class, array(
                'type' => PasswordType:: class,
                'first_options' => array('label' => 'Password' ),
                'second_options' => array('label' => 'Repeat Password' ),
                'disabled'=>!$user->isAdmin()
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AtlanteIt\EmployeeBundle\Entity\Employee'
        ));
    }
}
