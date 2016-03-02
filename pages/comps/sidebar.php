<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$pages=explode("/",$_REQUEST['page']);
$type=$pages[0];
if($type=="home") $type="api";

$category="";
$element="";

if(isset($pages[1])) $category=str_replace("-", " ", $pages[1]);
if(isset($pages[2])) $element=$pages[2];
?>
<style>
#apiTree * {
	-webkit-tap-highlight-color: rgba(0,0,0,0);
}
</style>
<div class="searchtag">
	<input id="apiTreeSearch" type="search" placeholder="Search ..." >
</div>
<ul id="apiTree" class='tf-tree'></ul>
<script>
srctype="<?=$type?>";
category="<?=$category?>";
element="<?=$element?>";
$(function() {
	$("#apiTree").delegate("li>div","click",function(e) {
		$(this).next("ul").toggle();
		$(this).closest("li").toggleClass("tf-open");
	})
	
	// $("#apiTree").delegate("li.category","click",function(e) {
	// 		if($(this).find(">ul").children().length<=0) {
	// 			v=$(this).attr("rel");
	// 			//console.log("Loading Category : "+v);
	// 			loadTreeElements(v);
	// 		}
	// 	});
	$("#apiTree").delegate("a.category","click",function(e) {
			e.preventDefault();
			li=$(this).parent();
			if(li.find(">ul").children().length<=0) {
				v=li.attr("rel");
				//console.log("Loading Category : "+v);
				loadTreeElements(v);
			} else {
				li.toggleClass("tf-open");
			}
		});
	$("#apiTreeSearch").keyup(function(e) {
		if(e.keyCode==13) {
			if($("#apiTreeSearch").val()!=null && $("#apiTreeSearch").val().length>0) {
				searchTree($("#apiTreeSearch").val());
			} else {
				loadTreeCategory();
			}
		}
	});
	<?php
		if(isset($_REQUEST['q'])) {
			echo "$('#apiTreeSearch').val('{$_REQUEST['q']}');";
			echo "searchTree($('#apiTreeSearch').val());";
		} else {
			echo "loadTreeCategory(category,element);";
		}
	?>
});
function loadTreeCategory(category,finalSelection) {
	//http://192.168.1.210/devlogiks362/services/?site=apidocs&scmd=apidocs&action=fetch-category&format=json
	lx=getServiceCMD(srctype,"fetch-category");
	$("#apiTree").html("<div class='ajaxloading4'>Loading Tree...</div>");
	processAJAXQuery(lx,function(txt) {
			try {
				json=$.parseJSON(txt);
				//console.log(json);
				$("#apiTree").html("");
				if(json.Data!=null) {
					$.each(json.Data,function(k,v) {
						if(v==null || v.length<=0) return;
						linkCategory=_link(srctype)+"/"+v.replace(/ /g,"-");
						$("#apiTree").append("<li rel='"+v+"' class='category tf-child-true' style='padding-left: 18px;'><a class='category' href='"+linkCategory+"'>"+v.capitalize()+"</a><ul></ul></li>");
						//loadTreeElements(v);
					});
					loadTreeElements(category,finalSelection);
				}
			} catch(e) {
				console.error(e);
			}
		});
}
function loadTreeElements(category,finalSelection) {
	if(category==null || category.length<=0) return false;
	//http://192.168.1.210/devlogiks362/services/?site=apidocs&scmd=apidocs&action=fetch-list&format=json&category=core
	lx=getServiceCMD(srctype,"fetch-list")+"&category="+category;
	if(category=="*") {
		$("#apiTree").html("<div class='ajaxloading4'>Loading Tree...</div>");
	} else {
		$("#apiTree li.category[rel='"+category+"']>ul").html("<div class='ajaxloading4'>Loading ...</div>");
	}
	processAJAXQuery(lx,function(txt) {
			if(category=="*") {
				$("#apiTree").html("");
			} else {
				$("#apiTree li.category[rel='"+category+"']>ul .ajaxloading4").detach();
			}
			try {
				json=$.parseJSON(txt);
				renderTree(json.Data);
				if(finalSelection!=null && finalSelection.length>0) {
					expandBranch($("#apiTree li.element[rel='"+finalSelection+"']"));
				} else if(category!=null && category.length>0) {
					$("#apiTree li.category[rel='"+category+"']").addClass("tf-open");
				}
			} catch(e) {
				console.error(e);
			}
		});
}
function searchTree(txt) {
	if(category!=null || category.length>0) {
		$("#apiTree").html("<div class='ajaxloading4'>Searching ...</div>");
		lx=getServiceCMD(srctype,"search-list")+"&q="+txt;
		processAJAXQuery(lx,function(txt) {
			//console.log(txt);
			$("#apiTree").html("");
			try {
				json=$.parseJSON(txt);
				renderTree(json.Data);
				$("#apiTree li.category").each(function() {
					expandNode($(this));
				});
			} catch(e) {
				console.error(e);
			}
		});
	} else {
		loadTreeCategory();
	}
}
function renderTree(jsonData) {
	//console.log(jsonData);
	$.each(jsonData,function(k,v) {
			if(v.category==null || v.category.length<=0) {
				v.category="Damaged";
			}
			category=v.category;
			subcategory=v.subcategory;
			
			v.titleID=subcategory.replace(/ /g,"-")+"."+v.title.replace(/ /g,"-")+"-"+v.id;
			v.titleID=encodeURIComponent(v.titleID);
			if($("#apiTree li.category[rel='"+category+"']").length<=0) {
				linkCategory=_link(srctype)+"/"+category.replace(/ /g,"-");
				$("#apiTree").append("<li rel='"+category+"' class='category tf-child-true' style='padding-left: 18px;'><a class='category' href='"+linkCategory+"'>"+category.capitalize()+"</a><ul></ul></li>");
			}
			if($("#apiTree li.category[rel='"+category+"'] li.subcategory[rel='"+subcategory+"']").length<=0) {
				html="<li rel='"+subcategory+"' class='subcategory tf-child-true' style='padding-left: 18px;'><div>"+subcategory+"</div><ul></ul></li>";
				$("#apiTree li.category[rel='"+category+"']>ul").append(html);
			}
			html='<li rel="'+v.titleID+'" class="element tf-child-false" style="padding-left: 18px;"><a href="'+_link(srctype)+"/"+category.replace(/ /g,"-")+"/"+v.titleID+'">'+v.title+'</a></li>';
			$("#apiTree li.category[rel='"+category+"'] li.subcategory[rel='"+subcategory+"']>ul").append(html);
		});
}
function expandNode(libObj) {
	$(libObj).addClass("tf-open");
	$(libObj).find(".tf-child-true").addClass("tf-open");
}
function expandBranch(eleObj) {
	//console.log(eleObj);
	liObj=$(eleObj).parent().closest("li");
	if(eleObj.hasClass("element")) eleObj.addClass("tf-selected");
	liObj.addClass("tf-open");
	if(!liObj.hasClass("category")) expandBranch(liObj);
}
</script>
