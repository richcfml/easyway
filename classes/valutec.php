<?php
//require_once ("lib/nusoap.php");
/*******************************************************************************************
 GLOBAL VARIABLES
 -------------------------------
 Modify these variables to match your settings as supplied by Valutec to you.  This will allow
 all these sample pages to work with your cards & data.  This is something you MUST do if you
 plan to simply copy this page to use within your own website for your customers.
********************************************************************************************/

	//CLIENT KEY
	//--------------------------------------

	/*
Test AutoRewards:
7018525218460004830 Pin 94927389 Active/Balance 10.00 Points 00
7018525218460004863 Pin 37127707 Active/Balance 20.00 Points 00
7018525218460004871 Pin 07655635 Active/Balance 00.00 Points 50
7018525218460004889 Pin 56283744 Active/Balance 00.00 Points 50
7018525218460004897 Pin 26811672 Active/Balance 15.00 Points 50
7018525218460004905 Pin 49855953 Active/Balance 50.00 Points 00*/

	
/*******************************************************************************************

DO NOT MODIFY BELOW THIS LINE------------------------------------DO NOT MODIFY BELOW THIS LINE

********************************************************************************************/
	 
define('ProgramType','Loyalty');
define('Identifier',substr(sha1(time()),0,10));
define('CLIENT','http://ws.valutec.net/Valutec.asmx?WSDL');
	
	 
function CardBalance($CardNumber)
{
    $input = array
    (
        'ClientKey' => ClientKey,
        'TerminalID' => TID,
        'ProgramType' => ProgramType,
        'CardNumber' => $CardNumber,
        'ServerID' => ServerId,
        'Identifier' => Identifier
    );

    $client = new nusoap_client(CLIENT, array('trace' => 1));
    
    Log::write('Valutec Call - CardBalance', print_r($input, true), 'valutec');
    $response = $client->call("Transaction_CardBalance", $input);
    Log::write('Valutec Response - CardBalance', print_r($response, true), 'valutec');
    
    $data = $response['Transaction_CardBalanceResult']; 

    if(Identifier == $data['Identifier'])
    {
        $data['Balance'] = (!empty($data['Balance']) && is_numeric($data['Balance']) ? $data['Balance'] :0);
        $data['PointBalance'] = (!empty($data['PointBalance']) && is_numeric($data['PointBalance']) ? $data['PointBalance'] :0);
    }
    else
    {
        $data['Balance'] = 0;
        $data['PointBalance'] =0;
    }

    return $data;
}

function Sale($CardNumber, $Amount)
{
    $input = array
    (
        'ClientKey' => ClientKey,
        'TerminalID' => TID,
        'ProgramType' => 'Gift',
        'CardNumber' => $CardNumber,
        'Amount' => $Amount,
        'ServerID' => ServerId,
        'Identifier' => Identifier
    );

    $client = new nusoap_client(CLIENT, array('trace' => 1));
    
    Log::write('Valutec Call - Sale', print_r($input, true), 'valutec');
    $trans = $client->call("Transaction_Sale",$input);
    Log::write('Valutec Response - Sale', print_r($trans, true), 'valutec');
    
    $data = $trans['Transaction_SaleResult']; 
		
    if(Identifier == $data['Identifier'])
    {
        $response = $data;
    }
    else
    {
        $response = 'Security error, transaction has been terminated';
    } 
    return $response; 
} 
		
function AddValue($CardNumber, $Points)
{
    $input = array
    (	
        'ClientKey' => ClientKey,	
        'TerminalID' => TID,
        'ProgramType' => ProgramType,
        'CardNumber' => $CardNumber,
        'Amount' => $Points,
        'ServerID' => ServerId,
        'Identifier' => Identifier
    );
    
    $client = new nusoap_client(CLIENT, array('trace' => 1)); 
    
    Log::write('Valutec Call - AddValue', print_r($input, true), 'valutec');
    $trans = $client->call("Transaction_AddValue",$input);
    Log::write('Valutec Response - AddValue', print_r($trans, true), 'valutec');

    $data = $trans['Transaction_AddValueResult']; 

    if(Identifier == $data['Identifier'])
    {
        $response = $data;
    }
    else
    {
        $response = 'Security error, transaction has been terminated';
    } 

    $response['Balance'] = (!empty($response['Balance']) && is_numeric($response['Balance']) ? $response['Balance'] :0);
    $response['PointBalance'] = (!empty($response['PointBalance']) && is_numeric($response['PointBalance']) ? $response['PointBalance'] :0);

    return $response;
} 
		
