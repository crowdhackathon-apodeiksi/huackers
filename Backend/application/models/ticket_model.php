<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model
{
    function save_ticket($user_id, $bus_afm, $am, $aa, $date, $time="", $category, $total, $lat_lon="37.9667 23.7167")
    {
        if (!$this->ticket_exists($bus_afm, $date, $aa, $am)) {
           /* $data = array(
                'user_owner' => $user_id,
                'ticket_type' => $category,
                'referenced_company_afm' => $bus_afm,
                'amount' => $total,
                'ticket_date' => $date,
                'ticket_time' => $time,
                'ticket_number' => $aa,
                'ticket_cash_id' => $am,
                'lat_lon' => GeomFromText('POINT(37.9667 23.7167)')
            );*/

            $query="insert into tickets(user_owner,ticket_type, referenced_company_afm, amount, ticket_date, ticket_time, ticket_number, ticket_cash_id,lat_lon)
                 values (" . $user_id . ", ". $category . ", " . $bus_afm .", ". $total . "," . $date . ", '" . $time  .
                "', '" . $aa . "', '" . $am . "',GeomFromText('POINT( "  . $lat_lon. " )'))";

            $query_response = $this->db->query($query);
            //$result = $query_response->result();

            //$this->db->insert('tickets', $data);
            //log_message('info', 'db query ' . var_dump($this->db->last_query()));

            if ($this->db->affected_rows() == 1) {
                return true;
            } else {
                return false;

            }
        } else {
            return false;
        }

    }

    function ticket_exists($bus_afm, $date, $aa, $am)
    {
        $data = array(
            'referenced_company_afm' => $bus_afm,
            'ticket_date' => $date,
            'ticket_number' => $aa,
            'ticket_cash_id' => $am,
        );

        $query = $this->db->get_where('tickets', $data);


        if ($query->num_rows() < 1) {
            return false;
        } else {
            return true;
        }

    }

}