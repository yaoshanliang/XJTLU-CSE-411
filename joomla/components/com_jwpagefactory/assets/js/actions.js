jQuery(function(e){function t(t,i){0===e(".jw-pagefactory-notifications").length&&e('<div class="jw-pagefactory-notifications"></div>').appendTo("body"),e('<div class="notify-'+i+'">'+t+"</div>").css({opacity:0,"margin-top":-15,"margin-bottom":0}).animate({opacity:1,"margin-top":0,"margin-bottom":15},200).prependTo(".jw-pagefactory-notifications"),e(".jw-pagefactory-notifications").find(">div").each(function(){var t=e(this);setTimeout(function(){t.animate({opacity:0,"margin-top":-15,"margin-bottom":0},200,function(){t.remove()})},3e3)})}e(".jw-pagefactory-form-group-toggler").find(">span").on("click",function(t){t.preventDefault(),e(this).parent().hasClass("active")?e(this).parent().removeClass("active"):(e(".jw-pagefactory-form-group-toggler").removeClass("active"),e(this).parent().addClass("active"))});window.onbeforeunload=function(e){if(void 0!==window.warningAtReload&&1==window.warningAtReload){var t="Do you want to lose unsaved data?";return(e=e||window.event)&&(e.returnValue=t),t}return null},e(document).on("click","#btn-save-page",function(t){t.preventDefault();var i=e(this),a=e.parseJSON(e("#jform_jwtext").val());a.filter(function(e){return e.columns.filter(function(e){return e.addons.filter(function(e){return"jw_row"===e.type||"inner_row"===e.type?e.columns.filter(function(e){return e.addons.filter(function(e){return null!=typeof e.htmlContent&&delete e.htmlContent,null!=typeof e.assets&&delete e.assets,e})}):(null!=typeof e.htmlContent&&delete e.htmlContent,null!=typeof e.assets&&delete e.assets,e)})})}),e("#jform_jwtext").val(JSON.stringify(a));var n=e("#adminForm"),o=e("#jw-page-factory").data("pageid");e.ajax({type:"POST",url:pagefactory_base+"index.php?option=com_jwpagefactory&task=page.apply&pageId="+o,data:n.serialize(),beforeSend:function(){i.find(".fa-save").removeClass("fa-save").addClass("fa-spinner fa-spin")},success:function(a){try{var n=e.parseJSON(a);i.find(".fa").removeClass("fa-spinner fa-spin").addClass("fa-save"),0===e(".jw-pagefactory-notifications").length&&e('<div class="jw-pagefactory-notifications"></div>').appendTo("body");var o="success";if(!n.status)o="error";if(n.title&&e("#jform_title").val(n.title),n.id&&e("#jform_id").val(n.id),e('<div class="notify-'+o+'">'+n.message+"</div>").css({opacity:0,"margin-top":-15,"margin-bottom":0}).animate({opacity:1,"margin-top":0,"margin-bottom":15},200).prependTo(".jw-pagefactory-notifications"),void 0!==window.warningAtReload&&1==window.warningAtReload&&(window.warningAtReload=!1),e(".jw-pagefactory-notifications").find(">div").each(function(){var t=e(this);setTimeout(function(){t.animate({opacity:0,"margin-top":-15,"margin-bottom":0},200,function(){t.remove()})},3e3)}),!n.status)return;window.history.replaceState("","",n.redirect),n.preview_url&&0===e("#btn-page-preview").length&&e("#btn-page-options").parent().before('<div class="jw-pagefactory-btn-group"><a id="btn-page-preview" target="_blank" href="'+n.preview_url+'" class="jw-pagefactory-btn jw-pagefactory-btn-primary"><i class="fa fa-eye"></i> Preview</a></div>'),"btn-save-new"==t.target.id&&(window.location.href="index.php?option=com_jwpagefactory&view=page&layout=edit"),"btn-save-close"==t.target.id&&(window.location.href="index.php?option=com_jwpagefactory&view=pages")}catch(e){window.location.href="index.php?option=com_jwpagefactory&view=pages"}}})}),e(".page-factory-form-menu").on("submit",function(i){i.preventDefault();var a=e(this).serialize();e.ajax({type:"POST",url:pagefactory_base+"index.php?option=com_jwpagefactory&task=page.addToMenu&pageId="+e("#jw-page-factory").data("pageid"),dataType:"json",cache:!1,data:a,beforeSend:function(){e("#jw-pagefactory-btn-add-to-menu").find(".fa").removeClass("fa-save").addClass("fa-spinner fa-spin")},success:function(i){e("#jw-pagefactory-btn-add-to-menu").find(".fa").removeClass("fa-spinner fa-spin").addClass("fa-save"),i.status?(t(i.success,"success"),window.location.href=i.redirect):t(i.error,"error")}.bind(this)})}),e("#jform_menutype").change(function(){var t=e(this).val();e.ajax({url:pagefactory_base+"index.php?option=com_jwpagefactory&task=page.getMenuParentItem&menutype="+t,dataType:"json"}).done(function(t){e("#jform_menuparent_id option").each(function(){"1"!=e(this).val()&&e(this).remove()}),e.each(t,function(t,i){var a=e("<option>");a.text(i.title).val(i.id),e("#jform_menuparent_id").append(a)}),e("#jform_menuparent_id").trigger("liszt:updated")})}),e(document).on({mouseenter:function(){var t=e(this).find("img"),i=e(this).find("img").outerHeight()-230;t.css({top:"-"+i+"px"})},mouseleave:function(){e(this).find("img").css({top:0})}},".jw-pagefactory-page-templates li")});