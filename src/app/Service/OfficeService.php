<?php
namespace OfficeSearch\Service;

use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

class OfficeService
{
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;

    /**
     * @var \OfficeSearch\Repository\OfficeRepository
     */
    protected $repository;

    /**
     * @param \Silex\Application $app
     */
    public function __construct(Application $app)
    {
        $this->formFactory = $app['form.factory'];
        $this->repository = $app['repository.office'];
    }

    /**
     * @param array $data
     * @return array
     */
    public function search($data)
    {
        $filters = array();
        $filters[] = $data['isOpenInWeekends'] ? 'isOpenInWeekends' : null;
        $filters[] = $data['hasSupportDesk'] ? 'hasSupportDesk' : null;

        $result = $this->repository->findByLocation(
            (float) $data['latitude'],
            (float) $data['longitude'],
            $data['range'],
            $filters
        );

        return $result;
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        if (!$this->form) {
            $this->form = $this->formFactory->createNamedBuilder('search', 'form')
                ->add('query', 'text', array(
                    'label' => 'Show offices in the neighborhood of',
                    'constraints' => new Assert\NotBlank()
                ))
                ->add('isOpenInWeekends', 'checkbox', array(
                    'label' => 'Open in weekends',
                    'required'  => false
                ))
                ->add('hasSupportDesk', 'checkbox', array(
                    'label' => 'With support desk',
                    'required'  => false
                ))
                ->add('range', 'choice', array(
                    'choices' => array(
                        5  => '5 km',
                        10 => '10 km',
                        20 => '20 km',
                    ),
                    'data' => 10,
                    'label' => 'Range',
                    'empty_value' => false,
                    'constraints' => new Assert\Choice(array(5, 10, 20)),
                ))
                ->add('latitude', 'hidden', array(
                    'constraints' => new Assert\NotNull()
                ))
                ->add('longitude', 'hidden', array(
                    'constraints' => new Assert\NotNull()
                ))
                ->add('submit', 'submit', array(
                    'label' => 'Search',
                ))
                ->getForm();
        }

        return $this->form;
    }
}