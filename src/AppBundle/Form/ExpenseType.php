<?php

/**
 * To handle Expense form object
 * @author Ashutosh Rai <dev.ashurai@gmail.com>
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Expenses;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * ExpenseType class
 */
class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('description', TextareaType::class, [
                    'block_name' => 'form_row',
                    'attr' => ['class' => 'form-group', 'autofocus' => true],
                    'required' => true,
                    'label' => 'Expense Description',
                    'constraints' => [
                        new Length(['max' => 512, 'maxMessage' => 'Max 10 digits are allowed for amount'])
                    ]
                ])
                ->add('totalAmount', TextType::class, array(
                    'block_name' => 'form_row',
                    'label' => 'Total Amount', //trans
                    'attr' => ['class' => 'minimal', 'autofocus' => true, 'placeholder' => 'Total Amount'],
                    'required' => true,
                    'constraints' => [
                        new Length(['max' => 10, 'maxMessage' => 'Max 10 digits are allowed for amount']),
                        new Regex(['pattern' => '/[0-9]/', 'message' => 'only no are allowed for amount'])
                    ]
                ))
            ;

        // Manage submit label
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (null === $data) {
                return;
            }
            $form->add('save', SubmitType::class, [
                        'label' => $data->getId() ? 'Update' : 'Create',
                        'attr' => [
                            'class' => 'pull-right btn btn-primary margin-top-20',
                        ],
                    ]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Expenses::class,
            'cascade_validation' => true,
            'error_bubbling' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getName() {
        return 'expense_form';
    }
}


