$(function (){
    var dialog ='<div class="dialog J_Dialog hidden">'+
                    '<div class="d-title"><bold class="J_t"></bold></div>'+
                    '<div class="d-content J_C"></div>'+
                    '<div class="J_D confirm"></div>'+
                '</div>"';
            
    var wine = window.wine = {
        url: "http://localhost/wine/action.php",
        getUrlParam: function (name){ 
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); 
            var r = window.location.search.substr(1).match(reg); 
            if (r!=null) return r[2]; return null; 
        },
        getInfo: function (){
            var obj = {};
            for(var i in localStorage){
                obj[i] = localStorage[i];
            }
            
            return obj;
        },
        showMask: function(){
            if(!$(".J_Mask")[0]){
                $("body").append("<div class='mask J_Mask'></div>");
            }
            
            $(".J_Mask").show();
        },
        showDialog: function (title,content,btn,cb){
            var that = this;
            if(!$(".J_Dialog")[0]){
                $("body").append(dialog);
            }
            title = title || "";
            content = content || "";
            btn = btn || '确定';
            var dia = $(".J_Dialog");
            dia.find(".J_t").html(title);
            dia.find(".J_C").html(content);
            dia.find(".J_D").html(btn);
            dia.show();
            dia.find(".J_D").unbind().on('click', function (){
                that.hideDialog();
                if(cb){
                    cb();
                }
            })
            
            this.showMask();
        },
        hideDialog: function (){
            $(".J_Dialog").hide();
            $(".J_Mask").hide();
        }
    };
})