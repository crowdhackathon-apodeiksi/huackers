<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Business_model extends CI_Model
{
    function get_business_by_user($email, $password)
    {
        //@todo get business from user
        $query = "SELECT c.onomasia,c.afm  FROM company as c, users as u
                    WHERE
                    t.referenced_company_afm=c.afm
                    and
                    u.afm= '" . $afm . "'
                    and
                    (substr(t.ticket_date, 6, 2))= " . $month . "
                    order by t.ticket_date desc";

        $query_response = $this->db->query($query);
        $result = $query_response->result();
        //log_message('info', 'query ' . var_dump($this->db->last_query()));

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
            return $result;
        }


    }

    function get_business_data($bus_id)
    {
        //@todo get stored info about the business
    }

    function check_business_exists_afm($afm){
        $this->db->select('afm');
        $query = $this->db->get_where('company', array('afm' => $afm));

        if ($query->num_rows() != 1) {
            return false;
        } else {
            $afm = $query->result()[0]->afm;
            //log_message('info', 'last query ' . var_dump($this->db->last_query()));
            //var_dump($afm);
            return $afm;
        }
    }


    function save_business($afm, $onomasia, $postal_address, $postal_addres_no, $postal_area_description, $postal_zip_code, $doy, $doy_descr, $firm_act_descr) {

       $data=array(
           'afm' => $afm,
            'onomasia' => $onomasia,
            'postal_address'=> $postal_address,
            'postal_address_no' => $postal_addres_no,
            'postal_area_description' => $postal_area_description,
            'postal_zip_code'=>$postal_zip_code,
            'doy' =>$doy,
            'doy_descr' =>$doy_descr,
            'firm_flag_descr' =>$firm_act_descr
       );
       $this->db->insert('company', $data);

        if ($this->db->affected_rows() == 1) {
            return true;
        } else {
            return false;

        }
       //log_message('info', $this->db->last_query());
    }

    function update_company_owner_flag($afm) {
        $data = array(
            'is_company' => 1,
        );

        $this->db->where('afm', $afm);
        $this->db->update('users', $data);
        log_message('info', $this->db->last_query());
    }

    function get_active_offers()
    {
        $query = "SELECT c.onomasia , o.offer_descr, o.ticket_count, o.offer_date, o.id
                  FROM offers AS o ,company as c
                  WHERE o.active=1
                  AND c.afm = o.bus_afm";

        $query_response = $this->db->query($query);
        $result = $query_response->result();
        //log_message('info', 'query ' . var_dump($this->db->last_query()));

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
            return $result;
        }

    }

    function set_active_offer($afm, $id){
        $data=array(
            'active' => 1
        );
        $this->db->where('bus_afm', $afm);
        $this->db->where('id', $id);
        $this->db->update('offers', $data);

    }

    function set_inactive_offer($afm,$id){
        $data=array(
            'active' => 0
        );
        $this->db->where('bus_afm', $afm);
        $this->db->where('id', $id);
        $this->db->update('offers', $data);

    }

    function get_company_tickets_num_per_month($afm)
    {

        $query = "SELECT substr(tickets.ticket_date, 6,2) as month_num, count(*) as num_of_tickets, sum(tickets.amount) as total_cost FROM tickets
				WHERE tickets.referenced_company_afm= '" . $afm . "'
				GROUP BY (substr(tickets.ticket_date, 6,2))";

        $query_response = $this->db->query($query);
        $result = $query_response->result();

        //log_message('info', var_dump($this->db->last_query()));

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
            return $result;
        }
    }

    function all_tickets_data()
    {

        $query = "select t.user_owner as xristis ,ct.type_name as katigoria_exodwn,
        t.referenced_company_afm as company_afm, t.amount as poson_euro, t.ticket_date as imerominia,
         t.ticket_time as wra
        from tickets as t, cost_type as ct where t.ticket_type = ct.id;";

        $query_response = $this->db->query($query);
        $result = $query_response->result();

        //log_message('info', var_dump($this->db->last_query()));

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
           // var_dump($result);
            return $result;
        }
    }

}
