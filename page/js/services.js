angular.module('bipoApp.services', [])

//REALIZA LAS PETICIONES POST DENTRO DEL APLICATIVO
.factory('PostAjax',function($http,$q){
	var postAjax={};
    //pruebas
    var url="http://localhost/bipo/services/v1/";
    //REGISTRO DE USUARIO
	postAjax.registerUser=function(data){
        //postAjax.user={}
        var serviceUrl=url+"register"
		var params={name:data.name.data,
                    lastName:data.lastName.data,
                    email:data.email.data,
                    birthdate:data.birthdate.data,
                    cellphone:data.cellphone.data,
                    document:data.document.data,
                    password:data.password.data
                };
        var dfd = $q.defer();
		//console.log(params);
	    $http.post(serviceUrl,params)
			.then(function(response){
                return response.data;                
            },
            function(error){
                return error.data;
            });
	}

    return postAjax;		
})
//VALIDA TODOS LOS FORMULARIOS
.factory('ValidateForm', function(){
	var validate={};
	var error={	number:{
						telefono:'El teléfono debe contener unicamente números y tener entre 7 y 8 digitos',
						document:'El documento debe contener unicamente números y tener entre 5 y 12 digitos',
						cellphone:'El número de celular debe contener unicamente números y tener 10 digitos'
			    },
			    alpha:{
			    	error:'Debe contener unicamente letras'
			    },
                birthdate:{
                    error:"Debes ser mayor de 18 años para registrarte"
                },
                checkbox:{
                    error:"Debes aceptar los terminos y condiciones"
                },
                password:{
                    error:"Contraseña no valida, debe contener una letra mayúscula,"+
                            "\n una letra minúscula, un número y minimo 6 carácteres."
                }
			}
	validate.fmFields=[];
	validate.fmValid=function(data){
		
		for (var key in data) {
  			if (data.hasOwnProperty(key)) {
    			var field = data[key];
    			
    			switch(field.type){
    				case 'number':
                        switch(field.name){
                            case 'document':
                                if(/^[0-9]{5,12}$/.test(field.data)){
                                    validate.fmFields[field.name]={valid:true};
                                }
                                else{
                                    validate.fmFields[field.name]={
                                    valid:false,
                                    error:error.number[field.name]
                                    };
                                }
                            break;
                            case 'telefono':
                                if(/^[0-9]{7,8}$/.test(field.data)){
                                    validate.fmFields[fields.name]={valid:true};
                                }
                                else{
                                    validate.fmFields[field.name]={
                                    valid:false,
                                    error:error.number[field.name]
                                    };
                                }
                            break;
                            case 'cellphone':
                                if(/^[0-9]{10}$/.test(field.data)){
                                    validate.fmFields[field.name]={valid:true};
                                }
                                else{
                                    validate.fmFields[field.name]={
                                    valid:false,
                                    error:error.number[field.name]
                                    };
                                }
                            break;
                        }
                    break;
    				case 'alpha':
    					if(/^([a-zA-Zñáéíóú]+[\s]*)+$/.test(field.data)){
    						validate.fmFields[field.name]={
                                valid:true
                            };
    					}else{
                            validate.fmFields[field.name]={
                                    valid:false,
                                    error:error.alpha.error
                            };
                        }
					break;
                    case 'birthdate':
                        if (field.data==null){
                            validate.fmFields[field.name]={
                                    valid:false,
                                    error:error.birthdate.error
                                    };
                        }
                        else{
                            var userDate=new Date().getFullYear()-new Date(field.data).getFullYear();
                            if(userDate>=18){
                                validate.fmFields[field.name]={valid:true};
                            }
                            else{
                                validate.fmFields[field.name]={
                                    valid:false,
                                    error:error.birthdate.error
                                    };
                            }
                        }
                    break;
                    case 'checkbox':
                        if(field.data==true){
                             validate.fmFields[field.name]={valid:true};
                        }
                        else{
                            validate.fmFields[field.name]={
                                valid:false,
                                error:error.checkbox.error};
                        }
                    break;
                        
                    case 'password':
                        if(/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/.test(field.data)){
                            validate.fmFields[field.name]={
                                valid:true
                            };
                        }
                        else{
                            validate.fmFields[field.name]={
                                valid:false,
                                error:error.password.error};
                        }
                    break;
                         
                    default:
                        validate.fmFields[field.name]={
                                    valid:true,
                                    }
                    break;
                    
    			}

  			}
		}
		//console.log(validate);
		return validate.fmFields;
	}

    validate.formState=function(data){
        var state=true;
        for(var key in data){
            if (data.hasOwnProperty(key)){
                if(data[key].valid===true){
                    state=true;
                }
                else{
                    state=false;
                    return state;
                }
            }
        }
        return state;
    }
    validate.comparePass=function(password,confirmPass){
        var result={valid:false,error:"Las contraseñas no coinciden"};
        if(password===confirmPass){
            result.valid=true
            result.error=""
            return result;
        }
        else{
            result.valid=false
            return result;
        }
    }
	return validate;
})

/*.factory('Modal',function($uibModal, $log, $document){
    var modal={};
    var controller;
    modal.open(size,parentSelector)

})*/;

