<?
class mailer  {
	
		public $MAIL_HOST_NAME="secure.emailsrvr.com";
		public $from;
			 
		public $customermail;
		
				 function __construct() {
							$this->customermail = new PHPMailer();
							$this->customermail->IsSMTP(); // telling the class to use SMTP
							$this->customermail->Host = $this->MAIL_HOST_NAME;// SMTP server
							$this->customermail->Port = 25;// SMTP port
							$this->customermail->SMTPAuth = true;
							$this->customermail->Username = 'orders@easywayordering.com';
							$this->customermail->Password = 'U7yOderEa';	
							$this->customermail->From = "orders@easywayordering.com";
							$this->customermail->FromName="";
							$this->customermail->Sender=""; // indicates ReturnPath header
							$this->customermail->AddAddress("irfan@qualityclix.com");
							$this->from="";
		
					  }
					  public function send($message,$subject) {
							$this->customermail->IsHTML(true);
							$this->customermail->Subject = $subject;
							$this->customermail->Body = $message;
							$this->customermail->Send();	
						  
						  }
						  
				  public function sendTo($message,$subject,$to,$html=true) {
							$this->customermail->ClearAllRecipients();
							$this->customermail->AddAddress($to);
						//	$this->customermail->AddBCC("irfan@qualityclix.com");
							$this->customermail->IsHTML($html);
							$this->customermail->Subject = $subject;
							$this->customermail->Body = $message;
							if($this->from!="")
								$this->customermail->From = $this->from;
							echo "here";
							$this->customermail->Send();	
						  
						  }
						  
						  public function addattachment($attachment){
							
							    $this->customermail->AddAttachment($attachment);
							  }
					  public function addbcc($bcc){
							
							    $this->customermail->AddBCC($bcc);
							  }
							  
							  public function clearattachments() {
								     $this->customermail->ClearAttachments();
								  
								  }
		

}
	
?>