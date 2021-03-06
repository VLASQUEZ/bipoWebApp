
angular.module('bipoApp.controllers', ['ngAnimate', 'ngSanitize','ui.bootstrap'])

.controller('registerCtrl',function ($scope,ValidateForm,Register,Login,setPreferences,CookieManager, $log, $document,$interval,$window,$cookies,$cookieStore){
	
	$scope.error={errorState:false,message:""};
	$scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		$window.location.href='home';
    	}
    }
	$scope.logout=function(){
	 		Login.logout($cookieStore.get('token'))
	 			.then(function(data){
	 				if(data.error){
	 					if(CookieManager.remove()){
					 		$window.location.href='inicio';
	 					}
	 				}
	 			})
	 	
    }

	$scope.register={name:{name:"name",data:null,type:"alpha"},
					lastName:{name:"lastName",data:null,type:"alpha"},
					birthdate:{name:"birthdate",data:null,type:"birthdate"},
					document:{name:"document",data:null,type:"number"},
					email:{name:"email",data:null,type:"email"},
					cellphone:{name:"cellphone",data:null,type:"number"},
					password:{name:"password",data:null,type:"password"},
					confirmPass:{name:"confirmPass",data:null,type:"comparePass"},
					terms:{name:"terms",data:null,type:"checkbox"},
					publish:{name:"publish",data:null,type:"publish"}
				};

	$scope.registerUser=function(isValid){
		
		if(isValid){
			$scope.error.message="Cargando...";	
			$scope.register.birthdate.data=document.getElementById('fh2').value;
			$scope.errors=ValidateForm.fmValid($scope.register);
			$scope.errors.confirmPass=ValidateForm.comparePass($scope.register.password.data,$scope.register.confirmPass.data);
			$scope.formState=ValidateForm.formState($scope.errors);
			
			if($scope.formState){
				try{
					
					Register.register($scope.register)
						.then(function(data){
								if(!data.error){
								if(data.message){

									//mostrar modal y pasar al formulario de registro de
									//bicicletas
									$scope.error.message="Iniciando Sesión...";
									Login.loginUser($scope.register)
										.then(function(data){
											//setearpreferencias
											CookieManager.writeCookie(data.user[0]);
											var preferences={token:$cookieStore.get('token'),
												emailReceiver:1,
												photoPublication:1,
												enableReportUbication:1,
												enableLocationUbication:1};
					      					setPreferences.setPreferences(preferences)
											$interval(function() {

												$window.location.href='registroBicicleta';
						      					//Redireccion a registro de bicicletas

											}, 3000,1);
										});

								}
								else{
										$scope.error.errorState=true;
										$scope.error.message="Esta cuenta ya se encuentra registrada...";
										
								}
									
								}
								else
								{
									$scope.error.errorState=true;
									$scope.error.message=data.message;
								}
					});					
				}
				catch(e)
				{
					console.log(e);
					$scope.error.message=e;
				}
				
			}
			else{
				$scope.error.errorState=true;
				$scope.error.message="Datos incompletos";
			}
		}
		else{
			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}

})
.controller('registerBikeCtrl',function ($scope,ValidateForm,Login,$uibModal, $log, $document,$interval,fileReader,CookieManager,$cookieStore,$cookies,$window,Bikes) {
	$scope.bikeRegister={bikeName:{name:"name",data:null,type:"alpha"},
				brand:{name:"brand",data:null,type:"select"},
				color:{name:"color",data:null,type:"select"},
				bikeType:{name:"bikeType",data:null,type:"select"},
				bikeState:{name:"bikeState",data:3,type:"select"},
				bikeFeatures:{name:"bikeFeatures",data:null,type:"text"},
				idFrame:{name:"idFrame",data:null,type:"text"},
				};
    $scope.islogged=false;
	$scope.nickname;
	$scope.brands;
	$scope.colors;
	$scope.bikeTypes;
	$scope.files=[];
	$scope.images=[];
	$scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		Bikes.colors()
				.then(function(data){
					$scope.colors=data.bikeColor;
				});
    		Bikes.brands()
				.then(function(data){
					$scope.brands=data.brands;
			});
    		Bikes.bikeTypes()
				.then(function(data){
					$scope.bikeTypes=data.biketypes;
			});
    	}else{
    		$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.getFile = function () {
	        fileReader.readAsDataUrl($scope.files, $scope)
          		.then(function(result) {
          			$scope.images=result;
         	 });


		
    };

	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.bike;

	$scope.registerBike=function(isValid){
		if(isValid){
			$scope.errors=ValidateForm.fmValid($scope.bikeRegister);
			$scope.formState=ValidateForm.formState($scope.errors);
			
			if($scope.formState){
				try{
					$scope.error.message="Registrando tu bicicleta";	
					Bikes.insertBike($scope.bikeRegister,$cookieStore.get('token'))
						.then(function(data){
							if(!data.error)
							{
				                angular.forEach($scope.files,function(photo){   
					                Bikes.bikePhoto(data.bikeId,$cookieStore.get('token'),photo);
					            });	

					           $scope.error.errorState=true;
					           $scope.error.message="Bicicleta agregada satisfactoriamente";
					           $interval(function(){
			           		         $window.history.back();
					           },2000);
							}
							else{
								$scope.error.errorState=true;
					           $scope.error.message=data.message;
							}
 							
						});
				}
				catch(e)
				{
					$ctrl.message="e";
				}
				
			}
			else{		
				$scope.error.errorState=true;
				$scope.error.message="Datos incompletos";
			}
		}
		else{

			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}
})
.controller('updateBikeCtrl',function ($scope,ValidateForm,Login,$uibModal, $log, $document,$interval,fileReader,CookieManager,$cookieStore,$cookies,$window,Bikes,getParams) {
	$scope.bikeRegister={bikeId:{name:"bikeId",data:null,type:"integer"},
				color:{name:"color",data:null,type:"select"},
				bikeFeatures:{name:"bikeFeatures",data:null,type:"text"},
				};
    $scope.islogged=false;
	$scope.bike={};
	$scope.colors;
	$scope.files=[];
	$scope.images=[];
	$scope.bikename="";
	$scope.checkLogin=function(){
		Bikes.colors()
				.then(function(data){
					$scope.colors=data.bikeColor;
				});
    	var bikename=getParams().idBike;
    	var user=getParams().user;
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		if(bikename!=undefined && user!=undefined){
    			Bikes.bikeByUser(user,bikename)
    			.then(function(data){
					if(data.bikes!= undefined){
						$scope.bike=data.bikes[0];
						$scope.bikeRegister.bikeFeatures.data=$scope.bike.bikefeatures;
						$scope.bikeRegister.color.data=$scope.bike.color;
						$scope.bikeRegister.bikeId.data=$scope.bike.id;
					}
					else{
						$scope.error.errorState=true;
						$scope.error.message=data.message;
					}

				});	
    		}
    		else{
    			
    			$scope.error.errorState=true;
    			$scope.error.message="Ocurrió un error al cargar la bicicleta";
    		}    			
    	}
    	else{
		$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.getFile = function () {
	        fileReader.readAsDataUrl($scope.files, $scope)
          		.then(function(result) {
          			$scope.images=result;
         	 });


		
    };

	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.bike;

	$scope.updateBike=function(isValid){
		if(isValid){
			$scope.errors=ValidateForm.fmValid($scope.bikeRegister);
			$scope.formState=ValidateForm.formState($scope.errors);
			
			if($scope.formState){
				try{
					Bikes.updateBike($scope.bikeRegister,$cookieStore.get('token'))
						.then(function(data){
							console.log($scope.bike);
							if(!data.error)
							{
				                angular.forEach($scope.files,function(photo){   
					                Bikes.bikePhoto($scope.bike.id,$cookieStore.get('token'),photo);
					            });
				            	$scope.error.errorState=true;
					            $scope.error.message=data.message;
					            $interval(function(){
			           		         $window.history.back();
					           },3000)
							}
							else{
							   $scope.error.errorState=true;
					           $scope.error.message=data.message;
							}
						});
				}
				catch(e)
				{
					$ctrl.message="e";
				}
				
			}
			else{		
				$scope.error.errorState=true;
				$scope.error.message="Datos incompletos";
			}
		}
		else{

			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}
})
.controller('newReportCtrl',function($scope,$interval,ValidateForm,$log,$document,fileReader,CookieManager,$cookieStore,$cookies,$window,Reports,getParams,Login) {
	$scope.Report={title:"",lead:""}
	$scope.newReport={reportType:{name:"reportType",data:null,type:"integer"},
			coordinates:{name:"coordinates",data:null,type:"coordinates"},
			idBike:{name:"idBike",data:null,type:"integer"},
			reportDetails:{name:"reportDetails",data:null,type:"text"},
			reportName:{name:"reportName",data:null,type:"string"},
			};
    $scope.islogged=false;
	$scope.nickname;
	$scope.files=[];
	$scope.images=[];
	
	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');

    		var bikeId=getParams().idBike;
			var reportType=getParams().reportType;
			$scope.newReport.reportType.data=reportType;
			$scope.newReport.idBike.data=bikeId;
			$scope.newReport.reportName.data=Reports.setReportName(bikeId,$scope.nickname);

			$scope.setTitles(reportType);

    	}else{
    		$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.getFile = function () {
	        fileReader.readAsDataUrl($scope.files, $scope)
          		.then(function(result) {
          			$scope.images=result;
         	 });		
    };

	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.bike;

	$scope.createReport=function(isValid){
		if(isValid){
			$scope.errors=ValidateForm.fmValid($scope.newReport);
			$scope.formState=ValidateForm.formState($scope.errors);

			//console.log($scope.newReport)
			if($scope.formState){
				try{
		            $scope.error.errorState=true;
		            $scope.error.message="Generando reporte. Por favor espera!";	
					Reports.insertReport($scope.newReport,$cookieStore.get('token'))
						.then(function(data){
							angular.forEach($scope.files,function(photo){
					                Reports.reportPhoto(data.reportId,$cookieStore.get('token'),photo);
					            });

					            $scope.error.errorState=true;
					            $scope.error.message="Reporte generado satisfactoriamente";	
					            $interval(function(){
			           		         $window.history.back();
					            },2000);
						});
				}
				catch(e)
				{
					$ctrl.message="e";
				}
				
			}
			else{		
				$scope.error.errorState=true;
				$scope.error.message="Datos incompletos";
			}
		}
		else{

			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}
	$scope.getCoordinates=function(event){
		$scope.newReport.coordinates.data=event.latLng.lat()+","+event.latLng.lng()
	}
	$scope.setTitles=function(reportType){
		switch(reportType){
			case "1":
				$scope.Report.title="Te robaron la bicicleta?";
				$scope.Report.lead="Reportala con bipo y tendrás más posibilidades de encontrarla";
			break;

			case "2":
				$scope.Report.title="Super, recuperaste la bicicleta!";
				$scope.Report.lead="Cuentanos como la recuperaste?";
			break;

			case "4":
				$scope.Report.title="Ayudanos a reportar las bicicletas robadas";
				$scope.Report.lead="Si viste una bicicleta robada, cuentanos donde la viste?";
			break;	
		}
	}
})
.controller('loginUserCtrl',function ($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};

  	$scope.login={email:{name:"email",data:null,type:"email"},
				password:{name:"password",data:null,type:"password"}
				};
	$scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		$window.location.href='home';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }			
	$scope.loginUser=function(isValid){
		
		if(isValid){

			$scope.errors=ValidateForm.fmValid($scope.login);
			$scope.formState=ValidateForm.formState($scope.errors);
			
			if($scope.formState){
				$ctrl.message="Iniciando Sesión...";
				$scope.error.errorState=false;
				try{
					Login.loginUser($scope.login)
						.then(function(data){
							console.log(data);
							if(!data.error){
								CookieManager.writeCookie(data.user[0]);			
								$scope.error.message="Inicio de sesión exitoso";
								$scope.error.errorState=true;
		   						//Redireccion al home de usuario
	   							$window.location.href='home';

							}
							else
							{
								$scope.error.errorState=data.error;
								$scope.error.message=data.message;
							}
							});

				}
				catch(e)
				{
					$ctrl.message="e";
				}
			}
			else{
				$scope.error.errorState=true;
				$scope.error.message="Datos incompletos";
			}
		}
		else{
			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}
	$scope.forgetPass=function(){
		$window.location.href='forgetPass';
	}
})
.controller('recoverPassCtrl', function ($scope,ValidateForm,setPass, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager,$window,getParams) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};

  	$scope.recover={password:{name:"password",data:null,type:"password"},
  					confirm:{name:"confirm",data:null,type:"password"},
				};
	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    	}else{
    		//$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }	
	$scope.recoverPass=function(isValid){
		if(isValid){

			$scope.errors=ValidateForm.fmValid($scope.recover);
			$scope.errors.confirmPass=ValidateForm.comparePass($scope.recover.password.data,$scope.recover.confirm.data);
			$scope.formState=ValidateForm.formState($scope.errors);
			if($scope.formState){
				try{
					$scope.recover.token={name:"token",data:getParams().uid,type:"password"};
					setPass.setPass($scope.recover)
						.then(function(data){
							if(!data.error){
								$scope.error.message="Se ha restaurado tu contraseña";
								$scope.error.errorState=true;

							}
							else
							{
								$scope.error.errorState=true;
								$scope.error.message=data.message;
							}
							});

				}
				catch(e)
				{
					$scope.error.errorState=true;
					$scope.error.message=e;
				}
			}
			else{
				$scope.error.errorState=true;
				$scope.error.message="Datos incompletos";
			}
		}
		else{
			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}
})
.controller('forgetPassCtrl', function ($scope,ValidateForm,RecoverPass, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager,$window,getParams,Login) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};

  	$scope.recover={email:{name:"email",data:null,type:"email"}
				};
	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    	}else{
    		$window.location.href='home';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }	
	$scope.forgetPass=function(isValid){
		console.log(isValid)

		if(isValid){

			$scope.errors=ValidateForm.fmValid($scope.recover);
			$scope.formState=ValidateForm.formState($scope.errors);
			if($scope.formState){
				try{
					console.log("1")
					RecoverPass.recoverPass($scope.recover)
						.then(function(data){
							if(!data.error){
								console.log("2")
								$scope.error.message="Se ha enviado un correo con las instrucciones para reestablecer tu contraseña ";
								$scope.error.errorState=true;

							}
							else
							{
								console.log("3")
								$scope.error.errorState=true;
								$scope.error.message=data.message;
							}
							});

				}
				catch(e)
				{
					$scope.error.errorState=true;
					$scope.error.message=e;
				}
			}
			else{
				$scope.error.errorState=true;
				$scope.error.message="Datos incompletos";
			}
		}
		else{
			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}
})
.controller('homeCtrl',function ($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager,Reports) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.reports={}
	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		Reports.getLastReports()
    			.then(function(data){
					$scope.reports=data.reports;
					});
    			

	    	}else{
    		$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				console.log(data)
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.setNewReport=function(idBike){
    	$window.location.href='newReport?idBike='+idBike+'&reportType=4';
    }
    $scope.isMyBike=function(owner){
    	if(owner===$scope.nickname){
    		return true;
    	}
    	else{
   			return false;
    	}

    }

})
.controller('contactCtrl',function ($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager,Reports) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
	    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				console.log(data)
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
})
.controller('profileCtrl', function($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager,Reports,Bikes){
	var $ctrl = this;
		var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.userName;
	$scope.nickname;
	$scope.reports={};
	$scope.user={name:null,
			lastName:null,
			email:null,
			document:null,
			cellphone:null,
			nickname:null
		}
	$scope.bikes={};
	$scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.userName=$cookieStore.get('name')+" "+$cookieStore.get('lastName');
    		$scope.nickname=$cookieStore.get('nickname');
			
			$scope.getUserInfo();  			

	    	}else{
    		$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.getUserInfo=function(){
    	$scope.user.name=$cookieStore.get('name');
    	$scope.user.lastName=$cookieStore.get('lastName');
    	$scope.user.document=$cookieStore.get('document');
    	$scope.user.email=$cookieStore.get('email');
    	$scope.user.cellphone=$cookieStore.get('cellphone');
    	$scope.user.nickname=$cookieStore.get('nickname');
    }
    $scope.getBikes=function(){
    	Bikes.bikesByUser($cookieStore.get('token'))
    			.then(function(data){
					$scope.bikes=data.bikes;
					console.log(data);
					});
    }
    $scope.getReports=function(){
    	Reports.reportsByUser($cookieStore.get('token'))
    			.then(function(data){
					$scope.reports=data.reports;

					});
    }
    $scope.showBike=function(bikename){
    	$window.location.href='bike?bikename='+bikename+'&user='+$cookieStore.get('token');
    }
    $scope.addNewBike=function(){
    	$window.location.href='registroBicicleta';
    }
    $scope.UpdatePass=function(){
    	$window.location.href='recoverPass?uid='+$cookieStore.get('token');
    }
    $scope.setNewReport=function(idBike,reportType){
    	$window.location.href='newReport?idBike='+idBike+'&reportType='+reportType;
    }
})
.controller('reportCtrl',function ($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager,Reports,getParams) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.report={}
	var reportId=getParams().reportId;
	$scope.taxiData;
	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		if(reportId!=undefined){
    			Reports.getReportById(reportId)
    			.then(function(data){
					$scope.report=data.reports;
					if($scope.report!=undefined){
						var coordinates= $scope.report.googlemapscoordinate.split(',');
						$scope.report.latitude=coordinates[0];
						$scope.report.longitude=coordinates[1];

					}
					else{
						$scope.error.errorState=true;
						$scope.error.message=data.message;
					}

				});	
    		}
    		else{
    			
    			$scope.error.errorState=true;
    			$scope.error.message="Ocurrió un error al cargar el reporte";
    			console.log($scope.error);
    		}    			
    	}
    	else{
		$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.setNewReport=function(){
    	$window.location.href='newReport?idBike='+$scope.report.idBike+'&reportType=4';
    }

})
.controller('bikeCtrl',function ($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager,Bikes,getParams) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.bike={}
	$scope.taxiData;
	var user=getParams().user;
	 $scope.checkLogin=function(){
 		var bikename=getParams().bikename;
 		

    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		if(bikename!=undefined && user!=undefined){
    			Bikes.bikeByUser(user,bikename)
    			.then(function(data){
					if(data.bikes!= undefined){
						$scope.bike=data.bikes[0];
					}
					else{
						$scope.error.errorState=true;
						$scope.error.message=data.message;

					}

				});	
    		}
    		else{
    			
    			$scope.error.errorState=true;
    			$scope.error.message="Ocurrió un error al cargar la bicicleta";
    		}    			
    	}
    	else{
		$window.location.href='login';
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.setNewReport=function(){
    	$window.location.href='newReport?idBike='+$scope.report.idBike+'&reportType=4';
    }
    $scope.setBikeDefault=function(id){
    	Bikes.setBikeDefault(id,user)
    		.then(function(data){
    			$scope.error.errorState=true;
    			$scope.error.message=data.message;
    		});
    }
    $scope.deleteBike=function(id){
    	Bikes.deleteBike(id,user)
    		.then(function(data){
    			$scope.error.errorState=true;
    			$scope.error.message=data.message;
    		});
    }
    $scope.updateBike=function(id){
    	$window.location.href='updateBike?idBike='+id+'&user='+$cookieStore.get('token');
    }

})
.controller('stolenBikesCtrl',function ($scope,$log,$document,$interval,$window,$cookies,$cookieStore,CookieManager,Reports,ValidateForm,$filter,Login) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.reports={};
	var date=new Date();
	date=date.setDate(date.getDate()+1);
	var today=$filter('date')(date,'yyyy-M-dd');
	var year=new Date().getFullYear();
	$scope.fmFilter={fhInicio:{name:"fhInicio",data:null,type:"datetime"},
				   fhFinal:{name:"fhFinal",data:null,type:"datetime"}
				};

	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');	
	   	}
	   	Reports.getReports(1,year+"-01-01",today)
    			.then(function(data){
					$scope.reports=data.reports;
					if($scope.reports==undefined){
			    		$scope.error.message=data.message;
    					$scope.error.errorState=true;
					}
				});

    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.filter=function(){
    	$scope.fmFilter.fhInicio.data=document.getElementById('fhInicio').value;
    	$scope.fmFilter.fhFinal.data=document.getElementById('fhFinal').value;
	    $scope.errors=ValidateForm.fmValid($scope.fmFilter);
		$scope.formState=ValidateForm.formState($scope.errors);
			
		if($scope.formState){
    		$scope.error.errorState=false;
    		Reports.getReports(1,$scope.fmFilter.fhInicio.data,$scope.fmFilter.fhFinal.data)
    			.then(function(data){
					$scope.reports=data.reports;
					if($scope.reports==undefined){
			    		$scope.error.message=data.message;
    					$scope.error.errorState=true;
					}
				});
    	}
    	else{
    		$scope.error.message="Debes seleccionar las fechas de busqueda";
    		$scope.error.errorState=true;
    		console.log($scope.error);

    	}
    }
    $scope.setNewReport=function(idBike){
    	$window.location.href='newReport?idBike='+idBike+'&reportType=4';
    }
    $scope.isMyBike=function(owner){
    	if(owner===$scope.nickname){
    		return true;
    	}
    	else{
   			return false;
    	}

    }

})
.controller('recoveredBikesCtrl',function ($scope,$log,$document,$interval,$window,$cookies,$cookieStore,CookieManager,Reports,ValidateForm,$filter,Login) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.reports={};
	var date=new Date();
	date=date.setDate(date.getDate()+1);
	var today=$filter('date')(date,'yyyy-M-dd');
	var year=new Date().getFullYear();
	$scope.fmFilter={fhInicio:{name:"fhInicio",data:null,type:"datetime"},
				   fhFinal:{name:"fhFinal",data:null,type:"datetime"}
				};

	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');	
	   	}
	   	Reports.getReports(2,year+"-01-01",today)
    			.then(function(data){
					$scope.reports=data.reports;
					if($scope.reports==undefined){
			    		$scope.error.message=data.message;
    					$scope.error.errorState=true;
					}
				});

    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.filter=function(){
    	$scope.fmFilter.fhInicio.data=document.getElementById('fhInicio').value;
    	$scope.fmFilter.fhFinal.data=document.getElementById('fhFinal').value;
	    $scope.errors=ValidateForm.fmValid($scope.fmFilter);
		$scope.formState=ValidateForm.formState($scope.errors);
			
		if($scope.formState){
    		$scope.error.errorState=false;
    		Reports.getReports(2,$scope.fmFilter.fhInicio.data,$scope.fmFilter.fhFinal.data)
    			.then(function(data){
					$scope.reports=data.reports;
					if($scope.reports==undefined){
			    		$scope.error.message=data.message;
    					$scope.error.errorState=true;
					}
				});
    	}
    	else{
    		$scope.error.message="Debes seleccionar las fechas de busqueda";
    		$scope.error.errorState=true;
    		console.log($scope.error);

    	}
    }
    $scope.isMyBike=function(owner){
    	if(owner===$scope.nickname){
    		return true;
    	}
    	else{
   			return false;
    	}

    }

})
.controller('foundBikesCtrl',function ($scope,$log,$document,$interval,$window,$cookies,$cookieStore,CookieManager,Reports,ValidateForm,$filter,Login) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.reports={};
	var date=new Date();
	date=date.setDate(date.getDate()+1);
	var today=$filter('date')(date,'yyyy-M-dd');
	var year=new Date().getFullYear();
	$scope.fmFilter={fhInicio:{name:"fhInicio",data:null,type:"datetime"},
				   fhFinal:{name:"fhFinal",data:null,type:"datetime"}
				};

	 $scope.checkLogin=function(){
    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');	
	   	}
	   	Reports.getReports(4,year+"-01-01",today)
    			.then(function(data){
					$scope.reports=data.reports;
					if($scope.reports==undefined){
			    		$scope.error.message=data.message;
    					$scope.error.errorState=true;
					}
				});

    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
    $scope.filter=function(){
    	$scope.fmFilter.fhInicio.data=document.getElementById('fhInicio').value;
    	$scope.fmFilter.fhFinal.data=document.getElementById('fhFinal').value;
	    $scope.errors=ValidateForm.fmValid($scope.fmFilter);
		$scope.formState=ValidateForm.formState($scope.errors);
			
		if($scope.formState){
    		$scope.error.errorState=false;
    		Reports.getReports(4,$scope.fmFilter.fhInicio.data,$scope.fmFilter.fhFinal.data)
    			.then(function(data){
					$scope.reports=data.reports;
					if($scope.reports==undefined){
			    		$scope.error.message=data.message;
    					$scope.error.errorState=true;
					}
				});
    	}
    	else{
    		$scope.error.message="Debes seleccionar las fechas de busqueda";
    		$scope.error.errorState=true;
    		console.log($scope.error);

    	}
    }
    $scope.isMyBike=function(owner){
    	if(owner===$scope.nickname){
    		return true;
    	}
    	else{
   			return false;
    	}

    }

})

