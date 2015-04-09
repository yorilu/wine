//var SERVER_IP = "121.40.92.70";
//start 替换
var SERVER_IP = "m.drwine.cn";
var PROJECT_NAME = "wine7_1";
var DOWNLOAD_URL = "http://a.app.qq.com/o/simple.jsp?pkgname=com.drwine.android";
var SHARE_TITLE = "送你红包，请你喝酒";
var SHARE_DESC = "dr.wine 红包送礼";
var SHARE_IMG = "http://"+SERVER_IP+"/"+PROJECT_NAME+"/pic/bag.jpg";
//end
window.PROJECT_NAME = PROJECT_NAME;



var ACTION_URL = "http://"+SERVER_IP+"/"+PROJECT_NAME+"/action.php";
var REDBAG_URL = "http://"+SERVER_IP+"/"+PROJECT_NAME+"/redbag.html";

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
		bindEvent: function (){
			$(".J_ShareBtn").on('click', function (){
				wine.showShareToast();
			})
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
            var $toast = $(".J_ToastShare");
            if(!$toast[0]){
                $toast = $("<div class='toast-share J_ToastShare'></div>");
                $("body").append($toast);
            };
			$("body").scrollTop(0);
            $toast.css({
                height:$('body')[0].scrollHeight
            })
			$toast.show();
            $toast.unbind().on('click',function (){
                $toast.hide();
            })
        },
        getWxConfig: function (){
            var that= this;
            var auth = localStorage["auth"];
            var urlname = localStorage["urlname"];
            var shareurl =location.href.split("#")[0];
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
                            "onMenuShareWeibo",
							"hideOptionMenu",
							"showOptionMenu",
							"hideMenuItems",
							"showMenuItems"
							]
                        });
                    }
                },
                error: function(info){
                    debugger;
                }
            });
        }
    };    
	
	if(typeof wx != 'undefined'){
		wx.ready(function(){
			var shareTitle = SHARE_TITLE;
			var shareDesc = SHARE_DESC;
			var shareImg = SHARE_IMG;
			
			var shareLink = REDBAG_URL+'?name='+localStorage["name"]+'&code='+localStorage["code"];
			
			wx.onMenuShareTimeline({
				title: shareTitle,
				link: shareLink,
				imgUrl: shareImg, 
				success: function (info) { 
				},
				cancel: function (info) { 
				   
				}
			});
			
			wx.onMenuShareAppMessage({
				title: shareTitle, 
				desc: shareDesc, 
				link: shareLink, 
				imgUrl: shareImg, 
				type: '', // 分享类型,music、video或link，不填默认为link
				dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
				success: function () { 
					
				},
				cancel: function () { 
				   
				}
			});
			
			wx.onMenuShareQQ({
				title: shareTitle, 
				desc: shareDesc, 
				link: shareLink, 
				imgUrl: shareImg, 
				success: function () { 
				  
				},
				cancel: function () { 
				   
				}
			});
			
			wx.onMenuShareWeibo({
				title: shareTitle, 
				desc: shareDesc, 
				link: shareLink, 
				imgUrl: shareImg, 
				success: function () { 
				  
				},
				cancel: function () { 
					
				}
			});
		});
	}
})