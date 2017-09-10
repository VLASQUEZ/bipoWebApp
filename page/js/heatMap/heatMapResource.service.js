angular.module('bipoApp').factory('heatMapResource',['$resource',function ($resource) {
    return $resource('http://www.bipoapp.com/services/v1/getReportsMaps/:id',{id:'@id'},{
        query:{
            responseType: 'json',
            params:{
                Authorization:'650E01A1B8F9A4DA4A2040FF86E699B7'
            }
        }
    });
}]);