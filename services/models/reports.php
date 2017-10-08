<?php 
//require("dbcon.php");

class Reports{

	public function __construct() {           
		$this->mysqcon= new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);;
	}

	//Inserta un reporte en la base de datos
	public function insertReport($tokenUser,$reportName,$reportType,$coordinates,$idBike,$ReportDetails){

		try{
			$stmt = $this->mysqcon->prepare('SET @tokenUser := ?');
	        $stmt->bind_param('s', $tokenUser);
	        $stmt->execute();

	        $stmt = $this->mysqcon->prepare('SET @reportType := ?');
	        $stmt->bind_param('i', $reportType);
	        $stmt->execute();

	        $stmt = $this->mysqcon->prepare('SET @coordinates := ?');
	        $stmt->bind_param('s', $coordinates);
	        $stmt->execute();

	        $stmt = $this->mysqcon->prepare('SET @idBike := ?');
	        $stmt->bind_param('i', $idBike);
	        $stmt->execute();

	        $stmt = $this->mysqcon->prepare('SET @ReportDetails := ?');
	        $stmt->bind_param('s', $ReportDetails);
	        $stmt->execute();

	        $stmt = $this->mysqcon->prepare('SET @ReportName := ?');
	        $stmt->bind_param('s', $reportName);
	        $stmt->execute();

	        $stmt=$this->mysqcon->query("call sp_insertReport(@tokenUser,@reportType,@coordinates,@idBike,@ReportDetails,@ReportName)");
	        
            $bikes = $stmt->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $bikes; 
	    }
	    catch(Exception $e){
	     	return $e;
	    }
	}

	//Almacena la foto del reporte
    public function InsertPhotoReport($idReport,$url){ 
        try{
        //$stmt = $this->mysqcon->open();
            $stmt=$this->mysqcon->prepare("insert into tb_reportPhotos(url,thumbUrl,idReport)values(?,?,?)");
            $thumb=$url;
            $stmt->bind_param('ssi',$url,$thumb,$idReport);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    //Obtiene todas las fotos asociadas al reporte
    public function getPhotoReport($idReport){
        try{
            $stmt=$this->mysqcon->prepare("SELECT url, thumbUrl FROM tb_reportPhotos where idReport =?;");
            $stmt->bind_param('i',$idReport);
            $stmt->execute();
            $result = $stmt->get_result();        
            $bikePhotos = $result->fetch_all(MYSQLI_ASSOC);
            //print_r($bikeStates);         //  $bikeState["type"]=utf8_encode($item);
            $stmt->close();
            
            return $bikePhotos;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }                         
    }
     //Obtiene un reporte por nombre
    public function getReportByName($reportName,$userName){ 

        //$stmt = $this->mysqcon->open();
        $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @userName := ?');
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        $stmt=$this->mysqcon->prepare('SET @report := ?');
        $stmt->bind_param('s', $reportName);
        $stmt->execute();
        //print_r($userName);
        $stmt=$this->mysqcon->query("call sp_getReportByName(@userName,@report)");
        //print_r($stmt);
        $bikes = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $bikes;              

    }
    //Obtiene un reporte por id
    public function getReportById($id){ 
        try{
        //$stmt = $this->mysqcon->open();
        $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @id := ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt=$this->mysqcon->query("call sp_getReportById(@id)");
        //print_r($stmt);
        $report = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $report;
        }
       catch(Exception $e)
        {
            print_r($e->getMessage());
            return $e->getMessage();
        }             

    }
    //Obtiene los tipos de reporte
    public function getReportType(){ 
        $stmt=$this->mysqcon->prepare("SELECT * FROM tb_reportType");
        $stmt->execute();
        $result = $stmt->get_result();        
        $reportType = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $reportType;              
    }
    //Obtiene todos los reportes asociados al tipo
    public function getReports($idTypeReport,$fhInicio,$fhFin){
        try{
            $stmt=$this->mysqcon->prepare("SELECT r.id,r.reportName,u.nickname as 'report_owner',r.idreportType,
            	rt.reportType,r.fhReport,r.googlemapscoordinate,r.idBike,
            	b.bikeName,c.color,br.brand,t.type, bo.nickname as 'bike_owner',r.reportDetails,r.fhUpdated
        		FROM tb_reports r 
		        INNER JOIN tb_users u on r.idUser=u.id
		        INNER JOIN tb_reportType rt on r.idReportType=rt.id
                INNER JOIN tb_bikes b on r.idBike=b.id
                INNER JOIN tb_colors c on b.idColor=c.id
                INNER JOIN tb_brands br on b.idBrand=br.id
		        INNER JOIN tb_bikeType t on b.idType=t.id
		        INNER JOIN tb_users bo on b.idUser=bo.id
		        where r.idReportType=? and r.fhReport >=? and r.fhReport<=? order by r.fhUpdated desc;");
            $stmt->bind_param('iss',$idTypeReport,$fhInicio,$fhFin);

            $stmt->execute();
            $result = $stmt->get_result();        
            $reports = $result->fetch_all(MYSQLI_ASSOC);
         //  $bikeState["type"]=utf8_encode($item);
            $stmt->close();
            
            return $reports;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }                         
    }
        //Obtiene todos los reportes asociados al tipo
    public function getReportsMaps(){
        try{
            $stmt=$this->mysqcon->prepare("SELECT r.id,r.reportName,u.nickname as 'report_owner',r.idreportType,
                rt.reportType,r.fhReport,r.googlemapscoordinate,r.idBike,
                b.bikeName,c.color,br.brand,t.type, bo.nickname as 'bike_owner',r.reportDetails,r.fhUpdated
                FROM tb_reports r 
                INNER JOIN tb_users u on r.idUser=u.id
                INNER JOIN tb_reportType rt on r.idReportType=rt.id
                INNER JOIN tb_bikes b on r.idBike=b.id
                INNER JOIN tb_colors c on b.idColor=c.id
                INNER JOIN tb_brands br on b.idBrand=br.id
                INNER JOIN tb_bikeType t on b.idType=t.id
                INNER JOIN tb_users bo on b.idUser=bo.id
                where rt.ReportType like 'BICICLETA ROBADA' order by r.fhUpdated desc");

            $stmt->execute();
            $result = $stmt->get_result();        
            $reports = $result->fetch_all(MYSQLI_ASSOC);
         //  $bikeState["type"]=utf8_encode($item);
            $stmt->close();
            
            return $reports;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }                         
    }
    //Obtiene los ultimos 10 reportes
    public function getLastReports(){
        try{
            $stmt=$this->mysqcon->prepare("SELECT r.id,r.reportName,u.nickname as 'report_owner',r.idreportType,
                rt.reportType,r.fhReport,r.googlemapscoordinate,r.idBike,
                b.bikeName,c.color,br.brand,t.type, bo.nickname as 'bike_owner',r.reportDetails,r.fhUpdated
                FROM tb_reports r 
                INNER JOIN tb_users u on r.idUser=u.id
                INNER JOIN tb_reportType rt on r.idReportType=rt.id
                INNER JOIN tb_bikes b on r.idBike=b.id
                INNER JOIN tb_colors c on b.idColor=c.id
                INNER JOIN tb_brands br on b.idBrand=br.id
                INNER JOIN tb_bikeType t on b.idType=t.id
                INNER JOIN tb_users bo on b.idUser=bo.id WHERE r.idReportType!=3 order by r.fhUpdated desc LIMIT 10 ");
            $stmt->execute();
            $result = $stmt->get_result();        
            $reports = $result->fetch_all(MYSQLI_ASSOC);
         //  $bikeState["type"]=utf8_encode($item);
            $stmt->close();
            
            return $reports;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }                         
    }

}
?>