angular.module('bipoApp',['bipoApp.controllers','bipoApp.routes','bipoApp.services','ui.bootstrap','ngAnimate', 'ngSanitize','ngCookies'])
.config(function($httpProvider) {
      
      $httpProvider.defaults.useXDomain = true;
      delete $httpProvider.defaults.headers.common['X-Requested-With'];
      $httpProvider.defaults.headers.post['Accept'] = 'application/json; text/javascript';
      $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
       $httpProvider.defaults.headers.common['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
      $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
      $httpProvider.defaults.headers.common['Authorization'] = '650E01A1B8F9A4DA4A2040FF86E699B7';
      $httpProvider.defaults.timeout = 6000;
      //Transforma el request para que los datos no sean enviados como JSON y sean enviados como url params
      $httpProvider.defaults.transformRequest = function(obj) {
        var str = [];
        for(var p in obj)
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        return str.join("&");
      }

})
.directive("ngFileSelect",function(){

  return {
    link: function($scope,el){
      
      el.bind("change", function(e){
      
        $scope.file = (e.srcElement || e.target).files[0];
        $scope.getFile();
      })
      
    }
    
  }
  
  
});
