<?php

namespace CAM\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use CAM\UserBundle\Entity\User;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller
{
    public function indexAction() {
       	return $this->render('@CAMCore/layout.html.twig');
    }

    public function infoViewAction() {
        return $this->render('@CAMUser/info.html.twig', array('infos' => $this->getUserInfos()));
    }

    public function infoEditAction(Request $request) {
    	$user = $this->getUser();
    	$infos = $this->getUserInfos();

    	$formBuilder = $this->createFormBuilder($user)
	      ->add('age', TextType::class, array(
	      	'data' => $infos['Age'],
	      	'required' => false
	  		))
	      ->add('race', TextType::class, array(
	      	'data' => $infos['Race'],
	      	'required' => false
	  		))
	      ->add('nourriture', TextType::class, array(
	      	'data' => $infos['Nourriture'],
	      	'required' => false
	  		))
	      ->add('Modifier', SubmitType::class)
	    ;

	    $form = $formBuilder->getForm();

	    if ($request->isMethod('POST')) {
     		$form->handleRequest($request);
      		if ($form->isValid()) {
		        $em = $this->getDoctrine()->getManager();
		        $em->persist($user);
		        $em->flush();
        		return $this->redirectToRoute('info');
      		}
      	}

    	return $this->render('@CAMUser/info_edit.html.twig', array('form' => $form->createView()));
    }

    public function usersViewAction() {
        $userManager = $this->get('fos_user.user_manager');
        $userslist = $userManager->findUsers();

        return $this->render('@CAMUser/users.html.twig', array('userslist' => $userslist));
    }

    public function friendsViewAction()
    {
        $user = $this->getUser();
        $friendlist = $user->getFriends();
        return $this->render('@CAMUser/friends.html.twig', array('friendlist' => $friendlist));
    }

    public function friendsAddAction($id) {
        $userManager = $this->get('fos_user.user_manager');
        $friend = $userManager->findUserBy(array('id' => $id));
        $user = $this->getUser();
        $user->addFriend($friend);
        return $this->redirectToRoute('friends');
    }

    public function getUserInfos() {
 		$user = $this->getUser();
        $infos = array(
        	'Age' => $user->getAge(),
        	'Race' => $user->getRace(),
        	'Nourriture' => $user->getNourriture()
        );
        return $infos;
    }
}
