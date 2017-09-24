angular.module('bipoApp.services', [])

.value('url', "http://www.bipoapp.com/services/v1/")
//REGISTRO DE USUARIO
.factory('Register',function($http,$q,url){
    //REGISTRO DE USUARIO
    return{
        register: function(data){
        //postAjax.user={}
        var serviceUrl=url+"register"
        var result=null;
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
            .then(function successCallback(response){
                dfd.resolve(response.data);             
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//INICIO DE SESION
.factory('Login',function($http,$q,url){
    //login
    return{
        login: function(data){
        //postAjax.user={}
        var serviceUrl=url+"login"
        var result=null;
        var params={email:data.email.data,
                    password:data.password.data
                };
        var dfd = $q.defer();
        //console.log(params);
        $http.get(serviceUrl,{params:params})
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//RECUPERAR CONTRASEÑA
.factory('RecoverPass',function($http,$q,url){
    //login
    return{
        RecoverPass: function(data){
        //postAjax.user={}
        var serviceUrl=url+"recoverPass"
        var result=null;
        var params={email:data.email.data
                };
        var dfd = $q.defer();
        //console.log(params);
        $http.post(serviceUrl,params)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//ACTUALIZAR CONTRASEÑA
.factory('UpdatePass',function($http,$q,url){
    //login
    return{
        UpdatePass: function(data){
        //postAjax.user={}
        var serviceUrl=url+"updatePassword"
        var result=null;
        var params={email:data.email.data,
                    password:data.password.data,
                    newPassword:data.newPassword.data,
                };
        var dfd = $q.defer();
        //console.log(params);
        $http.post(serviceUrl,params)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//SET CONTRASEÑA
.factory('setPass',function($http,$q,url){
    //login
    return{
        setPass: function(data){
        //postAjax.user={}
        var serviceUrl=url+"setPassword"
        var result=null;
        var params={password:data.password.data,
            token:data.token.data                    
                };
        var dfd = $q.defer();

        $http.post(serviceUrl,params)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//OBTENER MARCAS DE BICICLETA
.factory('Brands',function($http,$q,url){
    //login
    return{
        brands: function(data){
        //postAjax.user={}
        var serviceUrl=url+"brands"
        var result=null;
        var dfd = $q.defer();
        //console.log(params);
        $http.get(serviceUrl)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//OBTENER COLORES DE BICICLETA
.factory('Colors',function($http,$q,url){
    //login
    return{
        colors: function(data){
        //postAjax.user={}
        var serviceUrl=url+"bikeColors"
        var result=null;
        var dfd = $q.defer();
        //console.log(params);
        $http.get(serviceUrl)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//OBTENER TIPOS DE BICICLETA
.factory('bikeTypes',function($http,$q,url){
    //login
    return{
        bikeTypes: function(data){
        //postAjax.user={}
        var serviceUrl=url+"bikeTypes"
        var result=null;
        var dfd = $q.defer();
        //console.log(params);
        $http.get(serviceUrl)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//OBTENER ESTADOS DE BICICLETA
.factory('bikeStates',function($http,$q,url){
    //login
    return{
        colors: function(data){
        //postAjax.user={}
        var serviceUrl=url+"bikeStates"
        var result=null;
        var dfd = $q.defer();
        //console.log(params);
        $http.get(serviceUrl)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//OBTENER ESTADOS DE BICICLETA
.factory('Reports',function($http,$q,url){
    //login
    var reports={};
    reports.getLastReports=function(){
        var serviceUrl=url+"lastReports"
        var result=null;
        var dfd = $q.defer();
        //console.log(params);
        $http.get(serviceUrl)
            .then(function successCallback(response){
                dfd.resolve(response.data);   
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }
    return reports; 
})
//INICIO DE SESION
.factory('setPreferences',function($http,$q,url){
    //login
    return{
        setPreferences: function(data){
        //postAjax.user={}
        var serviceUrl=url+"setPreferences"
        var result=null;
        console.log(data);
        var params={token:data.token,
                    emailReceiver:data.emailReceiver,
                    photoPublication:data.photoPublication,
                    enableReportUbication:data.enableReportUbication,
                    enableLocationUbication:data.enableLocationUbication
                };
        var dfd = $q.defer();
        //console.log(params);
        $http.post(serviceUrl,params)
            .then(function successCallback(response){
                dfd.resolve(response.data);
            },
            function errorCallback(error){
                dfd.resolve(error.data); 
            });
            
        return dfd.promise;
    }      } 
})
//INICIO DE SESION
.factory('CookieManager',function($cookies,$cookieStore){
    var cookieManager={};
    cookieManager.writeCookie=function(data){

        $cookieStore.put('nickname',data.nickname);
        $cookieStore.put('name',data.name);
        $cookieStore.put('lastName',data.lastname);
        $cookieStore.put('id',data.id);
        $cookieStore.put('email',data.email);
        $cookieStore.put('token',data.token);  

    }
    cookieManager.remove=function(){
        $cookieStore.remove("nickname");
        $cookieStore.remove("name");
        $cookieStore.remove("lastName");
        $cookieStore.remove("id");
        $cookieStore.remove("email");
        $cookieStore.remove("token");
        return true;
    }

    cookieManager.login=function(){
        if(($cookieStore.get('nickname')!=undefined && $cookieStore.get('nickname')!=null)&&
           ($cookieStore.get('name')!=undefined && $cookieStore.get('name')!=null)&&
           ($cookieStore.get('lastName')!=undefined && $cookieStore.get('lastName')!=null)&&
           ($cookieStore.get('id')!=undefined && $cookieStore.get('id')!=null)&&
           ($cookieStore.get('email')!=undefined && $cookieStore.get('email')!=null)&&
           ($cookieStore.get('token')!=undefined && $cookieStore.get('token')!=null)){

            return true
        }
        else{
            return false;
        }
    }
    return cookieManager;
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
                publish:{
                    error:"Debes aceptar la publicación en redes sociales"
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
                    case 'publish':
                        if(field.data==true){
                             validate.fmFields[field.name]={valid:true};
                        }
                        else{
                            validate.fmFields[field.name]={
                                valid:false,
                                error:error.publish.error};
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
.factory('getParams',[ '$window',function(){
    return function getParams(){
        var paramsObject={};
        window.location.search.replace(/\?/,'').split('&').map(function(o){ paramsObject[o.split('=')[0]]= o.split('=')[1]});
        return paramsObject;
    };
}])
.factory('fileReader', function($q,$log){
    var fileReader ={};

    fileReader.onLoad = function(reader, deferred, scope) {
            return function () {
                scope.$apply(function () {
                    deferred.resolve(reader.result);
                });
            };
        };
 
    fileReader.onError = function (reader, deferred, scope) {
            return function () {
                scope.$apply(function () {
                    deferred.reject(reader.result);
                });
            };
        };
 
    fileReader.onProgress = function(reader, scope) {
            return function (event) {
                scope.$broadcast("fileProgress",
                    {
                        total: event.total,
                        loaded: event.loaded
                    });
            };
        };
 
    fileReader.getReader = function(deferred, scope) {
            var reader = new FileReader();
            reader.onload = fileReader.onLoad(reader, deferred, scope);
            reader.onerror = fileReader.onError(reader, deferred, scope);
            reader.onprogress = fileReader.onProgress(reader, scope);
            return reader;
        };
 
    fileReader.readAsDataURL = function (file, scope) {
            var deferred = $q.defer();
             
            var reader = fileReader.getReader(deferred, scope);         
            reader.readAsDataURL(file);
             console.log(reader);
            return deferred.promise;
        };
 
        return {
            readAsDataUrl: fileReader.readAsDataURL  
        };
    
})
    .factory('heatMapResource',function($http,$q){
        //login
        return{
            getReports: function(data){
                //postAjax.user={}
                var serviceUrl=url+"getReportsMaps"
                var result=null;
                var dfd = $q.defer();
                //console.log(params);
                $http.get(serviceUrl)
                    .then(function successCallback(response){
                            dfd.resolve(response.data);
                        },
                        function errorCallback(error){
                            dfd.resolve(error.data);
                        });

                return dfd.promise;
            }
        }

        /*['$resource',function ($resource) {
            return $resource('http://www.bipoapp.com/services/v1/getReportsMaps/:id',{id:'@id'},{
                query:{
                    method:'GET',
                    responseType: 'json',
                    headers:{
                        'Authorization':'650E01A1B8F9A4DA4A2040FF86E699B7',
                        'Access-Control-Allow-Methods':'*'
                    }
                }
            });*/
});
/*.factory('Modal',function($uibModal, $log, $document){
    var modal={};
    var controller;
    modal.open(size,parentSelector)

})*/;

