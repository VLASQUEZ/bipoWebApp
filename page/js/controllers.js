angular.module('bipoApp.controllers', ['ui.bootstrap'])
  .controller('modalRegisterCtrl',function ($uibModalInstance,message)
  {
	var $ctrl = this;
	$ctrl.message=message;	
  })
.controller('registerCtrl',function ($scope,ValidateForm,PostAjax,$uibModal, $log, $document,$interval){
	
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$ctrl.animationsEnabled = true;
  	$ctrl.open = function (size,parentSelector) {
    var parentElem = parentSelector ? angular.element($document[0].querySelector('.modal-demo' + parentSelector)) : undefined;
    $scope.modalInstance = $uibModal.open({
      animation: $ctrl.animationsEnabled,
      ariaLabelledBy: 'modal-title',
      ariaDescribedBy: 'modal-body',
      templateUrl: 'modal.html',
      controller: 'modalRegisterCtrl',
      controllerAs: '$ctrl',
      size: size,
      appendTo: parentElem,
      resolve: {
        message: function () {
          return $ctrl.message;
        }
      }
    });

    $scope.modalInstance.result.then(function (selectedItem) {
      	$ctrl.selected = selectedItem;
    	}, function () {
      		$log.info('Modal dismissed at: ' + new Date());
    	});
  	};

	$scope.register={name:{name:"name",data:null,type:"alpha"},
					lastName:{name:"lastName",data:null,type:"alpha"},
					birthdate:{name:"birthdate",data:null,type:"birthdate"},
					document:{name:"document",data:null,type:"number"},
					email:{name:"email",data:null,type:"email"},
					cellphone:{name:"cellphone",data:null,type:"number"},
					password:{name:"password",data:null,type:"password"},
					confirmPass:{name:"confirmPass",data:null,type:"comparePass"},
					terms:{name:"terms",data:null,type:"checkbox"}
				};

	$scope.registerUser=function(isValid){
		
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
		}
		else{
			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}

});
.controller('registerBikeCtrl',function ($scope,ValidateForm,PostAjax,$uibModal, $log, $document,$interval){
	
	var $ctrl = this;
	$scope.error={errorState:false,message:""};
	$ctrl.animationsEnabled = true;
  	$ctrl.open = function (size,parentSelector) {
    var parentElem = parentSelector ? angular.element($document[0].querySelector('.modal-demo' + parentSelector)) : undefined;
    $scope.modalInstance = $uibModal.open({
      animation: $ctrl.animationsEnabled,
      ariaLabelledBy: 'modal-title',
      ariaDescribedBy: 'modal-body',
      templateUrl: 'modal.html',
      controller: 'modalRegisterCtrl',
      controllerAs: '$ctrl',
      size: size,
      appendTo: parentElem,
      resolve: {
        message: function () {
          return $ctrl.message;
        }
      }
    });

    $scope.modalInstance.result.then(function (selectedItem) {
      	$ctrl.selected = selectedItem;
    	}, function () {
      		$log.info('Modal dismissed at: ' + new Date());
    	});
  	};

	$scope.register={name:{name:"name",data:null,type:"alpha"},
					lastName:{name:"lastName",data:null,type:"alpha"},
					birthdate:{name:"birthdate",data:null,type:"birthdate"},
					document:{name:"document",data:null,type:"number"},
					email:{name:"email",data:null,type:"email"},
					cellphone:{name:"cellphone",data:null,type:"number"},
					password:{name:"password",data:null,type:"password"},
					confirmPass:{name:"confirmPass",data:null,type:"comparePass"},
					terms:{name:"terms",data:null,type:"checkbox"}
				};

	$scope.registerUser=function(isValid){
		
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
		}
		else{
			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}

});