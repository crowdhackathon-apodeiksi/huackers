<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    function get_user_by_id($id)
    {
        $query = $this->db->get_where('users', array('user_id' => $id));
        if ($query->num_rows() != 1) {
            return NULL;
        } else {
            return $query->result();
        }
    }

    function get_afm_from_user_id($id)
    {
        $this->db->select('afm');
        $query = $this->db->get_where('users', array('user_id' => $id));

        if ($query->num_rows() != 1) {
            return NULL;
        } else {
            return $query->result();
        }
    }

    function get_user_by_afm($afm)
    {
        $query = $this->db->get_where('users', array('afm' => $afm));
        if ($query->num_rows() != 1) {
            return NULL;
        } else {
            return $query->result();
        }
    }

    function get_api_key_from_afm($afm)
    {
        $query = $this->db->get_where('login_keys', array('afm_fk' => $afm));
        //log_message('info', "db num rows  = " . print_r($query));
        if ($query->num_rows() != 1) {
            return NULL;
        } else {
            $api_key = $query->result()[0]->api_key;
            return $api_key;
            //log_message('info', "db result  = " . print_r($api_key));

        }
    }

    function get_email_from_afm($afm)
    {
        $query = $this->db->get_where('users', array('afm' => $afm));
        //log_message('info', "db num rows  = " . print_r($query));
        if ($query->num_rows() != 1) {
            return NULL;
        } else {
            $email = $query->result()[0]->email;
            return $email;

            //log_message('info', "db result  = " . print_r($api_key));

        }
    }

    function get_user_id_from_user_afm($afm)
    {
        $this->db->select('user_id');
        $query = $this->db->get_where('users', array('afm' => $afm));
        if ($query->num_rows() != 1) {
            return NULL;
        } else {
            return $query->result()[0]->user_id;
           log_message('info', 'user_id returned ' .var_dump($query->result()));

        }
    }

    function register_user($email, $afm = "", $password)
    {
        $data = array(
            'email' => $email,
            'afm' => $afm,
            'password' => md5($password),
        );

        try {
            $this->db->trans_start();
            $this->db->insert('users', $data);
            $insert_id = $this->db->insert_id();
            if (!$insert_id) throw new Exception($this->db->_error_message(), $this->db->_error_number());
            $this->db->trans_complete();
            if ($insert_id > 0) {
                return $insert_id;
            }

        } catch (Exception $e) {
            log_message('error', sprintf('%s : %s : DB transaction failed. Error no: %s, Error msg:%s, Last query: %s', __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage(), print_r($this->db->last_query(), TRUE)));
            return false;
        }

    }


    function get_users()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    function user_exists($email, $password)
    {
        $this->db->select('afm');
        $query = $this->db->get_where('users', array('email' => $email, 'password' => md5($password)));
        if ($query->num_rows() != 1) {
            return false;
        } else {
            $afm = $query->result()[0]->afm;
            //log_message('info', "db result  = " . print_r($afm));

            $api_key = $this->get_api_key_from_afm($afm);
            return $api_key;
        }
    }

    function get_afm_from_api_key($api_key)
    {
        $this->db->select('afm_fk');
        $query = $this->db->get_where('login_keys', array('api_key' => $api_key));

        if ($query->num_rows() != 1) {
            return false;
        } else {
            $afm = $query->result()[0]->afm_fk;
            return $afm;
        }
    }

    function commercial_user_flag($afm){
        $data=array(
          'is_company' => 1
        );
        $this->db->where('afm', $afm);
        $this->db->update('users', $data);

    }

    function is_business($afm) {

        $this->db->where('is_company', '1');
        $this->db->where('afm', $afm);
        $query=$this->db->get('users');


        if ($query->num_rows() == 1) {
            return true;
        } else {

            return false;
        }

    }


}
