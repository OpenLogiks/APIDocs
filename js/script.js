$(function() {
	$("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    
    $(".apiForm .input-group-btn .dropdown-menu a").click(function() {
			$(this).closest(".form-group").find("input").val($(this).html());
		});

    $("body").delegate(".toggleButton[for]","click",function(e) {
    	div=$(this).attr("for");
    	atag=$(this);
    	if($(div).is(":visible")) {
			atag.removeClass("fa-compress");
		} else {
			atag.addClass("fa-compress");
		}
		
    	$(div).toggle("blind");
    });
});

$.extend($.expr[":"], {
	"containsIN" : function(elem, i, match, array) {
		return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}