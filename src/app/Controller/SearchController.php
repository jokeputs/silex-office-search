<?php
namespace OfficeSearch\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class SearchController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     * @return mixed
     */
    public function indexAction(Request $request, Application $app)
    {
        $form = $app['service.office']->getForm();

        return $app['twig']->render('search.html.twig', array(
            'form' => $form->createView(),
            'action' => 'search'
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAction(Request $request, Application $app)
    {
        /** @var \Symfony\Component\Form\Form $form */
        $form = $app['service.office']->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $result = $app['service.office']->search($form->getData());

            $offices = array();
            foreach($result as $office) {
                array_push($offices, $office->__toString());
            }

            return $app->json(array(
                'success' => true,
                'offices' => $offices
            ));
        }

        // TODO: add better error messages
        $errors = array();
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($form->getErrors() as $error) {
            array_push($errors, $error->getMessage());
        }

        return $app->json(array(
            'success' => false,
            'errors' => $errors
        ));
    }
}