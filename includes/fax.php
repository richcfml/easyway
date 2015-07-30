<? 
class metrofax  {
public	$faxstatus=0;//0=Sent 1= Failed 3= processing
public $faxid;
	
	public function sendfax($orderid,&$raw,$faxnum1,$rest_name) { 
	
			 
			 $log_id = dbAbstract::Execute("INSERT INTO fax_log (orderid,status,fax_date,TrackingNumber,fax_message) VALUES (". $orderid .",0,'". date("Y-m-d H:i:s")  ."',0,'fax sending started'  )",0, 2);
		

		 
	   // define the soapaction as found in the wsdl
    $saUpload = "http://wsf.metrofax.com/webservices/UploadAttachment";
    $saSendFax = "http://wsf.metrofax.com/webservices/SendFaxMessage";
    // endpoint address
	$wsdl = "https://wsf.metrofax.com/WebService.asmx";
	$namespace = "http://wsf.metrofax.com/webservices/";
	$options = array('location' => $wsdl,'uri' => $wsdl, 'trace' => 1); 
	
 	$client = new SoapClient($wsdl);  
	
	// Relevant data
	$login = 'cwilliams@joenerd.com';
	$password = 'iceberg6';
 
	 
	 
	$company = 'easywayordering';
	$fromaddr = 'orders@easywayordering.com';
	$file_name=$orderid .".pdf";
 
	
  try {
  
	// Attachment data
 

	$soapmsg = $client->serializeEnvelope('<UploadAttachment xmlns="http://wsf.metrofax.com/webservices">
	<loginId>'.$login.'</loginId>
	<password>'.$password.'</password>
	<fileName>'.$file_name.'</fileName>
	<base64EncodedString>
		'.$raw.'
	</base64EncodedString>
	</UploadAttachment>','',array(),'document', 'literal');

	/* Send the SOAP message and specify the soapaction  */
	$response = $client->send($soapmsg, $saUpload);
	$uploadResult = $response["UploadAttachmentResult"];
 
 
	if ($uploadResult['ResultCode']=="-1") {
		 dbAbstract::Update("UPDATE fax_log SET fax_message='fax sending failed unable to upload file ". dbAbstract::returnRealEscapedString( json_encode($uploadResult)) ." WHERE id=". $log_id ."");
		
		 dbAbstract::Update("UPDATE ordertbl SET fax_tracking_number=123,fax_tries=1,resend_fax=0  WHERE OrderID=". $orderid ." ");
			 
          return false;
        }

	
	// start to use the result by following the structure of the SOAP response
    // first extract the UploadAttachmentResult
	
    // then you can access the Status message
	 
    // extract the file reference
	$fileref1 = $uploadResult["ResultString"];
    // continue with the Result string
	 
	 
	$soapmsg = $client->serializeEnvelope('<SendFaxMessage xmlns="http://wsf.metrofax.com/webservices">
		<loginId>'.$login.'</loginId>
		<password>'.$password.'</password>
		<subject>order '. $orderid .' Fax</subject>
		<fromEmailAddress>'.$fromaddr.'</fromEmailAddress>
		<recipients>
			<FaxRecipient>
				<FaxNumber>'.$faxnum1.'</FaxNumber>
				<Name>'.  $this->xmlentities($rest_name).'</Name>
				<Company>'.$company.'</Company>
			</FaxRecipient>
		</recipients>
		<attachments>
			<FileRef>
				<Id>'.$fileref1.'</Id>
			</FileRef>			 
		</attachments>
		</SendFaxMessage>','',array(),'document', 'literal');

	/* Send the SOAP message and specify the soapaction  */
	$response = $client->send($soapmsg, $saSendFax);
	 
	$sendFaxResult = $response["SendFaxMessageResult"];

	 
	if ($sendFaxResult['ResultCode']=="-1") {
		
	  	 dbAbstract::Update("UPDATE fax_log SET fax_message='fax sending failed, fax logging failed  ". dbAbstract::returnRealEscapedString(json_encode($sendFaxResult)) ."'  WHERE id=". $log_id ."");
		  dbAbstract::Update("UPDATE ordertbl SET fax_tracking_number=123,fax_tries=1,resend_fax=0 WHERE OrderID=". $orderid ." ");

		
		 return false;
       }

 
	
    // start to use the result by following the structure of the SOAP response
    // first extract the SendFaxResult
	$tracking_Number=$sendFaxResult["Items"]["FaxMessage"]["TrackingNumber"];
	
 if($tracking_Number=='') $tracking_Number=123;
	    dbAbstract::Update("UPDATE ordertbl SET fax_tracking_number='". $tracking_Number ."' ,fax_sent=1,resend_fax=0,fax_tries=0,json_sent=0 ,fax_date='".date("Y-m-d H:i:s") ."' WHERE OrderID=". $orderid ."");
					
	   dbAbstract::Update("UPDATE fax_log SET fax_message='success' ,status=1,TrackingNumber='".  $tracking_Number ."' WHERE id=". $log_id ."");
		   
		   
  
	  } catch (SoapFault $exception) { 
	
	 		 dbAbstract::Update("UPDATE ordertbl SET fax_tracking_number=123,fax_tries=1,resend_fax=0  WHERE OrderID=". $orderid ." ");
	 		 dbAbstract::Update("UPDATE fax_log SET fax_message='". serialize($exception) ."'  WHERE id=". $log_id ."");
		 
	
			 
		 return false; 
	  } 
 	
	
	 return true;
	
	}

private function xmlentities($string) {

    return str_replace(array("&", "<", ">", "\"", "'"),
        array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), $string);
}


	public function resendfax(){
   
	$qry="SELETC o.fax_tracking_number,o.OrderID,r.name restaurantname,r.fax,o.fax_tries,r.email FROM ordertbl o INNER JOIN resturants r ON o.`cat_id` = r.id WHERE resend_fax=1 ORDER BY OrderID DESC";
					 
	$mysql_query=dbAbstract::Execute($qry);
	$count=0;	
	
	while($faxtoresend=dbAbstract::returnObject($mysql_query)){
				$file_name='/home/public_html/easywayordering.com/public/pdffiles/pdf'. $faxtoresend->OrderID .'.pdf';
				$fh = fopen($file_name, 'rb');
				$fc = fread($fh, filesize($file_name));
				fclose($fh);
				$raw = base64_encode($fc);
				
				if(strlen($faxtoresend->fax) == 10)
				{
					$faxtoresend->fax = '1'.$faxtoresend->fax;
				}
				
				$this->sendfax($faxtoresend->OrderID,$raw,$faxtoresend->fax,$faxtoresend->restaurantname);
			    $count=$count+1;
		
		}///WHILE LOOP
		return $count;
	}
	
	
	function searchFax($trackingNumber){
		
			$saSearchFaxMessages = "http://wsf.metrofax.com/webservices/SearchFaxMessages";
	 
			// endpoint address
			$wsdl = "https://wsf.metrofax.com/WebService.asmx";
			$namespace = "http://wsf.metrofax.com/webservices/";
			$options = array('location' => $wsdl,'uri' => $wsdl, 'trace' => 1); 
			
			$client =new SoapClient($wsdl);  
			
			// Relevant data
			$login = 'cwilliams@joenerd.com';
			$password = 'iceberg6';
			
		  $querystring = 'TrackingNumber='. $trackingNumber;
	 
		
			$soapmsg = $client->serializeEnvelope('<SearchFaxMessages xmlns="http://wsf.metrofax.com/webservices">
			<loginId>'.$login.'</loginId>
			<password>'.$password.'</password>
			<queryString>'.$querystring.'</queryString>
			<startRecord>1</startRecord>
			<maxRecords>1</maxRecords>
			</SearchFaxMessages>','',array(),'document', 'literal');
		
	 		$response = $client->send($soapmsg, $saSearchFaxMessages);
			$faxid=0;
			
			  if (!$client->fault) 
			  {
				  if(isset($response["SearchFaxMessagesResult"]["Items"]["FaxItem"]["FaxID"]))
				  {
				  	$faxid=$response["SearchFaxMessagesResult"]["Items"]["FaxItem"]["FaxID"];
				  	$this->faxstatus=$response["SearchFaxMessagesResult"]["Items"]["FaxItem"]["FaxStatus"];
				  	$this->faxid=$faxid;
				  }
				  else
				  {
					  $this->faxstatus=4;
					  $this->faxid=0;
				  }
			  }
			  else{
				  $this->faxstatus=4;
				  $this->faxid=0;
				  
				  }
			 $client=NULL;
			  return $faxid;
 
 
		}
}
?>