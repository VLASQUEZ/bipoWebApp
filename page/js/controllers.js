angular.module('bipoApp.controllers', ['ui.bootstrap'])
  
.controller('registerCtrl',function ($scope,ValidateForm,PostAjax,$uibModal, $log, $document,$interval){
	$scope.error={errorState:false,message:""};
	var $ctrl = this;
	$ctrl.animationsEnabled = true;

  	$ctrl.open = function (size, parentSelector) {
    var parentElem = parentSelector ? angular.element($document[0].querySelector('.modal-demo' + parentSelector)) : undefined;
    $scope.modalInstance = $uibModal.open({
      animation: $ctrl.animationsEnabled,
      ariaLabelledBy: 'modal-title',
      ariaDescribedBy: 'modal-body',
      templateUrl: 'modal.html',
      controller: 'registerCtrl',
      controllerAs: '$ctrl',
      size: size,
      appendTo: parentElem
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
			$scope.error.message="Cargando...";	
			$scope.register.birthdate.data=document.getElementById('fh2').value;
			$scope.errors=ValidateForm.fmValid($scope.register);
			$scope.errors.confirmPass=ValidateForm.comparePass($scope.register.password.data,$scope.register.confirmPass.data);
			$scope.formState=ValidateForm.formState($scope.errors);
			console.log($scope.formState)
			
			if($scope.formState){
				$scope.error.message="Registrando...";
				$ctrl.open('sm');
				$scope.error.errorState=false;
				$scope.response=PostAjax.registerUser($scope.register)
				if(!$scope.response.error){
					//mostrar modal y pasar al formulario de registro de
					//bicicletas
					$scope.error.message="Iniciando Sesión...";
					$interval(function() {
      					$scope.modalInstance.close();
      					//Redireccion a registro de bicicletas
					}, 3000,1);
				}
				else
				{
					$scope.error.errorState=$scope.response.error;
					$scope.error.message=$scope.response.message;
				}
			}
		}
		else{
			$scope.error.errorState=true;
			$scope.error.message="Datos incompletos";
		}

	}

});