angular.module('bipoApp.controllers', ['ngAnimate', 'ngSanitize','ui.bootstrap'])

.controller('registerCtrl',function ($scope,ValidateForm,Register,Login,setPreferences,CookieManager, $log, $document,$interval,$window,$cookies,$cookieStore){
	
	$scope.error={errorState:false,message:""};
	
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
									Login.login($scope.register)
										.then(function(data){
											//setearpreferencias
											CookieManager.writeCookie(data.user[0]);
											var preferences={token:$cookieStore.get('token'),
												emailReceiver:1,
												photoPublication:1,
												enableReportUbication:1,
												enableLocationUbication:1};
					      					setPreferences.setPreferences(preferences)
					      						.then(function(data){
					      							console.log(data);
					      						});
											$interval(function() {

						      					//Inicio de Sesion

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
									$scope.error.message=$scope.response.message;
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
.controller('registerBikeCtrl',function ($scope,ValidateForm,$uibModal, $log, $document,$interval,fileReader,CookieManager,$cookieStore,$cookies,$window,Colors,Brands,bikeTypes,bikeStates) {
	$scope.register={bikeName:{name:"name",data:null,type:"alpha"},
				brand:{name:"brand",data:null,type:"select"},
				color:{name:"color",data:null,type:"select"},
				bikeType:{name:"bikeType",data:null,type:"select"},
				bikeState:{name:"bikeState",data:null,type:"select"},
				document:{name:"document",data:null,type:"number"},
				email:{name:"email",data:null,type:"email"},
				cellphone:{name:"cellphone",data:null,type:"number"},
				password:{name:"password",data:null,type:"password"},
				confirmPass:{name:"confirmPass",data:null,type:"comparePass"},
				terms:{name:"terms",data:null,type:"checkbox"}
				};
    $scope.islogged=false;
	$scope.nickname;
	$scope.brands;
	$scope.colors;
	$scope.bikeTypes;
	 $scope.checkLogin=function(){
    	if(CookieManager.login){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		Colors.colors()
				.then(function(data){
					console.log(data);
					$scope.colors=data.bikeColor;
				});
    		Brands.brands()
				.then(function(data){
					console.log(data);
					$scope.brands=data.brands;
			});
    		bikeTypes.bikeTypes()
				.then(function(data){
					console.log(data);
					$scope.bikeTypes=data.biketypes;
			});

    	}else{
    		$window.location.href='inicio';
    	}
    }	
    $scope.getFile = function () {
        $scope.progress = 0;                                                                  
        fileReader.readAsDataUrl($scope.file, $scope)
                      .then(function(result) {
                          $scope.imageSrc = result;
                      });
    };

    $scope.$on("fileProgress", function(e, progress) {
        $scope.progress = progress.loaded / progress.total;
    });

	var $ctrl = this;
	$scope.error={errorState:false,message:""};



	$scope.registerBike=function(isValid){
		console.log($scope.register);
		if(isValid){
			$ctrl.message="Cargando...";	
			$scope.register.birthdate.data=document.getElementById('fh2').value;
			$scope.errors=ValidateForm.fmValid($scope.register);
			$scope.errors.confirmPass=ValidateForm.comparePass($scope.register.password.data,$scope.register.confirmPass.data);
			$scope.formState=ValidateForm.formState($scope.errors);
			
			if($scope.formState){
				$ctrl.message="Registrando...";
				
				$ctrl.open('sm');
				$scope.error.errorState=false;
				try{
					$scope.response=PostAjax.registerUser($scope.register);
					if(!$scope.response.error){
						//mostrar modal y pasar al formulario de registro de
						//bicicletas
						$ctrl.message="Iniciando Sesión...";
						$scope.response=PostAjax.loginUser($scope.register);
						$interval(function() {
	      					$scope.modalInstance.close();
	      					//Inicio de Sesion

	      					//Redireccion a registro de bicicletas
						}, 3000,1);
					}
					else
					{
						$scope.error.errorState=$scope.response.error;
						$scope.error.message=$scope.response.message;
					}
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
.controller('loginUserCtrl',function ($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};

  	$scope.login={email:{name:"email",data:null,type:"email"},
				password:{name:"password",data:null,type:"password"}
				};

	$scope.loginUser=function(isValid){
		
		if(isValid){

			$scope.errors=ValidateForm.fmValid($scope.login);
			$scope.formState=ValidateForm.formState($scope.errors);
			
			if($scope.formState){
				$ctrl.message="Iniciando Sesión...";
				$scope.error.errorState=false;
				try{
					Login.login($scope.login)
						.then(function(data){
							if(!data.error){
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
})
.controller('homeCtrl',function ($scope,ValidateForm,Login, $log, $document,$interval,$window,$cookies,$cookieStore,CookieManager) {
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$scope.islogged=false;
	$scope.nickname;
	 $scope.checkLogin=function(){
    	if(CookieManager.login){
    		$scope.islogged=true;
    		$scope.nickname=$cookieStore.get('nickname');
    		console.log($scope.nickname);
    	}else{
    		$window.location.href='inicio';
    	}
    }

})