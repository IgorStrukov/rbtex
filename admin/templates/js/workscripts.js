$(document).ready(function(){
    
    /* ===Аккордеон=== */
    var openItem = false;
	if(jQuery.cookie("openItem") && jQuery.cookie("openItem") != 'false'){
		openItem = parseInt(jQuery.cookie("openItem"));
	}	
	jQuery("#accordion").accordion({
		active: openItem,
		collapsible: true,
        autoHeight: false,
        header: 'li.header_li'
	});
	jQuery("#accordion li.header_li").click(function(){
		jQuery.cookie("openItem", jQuery("#accordion").accordion("option", "active"));
	});	
	jQuery("#accordion > li.nol").click(function(){
		jQuery.cookie("openItem", null);
        var link = jQuery(this).find('a').attr('href');
        window.location = link;
	});
    /* ===Аккордеон=== */
  

	    // удаление
		$(".del").click(function(){
			var res = confirm("Подтвердите удаление");
			if(!res) return false;
		});
		// удаление  

	
	// слайд информеров
    $(".toggle").click(function(){
        $(this).parent().next().slideToggle(500);
        
        if($(this).parent().attr("class") == "inf-down"){
            $(this).parent().removeClass("inf-down");
            $(this).parent().addClass("inf-up");
        }else{
            $(this).parent().removeClass("inf-up");
            $(this).parent().addClass("inf-down");
        }
    });
    // слайд информеров
	});