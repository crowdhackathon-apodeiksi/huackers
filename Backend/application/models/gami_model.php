<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Gami_model
 * sql queries to get gamifications
 *
 */
class Gami_model extends CI_Model
{

    function get_all_user_tickets($afm)
    {
        $query = "SELECT c.onomasia,c.afm,c.postal_address,c.postal_address_no,c.postal_area_description,c.postal_zip_code,c.doy_descr,t.ticket_date,t.ticket_time,t.amount,t.ticket_type FROM company as c, users as u, tickets as t
                    where
                    t.user_owner=u.user_id
                    and
                    t.referenced_company_afm=c.afm
                    and
                    u.afm= '" . $afm . "'
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


    function get_user_tickets_monthly($afm)
    {
        $month = date('m');
        $query = "SELECT c.onomasia,c.afm,c.postal_address,c.postal_address_no,c.postal_area_description,c.postal_zip_code,c.doy_descr,t.ticket_date,t.ticket_time,t.amount,t.ticket_type FROM company as c, users as u, tickets as t
                    where
                    t.user_owner=u.user_id
                    and
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

    //returns expenses per category for a specific user (totally)
    function get_total_user_costs_per_category($afm)
    {

        $query = "SELECT cost_type.type_name,sum(tickets.amount) AS totalCostPerCategory ,count(tickets.id) AS numoftickets FROM cost_type
                left join tickets
                inner join users
                ON tickets.user_owner = users.user_id
                ON tickets.ticket_type = cost_type.id
                WHERE users.afm = " . $afm . "
                GROUP BY tickets.ticket_type";


        $query_response = $this->db->query($query);
        $result = $query_response->result();

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
            return $result;
        }

    }


    function get_total_user_costs_per_category_per_month($afm, $month)
    {

        $query = "SELECT cost_type.type_name,sum(tickets.amount) AS totalCostPerCategory ,count(tickets.id) AS numoftickets FROM cost_type
                    left join tickets
                    inner join users
                    ON tickets.user_owner = users.user_id
                    ON tickets.ticket_type = cost_type.id
                    WHERE users.afm = " . $afm . "  and (substr(ticket_date, 6, 2)= " . $month . ")
                    GROUP BY tickets.ticket_type";

        $query_response = $this->db->query($query);
        $result = $query_response->result();

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
            return $result;
        }

    }

    function save_offer($afm, $ticket_count, $offer_descr)
    {
        $date=date('Y-m-d');
        $data=array(
            'bus_afm' => $afm,
            'offer_date' => $date,
            'ticket_count' => $ticket_count,
            'offer_descr' => $offer_descr,
        );

        $this->insert('offers', $data);

        if ($this->db->affected_rows() == 1) {
            return true;
        } else {
            return false;
        }

    }


    function get_total_user_tickets_count($afm)
    {
        $query = "SELECT count(*) as num, sum(t.amount) as expense FROM users as u, tickets as t
                    where
                    t.user_owner=u.user_id
                    and
                    u.afm= " . $afm;

        $query_response = $this->db->query($query);
        $result = $query_response->result();
        //log_message('info', 'query ' . var_dump($this->db->last_query()));

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
            return $result;
        }

    }

    function get_total_user_tickets_count_permonth($afm)
    {
        $query = "SELECT (substr(t.ticket_date, 6, 2)) as minas, count(*) as num, sum(t.amount) as expense FROM users as u, tickets as t
                    where
                    t.user_owner=u.user_id
                    and
                    u.afm= " . $afm . "
                    group by (substr(t.ticket_date, 6, 2))";

        $query_response = $this->db->query($query);
        $result = $query_response->result();
        //log_message('info', 'query ' . var_dump($this->db->last_query()));

        if ($query_response->num_rows() < 1) {
            return NULL;
        } else {
            return $result;
        }

    }


}
