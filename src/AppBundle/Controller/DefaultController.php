<?php
/**
 * To handle Application Default requirements
 * @author Ashutosh Rai <dev.ashurai@gmail.com>
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Expenses;
use AppBundle\Form\ExpenseType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Default Controller
 */
class DefaultController extends Controller
{
    /**
     * Default page action to the application/list expenses
     * @param Request $request
     * @return type
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:Default:index.html.twig');
    }
    
    /**
     * Create and edit expenses
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function newAction(Request $request, $id = ''){
        $em = $this->getDoctrine()->getManager();
        if($id != ''){
            $expenseObj = $em->getRepository("AppBundle:Expenses")->find($id);
        }else{
            $expenseObj = new Expenses();
        }
        $form = $this->createForm(ExpenseType::class, $expenseObj);
        
        if($form->handleRequest($request)->isSubmitted() && $form->isValid()){
            $em->persist($expenseObj);
            $em->flush();
            if($id != ''){
                $this->get('session')->getFlashBag()->set('success', 'Record Successfully updated');
                return $this->redirect($this->generateUrl('homepage'));
            }else{
                $this->get('session')->getFlashBag()->set('success', 'Record added successfully');
                return $this->redirect($this->generateUrl('homepage'));
            }
        }
        
        return $this->render('AppBundle:Default:new.html.twig',
                array(
                    'form' => $form->createView(),
                    'expenseObj' => $expenseObj
                ));
    }
    
    /**
     * Return all available data to list
     * @return JsonResponse
     */
    public function listDataApiAction(){
        $em = $this->getDoctrine()->getManager();
        $listData = $em->getRepository("AppBundle:Expenses")->findAll();
        $list = array();
        $i = 0;
        foreach($listData as $data){
            $list[$i]['id'] = $data->getId();
            $list[$i]['description'] = $data->getDescription();
            $list[$i]['totalAmount'] = $data->getTotalAmount();
            $i++;
        }
        $response = json_encode($list);
        return new JsonResponse($response);
    }
}
