jQuery(function(e){e(".jw-pagefactory-core-options-toggler").on("click",function(o){o.preventDefault(),e(".jw-pagefactory-core-options").toggleClass("active")});var o={};e.fn.openPopupAlt=function(){e("#page-options").addClass("jw-pagefactory-modal-overlay-after-open"),e("#page-options").addClass("jwpf-modal-only-ext"),e("#page-options").find(".jw-pagefactory-modal-content").addClass("jw-pagefactory-modal-content-after-open"),e("body").addClass("jw-pagefactory-modal-open"),e(".jw-pagefactory-modal-alt .form-group").find(">input,>textarea,>select").each(function(){o[e(this).attr("id")]=e(this).val()})},e.fn.closePopupAlt=function(t){var a=e.extend({reset:!1},t);return e("#page-options").addClass("jw-pagefactory-modal-overlay-before-close"),e("#page-options").find(".jw-pagefactory-modal-content").addClass("jw-pagefactory-modal-content-before-close"),e("#page-options").removeClass("jw-pagefactory-modal-overlay-before-close jw-pagefactory-modal-overlay-after-open"),e("#page-options").find(".jw-pagefactory-modal-content").removeClass("jw-pagefactory-modal-content-before-close jw-pagefactory-modal-content-after-open"),e("body").removeClass("jw-pagefactory-modal-open"),a.reset&&e(".jw-pagefactory-modal-alt .form-group").find(">input,>textarea,>select").each(function(){e(this).val(o[e(this).attr("id")]),"jform_og_image"==e(this).attr("id")&&""!=o[e(this).attr("id")]&&e(this).prev(".jwpf-media-preview").removeClass("no-image").attr("src",pagefactory_base+o[e(this).attr("id")])}),this},e(document).on("click","#btn-page-options",function(o){o.preventDefault(),e().openPopupAlt()}),e(document).on("click",".jw-pagefactory-modal-alt .jw-pagefactory-modal-content-after-open",function(o){o.target===this&&e().closePopupAlt({reset:!0})}),e(document).on("click","#btn-cancel-page-options",function(o){o.preventDefault(),e().closePopupAlt({reset:!0})}),e(document).on("click","#btn-apply-page-options",function(o){o.preventDefault(),e().closePopupAlt(),e("#jw-pagefactory-css",window.frames["jw-pagefactory-view"].window.document).text(e("#jform_css").val())})});