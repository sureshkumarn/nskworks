// General Scripts for envyMyWorld

var NSKWORKS = window.NSKWORKS || {};

(function(window,document,$,NSKWORKS){

	NSKWORKS.utils = (function(){

		var post_comment = function(){
			if(!$('.contact').length){
				return;
			}
			var form = $('.contact form'),url = form.attr('action');
			$.post(url,form.serialize(),function(data){
				var status = data.status || false, msg;
				if(status){
					$('msg',form).html(data.msg);
				}
			});
		}

		var init = function(){
			post_comment();
		}

		return {
			init : init
		}
	}());
}(window,document,jQuery,NSKWORKS));

NSKWORKS.utils.init();