.controller('indexCtrl', function($scope,Login,$window,$cookies,$cookieStore,CookieManager,$document){
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.reports={}
	 $scope.checkLogin=function(){

    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');

    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
})
.controller('termsCtrl', function($scope,Login,$window,$cookies,$cookieStore,CookieManager,$document){
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.reports={}
	 $scope.checkLogin=function(){

    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    	}
    }
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
})
.controller('zoneContainerCtrl', function(NgMap,Reports,$scope,Login,$window,$cookies,$cookieStore,CookieManager,$document,$filter,ValidateForm){

	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	$scope.taxiData = [];
	var date=new Date();
	date=date.setDate(date.getDate()+1);
	var today=$filter('date')(date,'yyyy-M-dd');
	var year=new Date().getFullYear();
	$scope.fmFilter={fhInicio:{name:"fhInicio",data:null,type:"datetime"},
				   fhFinal:{name:"fhFinal",data:null,type:"datetime"}
				};
	var heatmap;
            $ctrl.googleMapsUrl="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZm14lpvD7-Pahl6cCSwIXAlquw1p46-U"

            NgMap.getMap().then(function(map) {
                $ctrl.map = map;
                heatmap = $ctrl.map.heatmapLayers.foo;
                var center = map.getCenter();
 				google.maps.event.trigger(map, "resize");
 				map.setCenter(center);
 				$ctrl.changeRadius();
                heatmap.setMap(heatmap.getMap());

            });
            $ctrl.toggleHeatmap= function(event) {
                heatmap.setMap(heatmap.getMap() ? null : $ctrl.map);
                $ctrl.changeGradient();
            };
            $ctrl.changeGradient = function() {
                var gradient = [
                    'rgba(0, 255, 255, 0)',
                    'rgba(0, 255, 255, 1)',
                    'rgba(0, 191, 255, 1)',
                    'rgba(0, 127, 255, 1)',
                    'rgba(0, 63, 255, 1)',
                    'rgba(0, 0, 255, 1)',
                    'rgba(0, 0, 223, 1)',
                    'rgba(0, 0, 191, 1)',
                    'rgba(0, 0, 159, 1)',
                    'rgba(0, 0, 127, 1)',
                    'rgba(63, 0, 91, 1)',
                    'rgba(127, 0, 63, 1)',
                    'rgba(191, 0, 31, 1)',
                    'rgba(255, 0, 0, 1)'
                ]
                heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
            }
            $ctrl.changeRadius = function() {
                heatmap.set('radius', heatmap.get('radius') ? null : 20);
            }
            $ctrl.changeOpacity = function() {
                heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
            }
    $scope.loadMaps=function(){
		Reports.getReports(1,year+"-01-01",today)
        .then(function(data){
        	$scope.reports=data.reports;
        	angular.forEach(data.reports,function (value, key) {
				$scope.taxiData.push(new google.maps.LatLng(value.latitude, value.longitude));
            });
        	//$scope.bikeTypes=data.biketypes;
        });
    }
	 $scope.checkLogin=function(){

    	if(CookieManager.login()){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');

    	}
    }
        $scope.filter=function(){
    	$scope.fmFilter.fhInicio.data=document.getElementById('fhInicio').value;
    	$scope.fmFilter.fhFinal.data=document.getElementById('fhFinal').value;
	    $scope.errors=ValidateForm.fmValid($scope.fmFilter);
		$scope.formState=ValidateForm.formState($scope.errors);
			
		if($scope.formState){
    		$scope.error.errorState=false;
    		Reports.getReports(1,$scope.fmFilter.fhInicio.data,$scope.fmFilter.fhFinal.data)
    			.then(function(data){
    				$scope.taxiData=[];
					angular.forEach(data.reports,function (value, key) {
						$scope.taxiData.push(new google.maps.LatLng(value.latitude, value.longitude));
            		});
	            NgMap.getMap().then(function(map) {
                $ctrl.map = map;
                heatmap = $ctrl.map.heatmapLayers.foo;
                console.log("seeeet")
                var center = map.getCenter();
 				google.maps.event.trigger(map, "resize");
 				map.setCenter(center);
 				$ctrl.changeRadius();
                heatmap.setMap(heatmap.getMap());

           		 });
			});

    	}
	}
	$scope.logout=function(){
 		Login.logout($cookieStore.get('token'))
 			.then(function(data){
 				if(!data.error){
 					if(CookieManager.remove()){
				 		$window.location.href='inicio';
 					}
 				}
 			})
	 	
    }
})
.controller('HeatMapCtrl',function (NgMap,heatMapResource,$scope) {
    console.log('Mapa');
    $scope.taxiData = [];
    /*$scope.taxiData = [
        new google.maps.LatLng(4.651006, -74.066541),
        new google.maps.LatLng(4.677319, -74.110354),
        new google.maps.LatLng(4.674245, -74.104251),
        new google.maps.LatLng(4.689753, -74.068944),
        new google.maps.LatLng(4.738853, -74.090383),
        new google.maps.LatLng(4.739813, -74.087815),
        new google.maps.LatLng(4.733947, -74.094018),
        new google.maps.LatLng(4.734866, -74.097512),
        new google.maps.LatLng(4.735668, -74.095999),
        new google.maps.LatLng(4.738214, -74.099485)
    ];*/
    $scope.loadMaps=function(){
		heatMapResource.getReports()
        .then(function(data){
        	console.log(data.reports);
        	angular.forEach(data.reports,function (value, key) {
				$scope.taxiData.push(new google.maps.LatLng(value.latitude, value.longitude));
            });
            var heatmap, vm = this;
            vm.googleMapsUrl="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZm14lpvD7-Pahl6cCSwIXAlquw1p46-U"

            NgMap.getMap().then(function(map) {
                vm.map = map;
                heatmap = vm.map.heatmapLayers.foo;
                var center = map.getCenter();
 				google.maps.event.trigger(map, "resize");
 				map.setCenter(center);

 				vm.changeRadius();
				heatmap.setMap(heatmap.getMap() );

            });
            vm.toggleHeatmap= function(event) {
                heatmap.setMap(heatmap.getMap() ? null : vm.map);
                vm.changeGradient();
            };
            vm.changeGradient = function() {
                var gradient = [
                    'rgba(0, 255, 255, 0)',
                    'rgba(0, 255, 255, 1)',
                    'rgba(0, 191, 255, 1)',
                    'rgba(0, 127, 255, 1)',
                    'rgba(0, 63, 255, 1)',
                    'rgba(0, 0, 255, 1)',
                    'rgba(0, 0, 223, 1)',
                    'rgba(0, 0, 191, 1)',
                    'rgba(0, 0, 159, 1)',
                    'rgba(0, 0, 127, 1)',
                    'rgba(63, 0, 91, 1)',
                    'rgba(127, 0, 63, 1)',
                    'rgba(191, 0, 31, 1)',
                    'rgba(255, 0, 0, 1)'
                ]
                heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
            }
            vm.changeRadius = function() {
                heatmap.set('radius', heatmap.get('radius') ? null : 30);
            }
            vm.changeOpacity = function() {
                heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
            }
        	//$scope.bikeTypes=data.biketypes;
        });
    }
    
    /*heatMapResource.query(function (completed, headers) {
        console.log(completed);
    })*/
});