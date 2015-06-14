<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('check_afm')) {
    function check_afm($afm)
    {
        try {

            $opts = array(
                'http' => array(
                    'user_agent' => 'PHPSoapClient'
                )
            );

            $context = stream_context_create($opts);
            $client = new SoapClient('https://ec.europa.eu/taxation_customs/tin/checkTinService.wsdl',
                array('stream_context' => $context,
                    'cache_wsdl' => WSDL_CACHE_NONE));

            $result = $client->checkTin(array(
                'countryCode' => 'EL',
                'tinNumber' => $afm
            ));
            if (($result->validSyntax && $result->validStructure)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
}

if (!function_exists('get_from_gsis_ours')) {
    function get_from_gsis_ours($afm)
    {
        $username = 'MY USERNAME';
        $password = 'MY_PASSWORD';
        $caller = '111111111';
        $called = $afm;
        return call_gsis($username, $password, $caller, $called);
    }
}


if (!function_exists('get_from_gsis_other')) {
    function get_from_gsis_other($afm, $username, $password)
    {
       return call_gsis($username, $password, $afm, $afm);
    }
}

if (!function_exists('call_gsis')) {
    function call_gsis($username, $password, $caller, $called)
    {

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <env:Envelope
            xmlns:env="http://schemas.xmlsoap.org/soap/envelope/"
            xmlns:ns="http://gr/gsis/rgwspublic/RgWsPublic.wsdl"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema"
            xmlns:ns1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-
            1.0.xsd">
            <env:Header>
            <ns1:Security>
            <ns1:UsernameToken>
            <ns1:Username>' . $username . '</ns1:Username>
            <ns1:Password>' . $password . '</ns1:Password>
            </ns1:UsernameToken>
            </ns1:Security>
            </env:Header>
            <env:Body>
            <ns:rgWsPublicAfmMethod>
            <RgWsPublicInputRt_in xsi:type="ns:RgWsPublicInputRtUser">
            <ns:afmCalledBy>' . $caller . '</ns:afmCalledBy>
            <ns:afmCalledFor>' . $called . '</ns:afmCalledFor>
            </RgWsPublicInputRt_in>
            <RgWsPublicBasicRt_out xsi:type="ns:RgWsPublicBasicRtUser">
            <ns:afm xsi:nil="true"/>
            <ns:stopDate xsi:nil="true"/>
            <ns:postalAddressNo xsi:nil="true"/>
            <ns:doyDescr xsi:nil="true"/>
            <ns:doy xsi:nil="true"/>
            <ns:onomasia xsi:nil="true"/>
            <ns:legalStatusDescr xsi:nil="true"/>
            <ns:registDate xsi:nil="true"/>
            <ns:deactivationFlag xsi:nil="true"/>
            <ns:deactivationFlagDescr xsi:nil="true"/>
            <ns:postalAddress xsi:nil="true"/>
            <ns:firmFlagDescr xsi:nil="true"/>
            <ns:commerTitle xsi:nil="true"/>
            <ns:postalAreaDescription xsi:nil="true"/>
            <ns:INiFlagDescr xsi:nil="true"/>
            <ns:postalZipCode xsi:nil="true"/>
            </RgWsPublicBasicRt_out>
            <arrayOfRgWsPublicFirmActRt_out xsi:type="ns:RgWsPublicFirmActRtUserArray"/>
            <pCallSeqId_out xsi:type="xsd:decimal">0</pCallSeqId_out>
            <pErrorRec_out xsi:type="ns:GenWsErrorRtUser">
            <ns:errorDescr xsi:nil="true"/>
            <ns:errorCode xsi:nil="true"/>
            </pErrorRec_out>
            </ns:rgWsPublicAfmMethod>
            </env:Body>
            </env:Envelope>
            ';

        $sUrl = 'https://www1.gsis.gr/webtax2/wsgsis/RgWsPublic/RgWsPublicPort?WSDL';

// set parameters

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $sOutput = curl_exec($ch);
        curl_close($ch);

        $xml = $sOutput;

// SimpleXML seems to have problems with the colon ":" in the <xxx:yyy> response tags, so take them out
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);

        $responseArray = json_decode($json, true);
        return $responseArray;
        //var_dump($responseArray);
        //var_dump($responseArray['envBody']['mrgWsPublicAfmMethodResponse']['RgWsPublicBasicRt_out']['mafm']);

    }
}

if (!function_exists('create_api_key')) {
    function create_api_key($afm)
    {

        log_message('info', '2');

        // Alternative JSON version
        // $url = 'http://twitter.com/statuses/update.json';
        // Set up and execute the curl process
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_URL, 'http://83.212.118.7/camelot/api/key/index');
        //curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl_handle, CURLOPT_PUT, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $data = array(
            'afm' => $afm,
        );
        curl_setopt($curl_handle, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($data));

        $buffer = curl_exec($curl_handle);


        //$info = curl_getinfo($curl_handle);

        curl_close($curl_handle);

        //var_dump(json_decode($buffer, true));

        $result = json_decode($buffer);
        //log_message('info', 'returned ' .  var_dump(json_decode($buffer, true)));

        if (isset($result->status) && $result->status == '1') {
            return $result->key;
        } else {
            echo 'Something has gone wrong';
        }
    }

}