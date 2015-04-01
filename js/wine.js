$(function (){
    var wine = window.wine = {
        url: "http://localhost/wine/action.php",
        getUrlParam: function (name){ 
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); 
            var r = window.location.search.substr(1).match(reg); 
            if (r!=null) return unescape(r[2]); return null; 
        },
        getInfo: function (){
            var obj = {};
            for(var i in localStorage){
                obj[i] = localStorage[i];
            }
            
            return obj;
        }
    };
})