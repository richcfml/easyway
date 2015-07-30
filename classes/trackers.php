<?php 
class tracker 
{
    public $id;
    public $Name;
    public $HtmlCode;
    public $RestaurantId;
    public $type;//1=visit tracker, 2=purchase tracker

    public function getAll() 
    {
        $sql="SELECT * FROM trackers WHERE RestaurantId=".$this->RestaurantId ." ORDER BY type";
        $mysql_qry = dbAbstract::Execute($sql);
        $arrResult=array();
        while($result = dbAbstract::returnObject($mysql_qry, 0, "tracker"))
        {
            $arrResult[]=$result;
        }
        return $arrResult;
    }
    
    public function save()	
    {
        if($this->id!="")
        {
            $sql="UPDATE trackers set Name='". addslashes($this->Name) ."'     ";
            $sql .=", HtmlCode='". dbAbstract::returnRealEscapedString($this->HtmlCode) . "'";
            $sql .=" where id=". $this->id ."";
            dbAbstract::Update($sql);
        }
        else
        {
            $sql="INSERT INTO trackers set Name='". addslashes($this->Name) ."'     ";
            $sql .=", HtmlCode='". dbAbstract::returnRealEscapedString($this->HtmlCode) . "'";
            $sql .=", type='".  $this->type . "'";
            $sql .=", RestaurantId='".  $this->RestaurantId . "'";
            dbAbstract::Insert($sql);
        }
    }
    
    public function delete() 
    {
        $sql="DELETE FROM trackers WHERE RestaurantId=".$this->RestaurantId ." and  id=". $this->id ."";
        $mysql_qry=dbAbstract::Delete($sql);
    }
    
    public function getVisitTrackers()
    {
        return $this->getTrackers(1);
    }
    
    public function getPurcahseTracker() 
    {
        return $this->getTrackers(2);
    }
    
    private function getTrackers($type) 
    {
        $sql="SELECT * FROM trackers WHERE RestaurantId=".$this->RestaurantId ." and type=". $type ." ORDER BY type";
        $mysql_qry = dbAbstract::Execute($sql);
        $arrResult=array();
        while($result=dbAbstract::returnObject($mysql_qry, 0, "tracker"))
        {
            $arrResult[]=$result;
        }
        return $arrResult;
    }
}
?>