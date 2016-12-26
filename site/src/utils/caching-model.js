import can from 'can';

let CachedModel = can.Model.extend({
    makeFindAll: function(findAllData){
        // A place to store requests
        var cachedRequests = {};

        return function(params, success, error){

            // is this not cached?
            if(!cachedRequests[JSON.stringify(params)]) {
                var self = this;
                // make the request for data, save deferred
                cachedRequests[JSON.stringify(params)] =
                    findAllData(params).then(function(data){
                        // convert the raw data into instances
                        return self.models(data)
                    })
            }
            // get the saved request
            var def = cachedRequests[JSON.stringify(params)];
            // hookup success and error
            def.then(success,error);
            return def;
        }
    }
},{});

export default CachedModel;