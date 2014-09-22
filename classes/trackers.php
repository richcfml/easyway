<? 

  class tracker {
	
		public $id;
		public $Name;
		public $HtmlCode;
		public $RestaurantId;
		public $type;//1=visit tracker, 2=purchase tracker
		
	public function getAll() {
			
			$sql="select * from trackers where RestaurantId=".$this->RestaurantId ." order by type";
			$mysql_qry=mysql_query($sql);
			$arrResult=array();
			while($result=mysql_fetch_object($mysql_qry,"tracker")){
				
				$arrResult[]=$result;
			 }
			 return $arrResult;
		}
	public function save()	{
		 if($this->id!="")
		 {
			 $sql="update trackers set Name='". addslashes($this->Name) ."'     ";
			  $sql .=", HtmlCode='". mysql_escape_string($this->HtmlCode) . "'";
			  $sql .=" where id=". $this->id ."";
			   
			  
		 }
		 else{
				$sql="insert into trackers set Name='". addslashes($this->Name) ."'     ";
				$sql .=", HtmlCode='". mysql_escape_string($this->HtmlCode) . "'";
				$sql .=", type='".  $this->type . "'";
				$sql .=", RestaurantId='".  $this->RestaurantId . "'";
				  
		 }
		 mysql_query($sql);
		 
		}
		public function delete() {
			
			$sql="delete from trackers where RestaurantId=".$this->RestaurantId ." and  id=". $this->id ."";
			$mysql_qry=mysql_query($sql);
			 
		}
	 public function getVisitTrackers() {
			
			return $this->getTrackers(1);
			
		}
	 public function getPurcahseTracker() {
			
			return $this->getTrackers(2);
			
		}
		 private function getTrackers($type) {
			
			$sql="select * from trackers where RestaurantId=".$this->RestaurantId ." and type=". $type ." order by type";
			$mysql_qry=mysql_query($sql);
			$arrResult=array();
			while($result=mysql_fetch_object($mysql_qry,"tracker")){
				
				$arrResult[]=$result;
			 }
			 return $arrResult;
		 }
		
	}

?>