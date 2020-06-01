

$(function() {
    navbar();
})

function ajax(url, method, data, successfun = function() {}, errorfun = function() {}) {
    $.ajax({
        url: url,
        type: method,
        dataType: 'json',
        data: data,
       
        beforeSend: function (data) {
           // showProcessingTip();
        },
        success: function(data) {
            if(data['code'] === SUCCESS) {
                // showSuccessTip(data['message']);
            } else {
                // showFailTip(data['message']);
            }
             successfun(data);
        },
        error: function(data) {
            //showFailTip(data.responseJSON.message);
            if(data.responseJSON.code == UNAUTHORIZED){
                window.location.href = "auth/login";
            }else{
                 errorfun(data);
            }
            
             console.log(data);
        }
    });

}




function confirmCreateAdmin() {
    // ajax('/admin/admin/create', 'POST', {'account': $('input[name=account]').val(), 'password': $('input[name=password]').val(), 'password_confirmation': $('input[name=password_confirmation]').val()});

    $.ajax({
        url: '/admin/admin/create',
        type: 'POST',
        dataType: 'json',
        data: {'account': $('input[name=account]').val(), 'password': $('input[name=password]').val(), 'password_confirmation': $('input[name=password_confirmation]').val()},
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === SUCCESS) {
                showSuccessTip(data['message']);
                $('#' + datatable_id).DataTable().draw(false);
                $('#create_admin_modal').modal('hide');
            } else {
                showFailTip(data['message']);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function alertModal(function_name, function_params) {
    $('#alert_modal_confirm').click(function () {
        $('#alert_modal').modal('hide');
        function_name(function_params);
    });
    $('#alert_modal').modal('show');
}

function setAdminRole(params) {
    ajax('/admin/admin/role', 'PUT', {'admin_id': params['admin_id'], 'role_id': params['role_id']});
}

function setAdminStatus(params) {
    ajax('/admin/admin/status', 'PUT', {'admin_id': params['admin_id'], 'status': params['status']});
}


$(function() {
    toggleTable();
    changePage();
    clearMask();
    $(".page-num a:not('.active')").on("click", function() {
        $(".current-page").html($(this).find("span").text());
    })
});

function changePage() {
    $(".page-box").on("click", function() {
        $(".page-num").toggle();
        if($(".page-num").is(":visible")) {
            $(".menus-icon").addClass("menus-icon-active").removeClass("menus-icon");
        }else{
            $(".menus-icon-active").addClass("menus-icon").removeClass("menus-icon-active");
        }
    })
}


function clearMask() {
    $(".bomb-btn").on("click", function() {
        $(".bomb-box").remove();
    })
}


function toggleTable() {
    $(".tabs>span").on("click", function() {
        if(!$(this).hasClass("active-tab")) {
            $(this).siblings("span").removeClass('active-tab');
            $(this).addClass('active-tab');
            $("section.tabs-change>div").removeClass("show");  
            $("section.tabs-change>div:eq(" + $(this).index() + ")").addClass("show");        
        }
    })
}





function navbar() {
    var url = window.location.href;
    var li = $(".nav-menus>li");
    li.removeClass("current");
    if(url.indexOf('/1930954.php') >= 0 || url.indexOf('/web/rank') >= 0) {
       li.eq(0).find("a").addClass("current");
    }
    if( url.indexOf('/web/course') >= 0) {
       li.eq(1).find("a").addClass("current");
    }
    if( url.indexOf('/web/exam') >= 0) {
       li.eq(2).find("a").addClass("current");
    }
    if( url.indexOf('/web/user') >= 0) {
        li.eq(3).find("a").addClass("current");
    } 
 }




function examModelSet(header, content, footer,other) {
    $(".exam-model-header").html(header);
    $(".exam-model-content").html(content);
    $(".exam-model-footer").html(footer);
    other ? $(".exam-other-mess").html(other) : $(".exam-other-mess").html("");
    $(".exam-cover").show();
}

function examGiveUp() {

    var header = 'Give up this exam',
    content = '<p></p>',
    footer = '<a class="exam-btn btn btn-primary" data-href="' + site_url + '/web/exam" onclick="deleteExamHistory(this)">Confirm</a><a  class="exam-btn btn btn-primary" onclick="continueExam()">Cancel</a>'
    examModelSet(header, content, footer);
}

function subExam() {
    $(".exam-model-content").html("");
    var header = 'Submission',
    content = $(".tip-detail").clone(true);  
    footer = '<a  class="exam-btn btn btn-primary" onclick="postExam()">Confirm</a><a class="exam-btn btn btn-primary" onclick="continueExam()">Cancel</a>',
    other = '<p style="text-align:center"></p>';
    examModelSet(header,content,footer,other);
}

function continueExam() {
    $(".exam-cover").hide();
}

function deleteExamHistory(obj) {
    window.location.href = $('#exam').val();

    var exam_id = $("#exam-id").html(),redirect_href = obj.dataset.href;
    ajaxAsync(Api['deleteExamHistory'] + exam_id, "DELETE", {}, function(data) {
        if(!data.code) {
            console.log(redirect_href);
            window.location.href= redirect_href;
        }
    }, function(err) {
        console.log(err);
    });
}


function postExam(isPost) {
    window.location.href = $('#exam').val();

    isPost = isPost||"POST" ;
    var data = $("form.exam-form").serializeArray(),examId = $("#exam-id").html();
    var question = {};
    var result = [];
    var questionId = null;
    if(!data.length) {
        data = null;
    }else{
        $.each(data, function(i,v) {
            console.log(v.name);
            if(questionId == null) {
                common(v);
            }else if(questionId == v.name) {
                question.answers.push(parseInt(v.value));
            }else{
                result.push(question);
                question = {};
                common(v);
            }
        })
        result.push(question);

        function common(v) {
                questionId = v.name;
                question.id = v.name;
                question.answers = [];
                question.answers.push(parseInt(v.value));
        }
   }
   data = JSON.stringify(result);
    ajaxAsync(Api['examPostApi'] + examId , isPost, {"answers" : data}, function(data) {
        // 处理请求成功
        if(data.code == SUCCESS) {
            if(isPost == "POST") {
                //clearInterval(examTime); 当有定时保存的时候此行代码要解除注释
                window.location.href = site_url + "/web/exam/details/" + examId;
            }
        }
    }, function() {
        // 处理请求错误

        return;
    });
    data = null; // 销毁数据
    result = null; 

}

//# sourceMappingURL=web.js.map
