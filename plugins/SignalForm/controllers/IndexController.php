<?php
class SignalForm_IndexController extends Omeka_Controller_Action
{    
	public function indexAction()
	{	
	    $name = isset($_POST['name']) ? $_POST['name'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';;
            $link = isset($_POST['link']) ? $_POST['link'] : '';;
            
            
            $message .= "Naam: ".$name."\nn";
            $message .= "E-mailadres: ".$email."\nn";
            $message .= "link naar object: ".WEB_ROOT.$link."\nn";
            $message .= "Tip omtrent het gestolen object: ".$_POST['message']."\nn"; 

	    $captchaObj = $this->_setupCaptcha();
	    
	    if ($this->getRequest()->isPost()) {    		
    		// If the form submission is valid, then send out the email
    		if ($this->_validateFormSubmission($captchaObj)) {
				$this->sendEmailNotification($_POST['email'], $_POST['name'], $message);
	            $this->redirect->gotoRoute(array(), 'signal_form_thankyou');
    		}
	    }	
	    
	    // Render the HTML for the captcha itself.
	    // Pass this a blank Zend_View b/c ZF forces it.
		if ($captchaObj) {
		    $captcha = $captchaObj->render(new Zend_View);
		} else {
		    $captcha = '';
		}
		
		$this->view->assign(compact('name','email','message', 'captcha'));
	}
	
	public function thankyouAction()
	{
		
	}
	
	protected function _validateFormSubmission($captcha = null)
	{
	    $valid = true;
	    $msg = $this->getRequest()->getPost('message');
	    $email = $this->getRequest()->getPost('email');
            //added by Joris
            if($this->getRequest()->getPost('link')){
                $link = $this->getRequest()->getPost('link');
            }
	    // ZF ReCaptcha ignores the 1st arg.
	    if ($captcha and !$captcha->isValid('foo', $_POST)) {
            $this->flashError('Your CAPTCHA submission was invalid, please try again.');
            $valid = false;
	    } else if (empty($msg)) {
            $this->flashError('U vergat uw tip in te vullen.');
            $valid = false;
	    }
	    
	    return $valid;
	}

    protected function _setupCaptcha()
    {
        return Omeka_Captcha::getCaptcha();
    }
	
	protected function sendEmailNotification($formEmail, $formName, $formMessage) {
		
		//notify the admin
		//use the admin email specified in the plugin configuration.
        $forwardToEmail = get_option('signal_form_forward_to_email');
        if (!empty($forwardToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('signal_form_admin_notification_email_message_header') . "\n\n" . $formMessage);
            $mail->setFrom($formEmail, $formName);
            $mail->addTo($forwardToEmail);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('signal_form_admin_notification_email_subject'));
            $mail->send();		
        }

        //notify the user who sent the message
        $replyToEmail = get_option('signal_form_reply_from_email');
        if (!empty($replyToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('signal_form_user_notification_email_message_header') . "\n\n" . $formMessage);
            $mail->setFrom($replyToEmail);
            $mail->addTo($formEmail, $formName);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('signal_form_user_notification_email_subject'));
            $mail->send();
        }
	}
}
