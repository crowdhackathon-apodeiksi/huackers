<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package        CodeIgniter
 * @subpackage    Rest Server
 * @category    Controller
 * @author        Phil Sturgeon
 * @link        http://philsturgeon.co.uk/code/
 */

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Business_api extends REST_Controller
{
    function __construct()
    {
        // Construct our parent class
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['userregister_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key

    }

    function saveoffer_post()
    {
        if ((!$this->post('email') || !$this->post('afm')) || (!$this->post('password'))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


    }

    function offerchange_post()
    {
        if (!$this->post('afm') || !$this->post('id') || !$this->post('status')) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        if ($this->post('status') == 'on') {
            $this->business_model->set_active_offer($this->post('afm'), $this->post('id'));
            $message = array('success' => 'true', 'message' => 'changed to active');
            $this->response($message, 200);

        } elseif ($this->post('status') == 'off') {
            $this->business_model->set_inactive_offer($this->post('afm'), $this->post('id'));
            $message = array('success' => 'true', 'message' => 'changed to inactive');
            $this->response($message, 200);

        }


    }

    function businesslogin_post()
    {
        if ((!$this->post('email') || (!$this->post('password')))) {
            $this->response(NULL, 400);
        }


        $email = $this->post('email');
        log_message('info', 'email is ' . $email);

        // Remove all illegal characters from email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);


        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            log_message('info', "$email is a valid email address");
        } else {
            log_message('info', "$email is not a valid email address");
            $message = array('error' => 'Not accepted email', 'success' => 'false');
            $this->response($message, 400);
        }

        $api_key = $this->user_model->user_exists($this->post('email'), $this->post('password'));
        $afm = $this->user_model->get_afm_from_api_key($api_key);

        if (!$api_key) {
            $message = array('message' => 'Authentication failure!', 'success' => 'false');
            $this->response($message, 401);
        }

        if ($this->user_model->is_business($afm)) {
            $message = array('api_key' => $api_key, 'afm' => $afm, 'is_compamy' => 'true', 'success' => 'true');

            $this->response($message, 200); // 200 being the HTTP response code
        } else {

            $message = array('message' => 'not a company', 'success' => 'false');

            $this->response($message, 200); // 200 being the HTTP response code
        }
    }

    function companyticketsnumpermonth_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key')))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        if ($afm == $this->get('afm')) {
            $tickets = $this->business_model->get_company_tickets_num_per_month($this->get('afm'));
            if (is_null($tickets)) {
                $message = array('error' => 'no results', 'success' => 'false');
                $this->response($message, 400);
            } else {
                $message = array('tickets' => $tickets, 'success' => 'true');
                $this->response($message, 200);
            }
        } else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }

    function tickets_open_data_get()
    {

        $tickets = $this->business_model->all_tickets_data();

        if (is_null($tickets)) {
            $message = array('error' => 'no results', 'success' => 'false');
            $this->response($message, 400);
        } else {
            $message = array('tickets' => $tickets, 'success' => 'true');
            $this->response($message, 200);
        }


    }


}