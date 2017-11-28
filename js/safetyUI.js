$(document).ready(function(){
    GNBAct();
    loginScript();
    rollingBanner();
});
$(window).load(function(){

});
$(window).resize(function(){

});

function GNBAct(){
     $('#GNB > div > ul').bind({
        mouseenter:function(){
             $('#GNB > div > ul > li > ul:not(:animated)').slideDown(250);
        },
        mouseleave:function(){
            $('#GNB > div > ul > li > ul').slideUp(150);
        }
    })
}

function loginScript(){
  $('input[name="userID"]').bind({
	  focus:function(){
		  if($(this).val()=='아이디'){
			  $(this).val('')
		  }
	  },
	  blur:function(){
		  if($(this).val()=='' || $(this).val()=='아이디'){
			  $(this).val('아이디')
		  }
	  }
  })
  $('input[name="pwd"]').bind({
	  focus:function(){
		  if($(this).attr('type')=='text'|| $(this).val()=='비밀번호'){
			  $(this).val('');
			  $(this).attr('type','password')
		  }
	  },
	  blur:function(){
		  if($(this).val()=='비밀번호' || $(this).val()==''){
			  $(this).attr('type','text')
			  $(this).val('비밀번호');
		  }
	  }
  })
}

function snbSel(selNum){     
   	$('#snb li:nth-child('+selNum+')').addClass('select');
}

function rollingBanner(){
    $('.rollingBanner').slick({
        autoplay: true,
        autoplaySpeed: 6000
    });
}