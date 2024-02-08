<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Reservations;
use App\Repository\ReservationsRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\LivreRepository;
use App\Entity\Livre;

class AddEmpruntResa extends AbstractType {
    public function __construct(
        private ReservationsRepository $rr
    ) { }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adherent', EntityType::class, [
                'class' => Adherent::class,
                'placeholder' => "Choisir un adhérent",
            ]);
        
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
    
                // Vérifier si le formulaire a été soumis et si le champ adherent a une valeur
                if ($form->isSubmitted() && $data->getAdherent() !== null) {
                    $choices = $this->rr->findBy(['adherent' => $data->getAdherent()]);
                    // $form->add('reservation', EntityType::class, [
                    //     'class' => Reservations::class,
                    //     'choices' => $choices,
                    //     'placeholder' => 'Choisir une réservation',
                    // ]);
                    $form->add('livre', EntityType::class, [
                        'class' => Livre::class,
                        'query_builder' => function (LivreRepository $lr): QueryBuilder {
                            return $lr->livreDispo();
                        },
                        // 'choice_label' => 'titre',
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}