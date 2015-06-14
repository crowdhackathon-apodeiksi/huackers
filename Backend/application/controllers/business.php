<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Business extends CI_Controller
{

    public function index(){
        //@todo check if user is logged inr or redirect to login form
        $this->load->view('/business/header');
        $this->load->view('/business/login');
        $this->load->view('/business/footer');
    }

    function login()
    {
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('/business/header');
            $this->load->view('/business/login');
            $this->load->view('/business/footer');
        }
        else
        {
            if ($this->member_auth->login($this->input->post('email'), $this->input->post('password')))
            {

                $this->load->view('/others/getcoords');
                //redirect('member');
            }
            else
            {
                $this->load->view('others/header');
                $this->load->view('member/member_login');
                $this->load->view('others/footer');
            }
        }
    }

    function profile() //show company profile
    {
        if($this->business_auth->is_logged_in())
        {
            //$data['member'] = $this->business_model->get_by_id($this->member_auth->get_member_id());
            $this->load->view('/business/header');
            $this->load->view('/business/profile');
            $this->load->view('/business/footer');
        }
        else
        {
            redirect('smo404');
        }
    }

    function register() {

        $this->form_validation->set_rules('afm', 'AFM', 'trim|required|exact_length[9]|callback_afm_check');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('re-password', 'Password', 'trim|required|min_length[4]|matches[password]');



        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('/business/header');
            $this->load->view('/business/register');
            $this->load->view('/business/footer');
        }
        else {
            $afm = $this->input->post('afm');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $taxis_uname = $this->input->post('taxis-uname');
            $taxis_password = $this->input->post('taxis-password');

            $returned_data = get_from_gsis_other($afm, $taxis_uname, $taxis_password);
            $returned_afm = $returned_data['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mafm'];

            if (!$returned_afm) {

                $data['message'] = 'Λάθος στοιχεία για το taxisnet';

                $this->load->view('/business/header');
                $this->load->view('/business/login', $data);
                $this->load->view('/business/footer');

            } else {


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


                $returned_afm = trim($returned_afm);
                //var_dump($result) ;


                if ($returned_afm == $afm) {
                    //write to db, after you get the data from gsis
                    $business_afm = $this->business_model->check_business_exists_afm($afm);

                    //var_dump($business_afm);
                    if ($business_afm) {
                        //business exists
                        //check if user exists
                        if ($this->user_model->get_user_by_afm($business_afm)) {
                            //update flag and return email
                            $this->user_model->commercial_user_flag($business_afm);
                            //return mail
                            $returned_email = $this->user_model->get_email_from_afm($afm);
                            $data['message'] = 'Έχτε ήδη κάνει εγγραφή και το email που έχετε καταχωρησει είναι το ' . $returned_email;

                            $this->load->view('/business/header');
                            $this->load->view('/business/login', $data);
                            $this->load->view('/business/footer');


                        } else {
                            //insert user with flag is_company = 1
                            $this->user_model->register_user($email, $afm, $password);
                            $this->user_model->commercial_user_flag($afm);
                            $returned_email = $this->user_model->get_email_from_afm($afm);
                            $api_key = create_api_key($afm);

                            $data['message'] = 'Ηε εγγραφή ήταν επιτυχής.
                        Το email που έχετε καταχωρησει είναι το ' . $returned_email .
                                ' To api key σας είναι το ' . $api_key;

                            $this->load->view('/business/header');
                            $this->load->view('/business/login', $data);
                            $this->load->view('/business/footer');


                        }
                    } else {
                        $this->business_model->save_business($afm, $onomasia, $postal_address, $postal_address_no, $postal_area_description, $postal_zip_code, $doy, $doy_descr, $firm_flag_descr);

                        if ($this->user_model->get_user_by_afm($afm)) {
                            //update flag and return email
                            $this->user_model->commercial_user_flag($afm);
                            //return mail


                        } else {
                            //insert user with flag is_company = 1
                            echo 'registering user';
                            echo $this->user_model->register_user($email, $afm, $password);
                            $api_key = create_api_key($afm);

                            $this->user_model->commercial_user_flag($afm);


                        }
                    }

                } else {
                    echo 'Wrong afm, username or password for gsis';
                }


            }
        }

    }

    public function afm_check($afm)
    {
        if (!check_afm($afm))
        {
            $this->form_validation->set_message('afm_check', 'Παρακαλώ εισάγετε έγκυρο ΑΦΜ');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }


    function add_offer()
    {
        if($this->member_auth->is_logged_in())
        {
            $ticket_count=$this->input->post('ticket_count');
            $offer_descr=$this->input->post('offer_descr');
            $this->gami_model->save_offer($afm, $ticket_count, $offer_descr);
        }
        else
        {
            redirect('smo404');
        }
    }

}