function ActivateCard($CardNumber, $Amount)
{
    $input = array
    (
        'ClientKey' => ClientKey,
        'TerminalID' => TID,
        'ProgramType' => ProgramType,
        'CardNumber' => $CardNumber,
        'Amount' => $Amount,
        'ServerID' => ServerId,
        'Identifier' => Identifier
    );
    $client = new nusoap_client(CLIENT, array('trace' => 1)); 
    
    Log::write('Valutec Call - ActivateCard', print_r($input, true), 'valutec');
    $trans = $client->call("Transaction_ActivateCard",$input);
    Log::write('Valutec Response - ActivateCard', print_r($trans, true), 'valutec');

    $data = $trans['Transaction_ActivateCardResult']; 

    if(Identifier == $data['Identifier'])
    {
        $response = $data;
        if($data['Authorized']=="false") 
        {
            return $this->AddValue($CardNumber, $Amount);
        }
    }
    else
    {
        $response = 'Security error, transaction has been terminated';
    } 

    $response['Balance'] = (!empty($response['Balance']) && is_numeric($response['Balance']) ? $response['Balance'] :0);
    $response['PointBalance'] = (!empty($response['PointBalance']) && is_numeric($response['PointBalance']) ? $response['PointBalance'] :0);
    return $response;
}
       
function DeactivateCard($CardNumber)
{
    $input = array
    (
        'ClientKey' => ClientKey,
        'TerminalID' => TID,
        'ProgramType' => ProgramType,
        'CardNumber' => $CardNumber,
        'ServerID' => ServerId,
        'Identifier' => Identifier
    );
    $client = new nusoap_client(CLIENT, array('trace' => 1));
    
    Log::write('Valutec Call - DeactivateCard', print_r($input, true), 'valutec');
    $trans = $client->call("DeactivateCard",$input);
    Log::write('Valutec Response - DeactivateCard', print_r($trans, true), 'valutec');

    $data = $trans['Transaction_DeactivateCardResult']; 

    if(Identifier == $data['Identifier'])
    {
        $response = $data;
    }
    else
    {
        $response = 'Security error, transaction has been terminated';
    }
    return $response;
}

function GetRegistration($CardNumber)
{
    $input = array
    (
        'ClientKey' => ClientKey,
        'TerminalID' => TID,
        'CardNumber' => $CardNumber
    );
    $client = new nusoap_client(CLIENT, array('trace' => 1));
    
    Log::write('Valutec Call - GetRegistration', print_r($input, true), 'valutec');
    $trans = $client->call("Registration_Get",$input);
    Log::write('Valutec Response - GetRegistration', print_r($trans, true), 'valutec');

    return $trans['Registration_GetResult'];
}

function SetRegistration($CardNumber, $Name='', $Address1='', $Address2='', $City='', $State='', $Zip='', $Telephone='', $Email='', $DOB='', $M1='', $M2='', $M3='', $M4='', $M5='')
{
    $input = array
    (
        'ClientKey' => ClientKey,
        'TerminalID' => TID,
        'CardNumber' => $CardNumber,
        'Name' => $Name,
        'Address1' => $Address1,
        'Address2' => $Address2,
        'City' => $City,
        'State' => $State,
        'Zip' => $Zip,
        'Telephone' => $Telephone,
        'EmailAddress' => $Email,
        'DateOfBirth' => $DOB,
        'Misc1' => $M1,
        'Misc2' => $M2,
        'Misc3' => $M3,
        'Misc4' => $M4,
        'Misc5' => $M5
    );
    $client = new nusoap_client(CLIENT, array('trace' => 1));
    
    Log::write('Valutec Call - SetRegistration', print_r($input, true), 'valutec');
    $trans = $client->call("Registration_Set",$input);
    Log::write('Valutec Response - SetRegistration', print_r($trans, true), 'valutec');

    return $trans['Registration_SetResult'];
}	
	
function isCardRegistered($CardNumber) 
{
    $mSQL = "SELECT COUNT(*) AS total FROM customer_registration WHERE valuetec_card_number=".$CardNumber;
    $result = dbAbstract::ExecuteObject($mSQL);
    if($result->total>0)
    {
        return true;	
    }
    else
    {
        return false;
    }
 }
?>