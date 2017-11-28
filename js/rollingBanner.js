/* Main Rolling Banner 수정 20160202 서영기 */
var Roll_Num = 0;

$(window).load(function(){
	var active = null;
	
	imgSize = $('.rolling_banner ul li').size();
	imgWidth = $('.rolling_banner ul li').innerWidth();
	rollWidth = imgSize * imgWidth;
	lastMargin = (imgSize-1) * imgWidth;
	interval = 8000;
	speed = 500;
	pause = "N";
	for (var i=0 ; i<imgSize ; i++){
		$('.rolling_pager').append('<li></li>');
	}
	/*
	$('.rolling_pager li:first-child').addClass('On')
	*/
	$('.rolling_banner').css('width',rollWidth+'px');
	rolling_banner();
	timer();

});
	 
function rolling_banner(){
	/*
	$('.rolling_pager li').click(function(){
		clearInterval(active);
		$('.rolling_pager li').removeClass('On');
		$(this).addClass('On');
		imgSel = $(this).index();
		imgMarginNum = imgSel - Roll_Num;
		imgMargin = imgMarginNum * imgWidth;
		$('.rolling_banner:not(:animated)').animate({marginLeft:'-='+imgMargin+'px'}, speed);
		Roll_Num = Number(imgSel);
	});
	$('.rolling_pager li').hover(
		function(){clearInterval(active);},
		function(){active  = setInterval("action()", interval)}
	)
	*/
	$('.slideArea .btn_prev').click(function(){
		clearInterval(active);
		if(Roll_Num != 0){
			$('.rolling_banner:not(:animated)').animate({marginLeft:'+='+imgWidth+'px'}, speed);
			Roll_Num -= 1;
		}else{
			$('.rolling_banner:not(:animated)').animate({marginLeft:'-='+lastMargin+'px'}, speed);
			Roll_Num = Number((imgSize-1))
		}
		/*
		$('.rolling_pager li').removeClass('On')
		$('.rolling_pager li').eq(Roll_Num).addClass('On')	
		*/
		pause = "N";
		timer()		
	});
	
	$('.slideArea .btn_next').click(function(){
		clearInterval(active);
		if(Roll_Num != imgSize-1){
			$('.rolling_banner:not(:animated)').animate({marginLeft:'-='+imgWidth+'px'}, speed);
			Roll_Num += 1;
		}else{
			$('.rolling_banner:not(:animated)').animate({marginLeft:'0'}, speed);
			Roll_Num = 0;
		}
		/*
		$('.rolling_pager li').removeClass('On')
		$('.rolling_pager li').eq(Roll_Num).addClass('On')
		*/
		pause = "N";
		timer()
	});

}
	  
function timer(){
	active  = setInterval("action()", interval) 
};


function action(){
	imgSize = $('.rolling_banner ul li').size();
	if(Roll_Num < (imgSize-1)){	
		Roll_Num += 1;
		$('.rolling_banner').animate({marginLeft:'-='+imgWidth+'px'},speed);
	}else{
		Roll_Num = 0;
		$('.rolling_banner').animate({marginLeft:'0'},speed);
	}
		//alert(imgMargin)
	$('.rolling_pager li').removeClass('On');
	$('.rolling_pager li').eq(Roll_Num).addClass('On');	
};

function fn_mouseOver(){
	if(pause == "N"){
		clearInterval(active);
	}
}

function fn_mouseOut(){
	if(pause == "N"){
		active = setInterval("action()", interval);
	}
}