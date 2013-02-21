<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SignalForm
 */

// Define Constants.
define('SIGNAL_FORM_PAGE_PATH', 'signaleer/');
define('SIGNAL_FORM_CONTACT_PAGE_TITLE', 'Contact Us');
define('SIGNAL_FORM_CONTACT_PAGE_INSTRUCTIONS', 'Please send us your comments and suggestions.');
define('SIGNAL_FORM_THANKYOU_PAGE_TITLE', 'Thank You For Your Feedback');
define('SIGNAL_FORM_THANKYOU_PAGE_MESSAGE', 'We appreciate your comments and suggestions.');
define('SIGNAL_FORM_ADMIN_NOTIFICATION_EMAIL_SUBJECT', 'A User Has Contacted You');
define('SIGNAL_FORM_ADMIN_NOTIFICATION_EMAIL_MESSAGE_HEADER', 'A user has sent you the following message:');
define('SIGNAL_FORM_USER_NOTIFICATION_EMAIL_SUBJECT', 'Thank You');
define('SIGNAL_FORM_USER_NOTIFICATION_EMAIL_MESSAGE_HEADER', 'Thank you for sending us the following message:');
define('SIGNAL_FORM_ADD_TO_MAIN_NAVIGATION', 1);

// Add plugin hooks.
add_plugin_hook('install', 'signal_form_install');
add_plugin_hook('uninstall', 'signal_form_uninstall');
add_plugin_hook('define_routes', 'signal_form_define_routes');
add_plugin_hook('config_form', 'signal_form_config_form');
add_plugin_hook('config', 'signal_form_config');

// Add filters.
add_filter('public_navigation_main', 'signal_form_public_navigation_main');


function signal_form_install()
{
	set_option('signal_form_reply_from_email', get_option('administrator_email'));
	set_option('signal_form_forward_to_email', get_option('administrator_email'));	
	set_option('signal_form_admin_notification_email_subject', SIGNAL_FORM_ADMIN_NOTIFICATION_EMAIL_SUBJECT);
	set_option('signal_form_admin_notification_email_message_header', SIGNAL_FORM_ADMIN_NOTIFICATION_EMAIL_MESSAGE_HEADER);
	set_option('signal_form_user_notification_email_subject', SIGNAL_FORM_USER_NOTIFICATION_EMAIL_SUBJECT);
	set_option('signal_form_user_notification_email_message_header', SIGNAL_FORM_USER_NOTIFICATION_EMAIL_MESSAGE_HEADER);
	set_option('signal_form_contact_page_title', SIGNAL_FORM_CONTACT_PAGE_TITLE);
	set_option('signal_form_contact_page_instructions', SIGNAL_FORM_CONTACT_PAGE_INSTRUCTIONS);
	set_option('signal_form_thankyou_page_title', SIGNAL_FORM_THANKYOU_PAGE_TITLE);
	set_option('signal_form_thankyou_page_message', SIGNAL_FORM_THANKYOU_PAGE_MESSAGE);	
	set_option('signal_form_add_to_main_navigation', SIGNAL_FORM_ADD_TO_MAIN_NAVIGATION);	
	
}

function signal_form_uninstall()
{
	delete_option('signal_form_reply_from_email');
	delete_option('signal_form_forward_to_email');	
	delete_option('signal_form_admin_notification_email_subject');
	delete_option('signal_form_admin_notification_email_message_header');
	delete_option('signal_form_user_notification_email_subject');
	delete_option('signal_form_user_notification_email_message_header');
	delete_option('signal_form_contact_page_title');
	delete_option('signal_form_contact_page_instructions');
	delete_option('signal_form_thankyou_page_title');
	delete_option('signal_form_add_to_main_navigation');	
}

/**
 * Adds 2 routes for the form and the thank you page.
 **/
function signal_form_define_routes($router)
{   
	$router->addRoute(
	    'signal_form_form', 
	    new Zend_Controller_Router_Route(
	        SIGNAL_FORM_PAGE_PATH, 
	        array('module'       => 'signal-form')
	    )
	);
		
	$router->addRoute(
	    'signal_form_thankyou', 
	    new Zend_Controller_Router_Route(
	        SIGNAL_FORM_PAGE_PATH . 'thankyou', 
	        array(
	            'module'       => 'signal-formm', 
	            'controller'   => 'index', 
	            'action'       => 'thankyou', 
	        )
	    )
	);

}

function signal_form_config_form() 
{
	include 'config_form.php';
}

function signal_form_config()
{
	set_option('signal_form_reply_from_email', $_POST['reply_from_email']);
	set_option('signal_form_forward_to_email', $_POST['forward_to_email']);	
	set_option('signal_form_admin_notification_email_subject', $_POST['admin_notification_email_subject']);
	set_option('signal_form_admin_notification_email_message_header', $_POST['admin_notification_email_message_header']);
	set_option('signal_form_user_notification_email_subject', $_POST['user_notification_email_subject']);
	set_option('signal_form_user_notification_email_message_header', $_POST['user_notification_email_message_header']);
	set_option('signal_form_contact_page_title', $_POST['contact_page_title']);
	set_option('signal_form_contact_page_instructions',$_POST['contact_page_instructions']);
	set_option('signal_form_thankyou_page_title', $_POST['thankyou_page_title']);
	set_option('signal_form_thankyou_page_message', $_POST['thankyou_page_message']);
	set_option('signal_form_add_to_main_navigation', $_POST['add_to_main_navigation']);
}

function signal_form_public_navigation_main($nav)
{
	$contact_title = get_option('signal_form_contact_page_title');
	$contact_add_to_navigation = get_option('signal_form_add_to_main_navigation');
	if ($contact_add_to_navigation) {
	    $nav[$contact_title] = uri(array(), 'signal_form_form');
	}

    return $nav;
}