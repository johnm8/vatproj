<?php
namespace App\Form;

use App\Entity\CmoCalculation;
use App\Entity\CmoPrice;
use App\Entity\CmoVatRate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceCalculateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      
          ->add('cmoPrice', EntityType::class, [
            'class' => CmoPrice::class,     
            'choice_label' => 'price', 
          ])

          ->add('cmoVatRate', EntityType::class, [
            'class' => CmoVatRate::class,     
            'choice_label' => 'rate', 
          ]);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults([
        'data_class' => CmoCalculation::class
      ]);
    }
}