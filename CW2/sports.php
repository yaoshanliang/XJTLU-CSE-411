<?php
include_once('header.php');
include_once('function.php');
?>

<?php
$info = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['is_public'] == true) {
        $is_public = 1;
    } else {
        $is_public = 0;
    }

    $result = $dbHelper->addSports($_POST['sports'], $_POST['duration'], $_POST['start_date'], $_POST['start_time'], $_POST['distance'], $_POST['calories'], $_POST['avg_speed'], $is_public);

    if ($result['code'] == 0) {
        header("Location: ./sports.php");
    } else {
        $info = $result['message'];
    }
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="page-common course-page">
            <div class="bulletin-content">
                <section class="content-detail">
                    <p class="bulletion-header">My Sports</p>
                    <button type="button" class="btn btn-primary" onclick="return add();">Add New Sports</button>

                    <table class="common-lists-style course-lists">
                        <tr>
                            <th>ID</th>
                            <th>Sport</th>
                            <th>Duration</th>
                            <th>Distance</th>
                            <th>Start Time</th>
                            <th>Average Speed</th>
                            <th>Calories</th>
                            <th>Is Public</th>
                            <th>Operation</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <a th:href="@{/web/course/} + ${course.id} ">Attend</a>
                            </td>
                        </tr>
                    </table>

                </section>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:500px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="modal_title">Add New Sports</h5>
                <input type="hidden" id="id" value="">
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Sport Type<span class="required">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="sports">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Duration</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="duration">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Start Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" id="start_date">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Start Time</label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" id="start_time">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Distance</label>
                            <div class="col-md-9">
                                <input type="number" step="0.01" class="form-control" id="distance">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Average Speed</label>
                            <div class="col-md-9">
                                <input type="number" step="0.01" class="form-control" id="avg_speed">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Calories<span class="required">*</span></label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" id="calories">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-3">Is Public<span class="required">*</span></label>
                            <div class="col-md-9">
                                <input type="checkbox" style="margin-top: 10px;" id="is_public">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-1">
                        <button type="button" class="btn btn-primary btn-block" onclick="return confirm();">确认</button>
                    </div>
                    <div class="col-md-5 col-md-offset-">
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">取消</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    function add() {
        $("#add_modal").modal('show');
    }

    function confirm() {
        let data = {
            sports: $('#sports').val(),
            duration: $('#duration').val(),
            start_date: $('#start_date').val(),
            start_time: $('#start_time').val(),
            distance: $('#distance').val(),
            calories: $('#calories').val(),
            avg_speed: $('#avg_speed').val(),
            is_public: $('#is_public').is(':checked')
        };

        ajax('./sports.php', 'POST', data, function() {
            window.location.href = './sports.php';
        }, function(data) {
            $('#info').text(data['message']);
        })
    }
</script>
<?php
include_once('footer.php');
?>