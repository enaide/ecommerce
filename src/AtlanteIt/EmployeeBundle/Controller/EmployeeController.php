<?php

namespace AtlanteIt\EmployeeBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AtlanteIt\EmployeeBundle\Entity\Employee;
use AtlanteIt\EmployeeBundle\Form\EmployeeType;

/**
 * Employee controller.
 *
 * @Route("/employee")
 */
class EmployeeController extends Controller
{

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils' );
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(
            'EmployeeBundle:security:login.html.twig' ,
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }
    /**
     * Lists all Employee entities.
     *
     * @Route("/", name="employee_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT e FROM EmployeeBundle:Employee e ORDER BY e.id DESC ";
        $employees = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $employees, /* fuente de los datos*/
            $request->query->get('page', 1)/*número de página*/,
            10/*lámite de resultados por página*/
        );

        $this->get('session')->getFlashBag()->set(
            'error',
            array(
                'title' => "Operación Invalida",
                'message' => "Existen otros datos que dependen del dato en cuestión"
            )
        );

        $this->addFlash('My titulo', 'My mensaje');

        $deleteFormAjax = $this->createCustomForm(':EMPLOYEE_ID', 'DELETE', 'employee_delete');
        return $this->render('EmployeeBundle:employee:index.html.twig', array(
            'employees' => $pagination,
            'deleteFormAjax' => $deleteFormAjax->createView()
        ));
    }

    /**
     * Creates a new Employee entity.
     *
     * @Route("/new", name="employee_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $employee = new Employee();
        $form = $this->createForm('AtlanteIt\EmployeeBundle\Form\EmployeeType', $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();
            $passwordContraints = new Assert\NotBlank();
            $errorList = $this->get('validator')->validate($plainPassword,$passwordContraints);

            if(count($errorList) == 0){
                $encoded = $this->get('security.password_encoder' )->encodePassword($employee, $plainPassword);
                $employee->setPassword($encoded);

                $em = $this->getDoctrine()->getManager();
                $em->persist($employee);
                $em->flush();


                $this->get('session')->getFlashBag()->set(
                    'info',
                    array(
                        'title' => "Nuevo Usuario",
                        'message' => "El Usuario se adicionó correctamente"
                    )
                );

                //Debug::dump(json_encode($this->get('session')->getFlashBag()->all()));
                //exit;
                return $this->redirectToRoute('employee_index');
            }
            else{
                $errorMessage = new FormError($errorList[0]->getMessage());
                $form->get('plainPassword')->addError($errorMessage);
            }

        }

        return $this->render('EmployeeBundle:employee:new.html.twig', array(
            'employee' => $employee,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Employee entity.
     *
     * @Route("/{id}", name="employee_show")
     * @Method("GET")
     */
    public function showAction(Employee $employee)
    {
        $deleteForm = $this->createDeleteForm($employee);

        return $this->render('EmployeeBundle:employee:show.html.twig', array(
            'employee' => $employee,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Employee entity.
     *
     * @Route("/{id}/edit", name="employee_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Employee $employee)
    {
        $deleteForm = $this->createDeleteForm($employee);
        $editForm = $this->createForm('AtlanteIt\EmployeeBundle\Form\EmployeeType', $employee);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $plainPassword = $editForm->get('plainPassword')->getData();

            if(!empty($plainPassword)){
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($employee,$plainPassword);
                $employee->setPassword($encoded);
            }
            else{
                $recoverPass = $this->recoverPass($employee);
                $employee->setPassword($recoverPass[0]['password']);
            }
            $em = $this->getDoctrine()->getManager();
            #$em->persist($employee);
            $em->flush();

            return $this->redirectToRoute('employee_edit', array('id' => $employee->getId()));
        }


        return $this->render('EmployeeBundle:employee:edit.html.twig', array(

            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Employee entity.
     *
     * @Route("/{id}", name="employee_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Employee $employee)
    {
        $form = $this->createCustomForm($employee->getId(), 'DELETE', 'employee_delete');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            if($request->isXmlHttpRequest()){

                $data = $this->deleteEmployee($employee,$em);
                //return new Response(json_encode($data), 200, array('Content-Type' => 'application/json' ));
                return new JsonResponse($data);
            }

            $data = $this->deleteEmployee($employee, $em);
        }

        return $this->redirectToRoute('employee_index');
    }

    /**
     * Creates a form to delete a Employee entity.
     *
     * @param Employee $employee The Employee entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Employee $employee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('employee_delete', array('id' => $employee->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    private function recoverPass(Employee $employee){

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT e.password
              FROM EmployeeBundle:Employee e
              WHERE e.id = :id'
        )->setParameter('id', $employee->getId());

        $currentPass = $query->getResult();

        return $currentPass;
    }

    private function createCustomForm($id, $method, $route){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route,array('id' => $id)))
            ->setMethod($method)
            ->getForm();
    }

    private function deleteEmployee(Employee $employee, $em){
        try
        {
            $em->remove($employee);
            $em->flush();

            $remove = 1;
        }
        catch (\Exception $e){
            $remove = 0;
        }

        return array('removed' => $remove);
    }
}
