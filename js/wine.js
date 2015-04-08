//var SERVER_IP = "121.40.92.70";
var SERVER_IP = "172.16.150.128";
var DOWNLOAD_URL = "http://download.com";
var ACTION_URL = "http://"+SERVER_IP+"/wine/action.php";

$(function (){
    var dialog ='<div class="dialog J_Dialog hidden">'+
                    '<div class="d-title"><bold class="J_t"></bold></div>'+
                    '<div class="d-content J_C"></div>'+
                    '<div class="J_D confirm"></div>'+
                '</div>"';
            
    var wine = window.wine = {
        url: ACTION_URL,
        downloadUrl:DOWNLOAD_URL,
        serverIp: SERVER_IP,
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
            $(".J_Mask").css({
                height:$("body")[0].scrollHeight
            })
            
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
        },
        showShareToast: function (){
            if(localStorage['hasToasted'] == 1){
                return;
            }
            var $toast = $(".J_ToastShare");
            if(!$toast[0]){
                $toast = $("<div class='toast-share J_ToastShare'></div>");
                $("body").append($toast);
            };
            localStorage['hasToasted'] = 1;
            $toast.css({
                height:$('body')[0].scrollHeight
            })
            $toast.unbind().on('click',function (){
                $toast.hide();
            })
        },
        getWxConfig: function (){
            var that= this;
            var auth = localStorage["auth"];
            var urlname = localStorage["urlname"];
            var shareurl =location.href.split("?")[0];
            $.ajax({
                url:wine.url,
                type:"post",
                data: {
                    action: "getWxConfig",
                    dataType: "json",
                    auth: auth,
                    name: urlname,
                    shareurl:shareurl
                },
                success: function (info){
                    if(info.rc == 0){
                        var data = info.data;
                        debugger;
                        wx.config({
                            debug: true, 
                            appId: data.appid,
                            timestamp: data.timestamp,
                            nonceStr: data.nonceStr, 
                            signature: data.signature,
                            jsApiList: [
                            "onMenuShareTimeline",
                            "onMenuShareAppMessage",
                            "onMenuShareQQ",
                            "onMenuShareWeibo"]
                        });
                        that.bindEvent();
                    }
                },
                error: function(info){
                    debugger;
                }
            });
        },
        bindEvent: function (){
            wx.onMenuShareTimeline({
                title: 'aaa', // 分享标题
                link: 'www.baidu.com', // 分享链接
                imgUrl: 'dddd', // 分享图标
                success: function (info) { 
                    alert(1);
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (info) { 
                    alert(2);
                    // 用户取消分享后执行的回调函数
                }
            });
            $(".J_ShareBtn").on('click', function (){
                wx.showOptionMenu();
            })
        }
    };    
})