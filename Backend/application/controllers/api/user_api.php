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

class User_api extends REST_Controller
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

    public function index()
    {
        echo "Hello";
    }

    function user_get()
    {
        if ((!$this->get('afm') && !$this->get('id'))) {
            $this->response(NULL, 400);
        }

        if ($this->get('afm')) {
            $user = $this->user_model->get_user_by_afm($this->get('afm'));
        }

        if ($this->get('id')) {
            $user = $this->user_model->get_user_by_id($this->get('id'));
        }


        if ($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

    /**
     * Registers a user
     * input: email, afm, password
     * checks afm and email for validity
     * output: api key
     */
    function userregister_post()
    {
        if ((!$this->post('email') || !$this->post('afm')) || (!$this->post('password'))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        if ((strlen($this->post('afm')) != 9) || (!is_numeric(($this->post('afm'))))) {
            $message = array('error' => 'Not accepted AFM', 'success' => 'false');
            $this->response($message, 400);
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

        //@todo if afm is valid
        if (!check_afm($this->post('afm'))) {
            $message = array('error' => 'Not valid AFM', 'success' => 'false');
            $this->response($message, 400);
        }

        //start transaction in order to register user and create api key
        //$this->db->trans_strict(FALSE);
        //$this->db->trans_start();

        $user_id = $this->user_model->register_user($email, $this->post('afm'), $this->post('password'));

        if (!$user_id) {
            $message = array('error' => 'DB error, user not inserted!', 'success' => 'false');
            $this->response($message, 409);
        }

        log_message('info', 'db returned userid ' . $user_id);
        $api_key = create_api_key($this->post('afm'));
        //get afm by user_id
        //$api_key=$this->user_model->get_api_key_from_afm($this->post('afm'));
        //get
        //$this->db->trans_complete();


        $message = array('id' => $user_id, 'afm' => $this->post('afm'), 'email' => $this->post('email'), 'api_key' => $api_key, 'message' => 'user registered!', 'success' => 'true');

        $this->response($message, 200); // 200 being the HTTP response code
    }

    /**
     * returns api key and afm of a registered user
     */
    function userlogin_post()
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
            $message = array('api_key' => $api_key, 'afm' => $afm, 'is_company' => 'true','success' => 'true');

            $this->response($message, 200); // 200 being the HTTP response code
        }

        $message = array('api_key' => $api_key, 'afm' => $afm,  'success' => 'true!');

        $this->response($message, 200); // 200 being the HTTP response code
    }

    function userticket_post()
    {
        if (((!$this->post('afm')) || (!$this->post('api_key')) ||
            (!$this->post('bus_afm')) || (!$this->post('am')) ||
            (!$this->post('date'))  ||
            (!$this->post('category')) || (!$this->post('total')) ||
            (!$this->post('aa'))|| (!$this->post('lat_lon'))))
        {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }

        var_dump($this->post('api_key'));

        $afm = $this->user_model->get_afm_from_api_key(trim($this->post('api_key')));

        var_dump($afm);

        if ($afm == $this->post('afm')) {
            $user_id=$this->user_model->get_user_id_from_user_afm($afm);

            $business_afm=$this->business_model->check_business_exists_afm($this->post('bus_afm'));

            //var_dump($business_afm);
            if ($business_afm) {
                //save data to db
                if ($this->ticket_model->save_ticket($user_id, $business_afm, $this->post('am'),
                    $this->post('aa'), $this->post('date'), $this->post('time'), $this->post('category'),
                    $this->post('total'), $this->post('lat_lon')))
                {
                    $message = array('success' => 'true', 'message' => 'ticket saved and company existed');
                    $this->response($message, 200);

                }
                else {
                    $message = array('success' => 'false', 'message' => 'ticket not saved and company existed');
                    $this->response($message, 400);
                }

            }
            else
            {
                //call gsis to get business data
                $returned_data=get_from_gsis_ours($this->post('bus_afm'));

                $returned_afm = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mafm'];

                if(!$returned_afm){
                    $message = array('success' => 'false', 'message' => 'company not found in gsis');
                    $this->response($message, 400);
                }


                $onomasia = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['monomasia'];
                $postal_address = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mpostalAddress'];
                $postal_address_no = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mpostalAddressNo'];
                $postal_address_no = trim($postal_address_no);
                $postal_area_description = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mpostalAreaDescription'];
                $postal_zip_code = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mpostalZipCode'];
                $doy = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mdoy'];
                $doy_descr = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mdoyDescr'];
                $firm_flag_descr = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mfirmFlagDescr'];
                $firm_flag_descr = trim($firm_flag_descr);

                if ($this->business_model->save_business($afm, $onomasia, $postal_address, $postal_address_no, $postal_area_description, $postal_zip_code, $doy, $doy_descr, $firm_flag_descr))
                {

                    if ($this->ticket_model->save_ticket($user_id, $this->post('bus_afm'), $this->post('am'),
                        $this->post('aa'), $this->post('date'), $this->post('time'), $this->post('category'), $this->post('total')))
                    {
                        $message = array('success' => 'true', 'message' => 'ticket saved and company inserted') ;
                        $this->response($message, 200);

                    }
                    else {
                        $message = array('success' => 'false', 'message' => 'ticket not saved and company inserted');
                        $this->response($message, 400);
                    }



                }
                else {
                    $message = array('error' => 'business not inserted', 'success' => 'false');

                    $this->response($message, 400);

                }



            }



        }
        else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }

    /**
     * Returns a zip file of all images of a user
     * input: afm and api_key
     * output: a zip file named with {user afm}.zip
     */
    function receipts_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key')))) {
            $this->response(NULL, 400);
        }
        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        var_dump($afm);
        if ($afm == $this->get('afm')) {

            shell_exec('/var/www/html/create_zip.sh ' . $afm);

            $message = array('message' => 'Υou will get your data soon!', 'url' => 'http://83.212.118.7/' . $afm . '.zip', 'success' => 'true');

            $this->response($message, 200);
        } else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }


    function users_get()
    {
        //$users = $this->some_model->getSomething( $this->get('limit') );
        $users = array(
            array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
            array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
            3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
        );

        if ($users) {
            $this->response($users, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }


    /**
     * Gets expenses from all tickets of a user grouped by categories
     * input: afm, api_key
     * output: key-value pair of {category_name: sum}
     */
    function cost_per_category_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key')))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        if ($afm == $this->get('afm')) {
            $expenses = $this->gami_model->get_total_user_costs_per_category($afm);
            if (is_null($expenses)) {
                $message = array('error' => 'no results', 'success' => 'false');
                $this->response($message, 400);
            } else {
                $message = array('costs' => $expenses, 'success' => 'true');
                $this->response($message, 200);
            }
        }
        else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }


    function allusertickets_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key')))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        if ($afm == $this->get('afm')) {
            $tickets = $this->gami_model->get_all_user_tickets(trim($afm));
            if (is_null($tickets)) {
                $message = array('error' => 'no results', 'success' => 'false');
                $this->response($message, 400);
            } else {
                $message = array('tickets' => $tickets, 'success' => 'true');
                $this->response($message, 200);
            }
        }
        else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }


    function alluserticketscurrentmonth_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key')))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        if ($afm == $this->get('afm')) {
            $tickets = $this->gami_model->get_user_tickets_monthly(trim($afm));
            if (is_null($tickets)) {
                $message = array('error' => 'no results', 'success' => 'false');
                $this->response($message, 400);
            } else {
                $message = array('tickets' => $tickets, 'success' => 'true');
                $this->response($message, 200);
            }
        }
        else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }


    /**
     * Gets expenses of a user grouped by categories for a specific month
     * input: afm, api_key
     * output: key-value pair of {category_name: sum}
     */
    function cost_per_category_per_month_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key'))))
        {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }

        $month=date('m');

        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        if ($afm == $this->get('afm')) {
            $expenses = $this->gami_model->get_total_user_costs_per_category_per_month($afm,$month);
            if (is_null($expenses)) {
                $message = array('error' => 'no results', 'success' => 'false');
                $this->response($message, 400);
            } else {
                $message = array('costs' => $expenses, 'success' => 'true');
                $this->response($message, 200);
            }
        }
        else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }

    function active_offers_get(){
        $offers=$this->business_model->get_active_offers();

        if ($offers) {
            $message = array('message' => 'returned offers', 'success' => 'true');
            $this->response($offers, 200);
        }
        else{
            $message = array('message' => 'not returned offers', 'success' => 'false');
            $this->response($message, 204);
        }

    }

    function alluserticketscount_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key')))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        if ($afm == $this->get('afm')) {
            $tickets = $this->gami_model->get_total_user_tickets_count($this->get('afm'));
            if (is_null($tickets)) {
                $message = array('error' => 'no results', 'success' => 'false');
                $this->response($message, 400);
            } else {
                $message = array('tickets' => $tickets, 'success' => 'true');
                $this->response($message, 200);
            }
        }
        else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }


    function alluserticketscountpermonth_get()
    {
        if ((!$this->get('afm') || (!$this->get('api_key')))) {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


        $afm = $this->user_model->get_afm_from_api_key($this->get('api_key'));
        if ($afm == $this->get('afm')) {
            $tickets = $this->gami_model->get_total_user_tickets_count_permonth($this->get('afm'));
            if (is_null($tickets)) {
                $message = array('error' => 'no results', 'success' => 'false');
                $this->response($message, 400);
            } else {
                $message = array('tickets' => $tickets, 'success' => 'true');
                $this->response($message, 200);
            }
        }
        else {
            $message = array('error' => 'Not authorized', 'success' => 'false');

            $this->response($message, 401);
        }
    }








}