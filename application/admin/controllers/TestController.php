<?php

class Admin_TestController extends Digitalus_Controller_Action
{

    public function init()
    {
		parent::init();    	
    }

    function test_function()
    {
    	print "<pre>";
    	print_r('da vao test function');
    	print "</pre>";
    	exit();
    }

    public function indexAction()
    {
    	$_POST['firstname'] = '';
    	$_POST['lastname'] = '';
    	$_POST['username'] = 'ducbui';
    	$_POST['password'] = '123456';
    	$_POST['passconf'] = '12345622';
//    	$_POST['email'] = 'vietnam@yahoo.com';
    	$_POST['email'] = '';
    	
    	
    	$valid = new Digitalus_Validation($this);
    	
    	$valid->set_rules('firstname','First Name', 'trim|required');
    	$valid->set_rules('lastname','Last Name', 'trim|required');
    	$valid->set_rules('username','Username', 'trim|required');
    	$valid->set_rules('password','Password', 'trim|required|matches[passconf]');
		$valid->set_rules('passconf','Confirmation Password', 'trim|required');
//		$valid->set_rules('email','Email', 'required');
		$valid->set_rules('email','Email', 'trim|required|callback_valid_email');
    	
		
		if ($valid->run() == FALSE)
		{
			
			print "<pre>";
			print_r('co loi xay ra');
			print "</pre>";
			
			print "<pre>";
			print_r($valid->error_string);
			print_r($valid->_error_array);
			print "</pre>";
			exit();

		}
		else
		{
			print "<pre>";
			print_r('thanh cong');
			print "</pre>";
		}
		
    	print "<pre>";
    	print_r($_POST);
    	print "</pre>";
    	exit();

    }
    
    function valid_email()
    {
    	print "<pre>";
    	print_r('da chay vao trong valid_email ');
    	print "</pre>";
    	exit();

		return true;
//		if ($str == 'test')
//		{
//			$this->form_validation->set_message('valid_email', lang('_existed_notify'));
//			return FALSE;
//		}
//		else
//		{
//			return TRUE;
//		}    	
    	
    }
    
   
}