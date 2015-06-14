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
require APPPATH . '/libraries/TesseractOCR.php';

class Ocrimg extends REST_Controller
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
        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
    }

    public function ocr_post()
    {

	//reset($_FILES);
        log_message('info', print_r($_FILES, TRUE));
        //@todo it must return bad request on non user specified
        $userid0=$this->post('userid');
        $userid=((isset($userid0)) ? $userid0 : "unknown_user");
        //var_dump($userid);

        //@todo get afm and api_key from user and if not return 400
        
        $file_path = $_FILES['photo']['tmp_name'];
        //@todo if not file_path it should return 400
        //get the produced filename from path
        $file = basename($file_path); 
        //saves file to filesystem, before it is vanished!
        shell_exec('cp ' . $file_path . ' /var/www/html/images/'. $file . '_' . $userid. '.jpg');

        $full_file_path='/var/www/html/images/'. $file . '_' . $userid . '.jpg';

        log_message('info', 'file name ---'. $full_file_path);
        //prepares image for ocr
        
        $command= 'convert \( ' . $full_file_path. ' -colorspace gray -type grayscale -contrast-stretch 0 \) \( -clone 0 -colorspace gray -negate -lat 20x20+12% -contrast-stretch 0 \) -compose copy_opacity -composite -opaque none +matte -deskew 0% -sharpen 1x1 ' . $full_file_path;

        //$a=shell_exec($command);
        //var_dump($a);
        //log_message('info', "convert command  " . $command);
        $command_ocr='/var/www/html/ocr-api ' . $full_file_path;
        //var_dump($command_ocr);
        $ocr_text=shell_exec($command_ocr);

        $return_data=file_get_contents('/var/www/html/out.txt');
        //var_dump($return_data);


        //$tesseract = new TesseractOCR($full_file_path);
        //$tesseract->setLanguage('ell'); //same 3-letters code as tesseract training data packages
        //$text =$tesseract->recognize(); //use TESSERACT
        //$text = myocr($file_path); //use ABBY
        //print $text;
        //$rec=$this->ocr_file($text);
        //var_dump($rec);

    //  $manage = json_decode($return_data);
        //var_dump($manage);
    //    $ret=json_encode($manage);

        $data1= json_decode($return_data);


        $rec['userid']=$userid;
        if ($file_path) {
            //print $ret;
            //log_message('info', "returned from ocr " . $text);
            $this->response($data1, 200);
        } else {
            $message = array('success' => 'false');
            $this->response($message, 400);
        }


    }

    function ocr_file($ret) {
        //@todo improve regex and return all required fields
        // which are: afm, number, date, time, total, tax, ccn(=0)
        $afm_pattern = '/ΑΦΜ *[:.]* *\d{9}/';
        preg_match($afm_pattern, $ret, $afm);
        //print_r($afm);

        $afm_pattern_all='/ *\d{9}/';
        preg_match($afm_pattern_all, $ret, $afm1);
        //print_r($afm1);

        $date_pattern= '/\d{1,2}[\/-] *\d{1,2}[\/-] *\d{1,4} */';
        preg_match($date_pattern, $ret, $date);
        //print_r($date);

        $total1_pattern='/€\d{1,}[.,]*\d{2}/'; //matches all expressions beginning with euro sign
        preg_match_all($total1_pattern, $ret, $total1);
        //print_r($total1);


        $total2_pattern='/ΣΥΝΟΛΟ [Α-Ω]* *[€ ]*\d{1,}[.,]*\d{2}/';//matches all expressions beginning with ΣΥΝΟΛΟ and having or not euro sign
        preg_match_all($total2_pattern, $ret, $total2);
        //print_r($total2);


        $date2_pattern='/\d{1,2} +[Α-Ωα-ωάίέόύ.]+ \d{1,4}/';
        preg_match($date2_pattern, $ret, $date2);
        //print_r($date2);

        $time_pattern='/\d{2}:\d{2}:*\d{0,2}/';
        preg_match($time_pattern, $ret, $time);
        //print_r($time);

        $cash_pattern='/ *[A-ZΑ-Ω]{2,3} *\d{8}/u';
        preg_match($cash_pattern, $ret, $cash_id);
        //print_r($cash_id);

        //set tax to 0 for testing
        $tax='0';

        $recognized=array(
            'afm1' => implode("",$afm),//(!empty($afm) ? "" : implode("", $afm) ),
            'afm2' => implode("",$afm1),
            'date1' => implode("",$date),
            'total1' => (!empty($total1) ? "" : implode("", $total1) ),
            'total2' => (!empty($total2) ? "" : implode("", $total2) ),
            'date2' => implode("",$date2),
            'time' => implode("",$time),
            'cash_id'=> implode("",$cash_id),
            //'tax' => implode("",$tax),
        );
        //log_message('info', 'Recognized text ' . var_dump($recognized));
        return $recognized;
    }

    function user_get()
    {
        if (!$this->get('id')) {
            $this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
        $users = array(
            1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
            2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
            3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
        );

        $user = @$users[$this->get('id')];

        if ($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

    function user_post()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');

        $this->response($message, 200); // 200 being the HTTP response code
    }

    function user_delete()
    {
        //$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');

        $this->response($message, 200); // 200 being the HTTP response code
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


    public function send_post()
    {
        var_dump($this->request->body);
    }


    public function send_put()
    {
        var_dump($this->put('foo'));
    }
}
