<?php
include_once('header.php');
include_once('function.php');
?>

<?php
$info = '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $result = $dbHelper->getMySports($page, @$_GET['sport'], @$_GET['start_date'], @$_GET['sort'], @$_GET['order']);
    $sports = $result['data'];
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="page-common course-page">
            <div class="content">
                <section class="content-detail">
                    <p class="header">My Sports</p>
                    <button type="button" class="btn btn-primary" onclick="return add();">Add New Sport</button>
                    <input type="text" class="form-control" style="width: 150px; display:inline-block;" id="search_sports" placeholder="Sport Type" value="<?php echo @$_GET['sport']; ?>">
                    <input type="date" class="form-control" style="width: 150px; display:inline-block;" id="search_start_date" placeholder="Start Time" value="<?php echo @$_GET['start_date']; ?>">
                    <button type="button" class="btn btn-default" onclick="search();">Search</button>
                    <button type="button" class="btn btn-default" onclick="reset();">Reset</button><br /><br />
                    <?php if (@$_GET['sort'] == 'duration' && @$_GET['order'] == 'asc') { ?>
                        <button type="button" class="btn btn-default" onclick="sort('duration', 'desc');">Sort By Duration(DESC)</button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-default" onclick="sort('duration', 'asc');">Sort By Duration(ASC)</button>
                    <?php } ?>

                    <?php if (@$_GET['sort'] == 'distance' && @$_GET['order'] == 'asc') { ?>
                        <button type="button" class="btn btn-default" onclick="sort('distance', 'desc');">Sort By Distance(DESC)</button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-default" onclick="sort('distance', 'asc');">Sort By Distance(ASC)</button>
                    <?php } ?>

                    <?php if (@$_GET['sort'] == 'start_date' && @$_GET['order'] == 'asc') { ?>
                        <button type="button" class="btn btn-default" onclick="sort('start_date', 'desc');">Sort By Start Time(DESC)</button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-default" onclick="sort('start_date', 'asc');">Sort By Start Time(ASC)</button>
                    <?php } ?>

                    <?php if (@$_GET['sort'] == 'avg_speed' && @$_GET['order'] == 'asc') { ?>
                        <button type="button" class="btn btn-default" onclick="sort('avg_speed', 'desc');">Sort By Average Speed(DESC)</button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-default" onclick="sort('avg_speed', 'asc');">Sort By Average Speed(ASC)</button>
                    <?php } ?>

                    <?php if (@$_GET['sort'] == 'calories' && @$_GET['order'] == 'asc') { ?>
                        <button type="button" class="btn btn-default" onclick="sort('calories', 'desc');">Sort By Calories(DESC)</button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-default" onclick="sort('calories', 'asc');">Sort By Calories(ASC)</button>
                    <?php } ?>
                    <input type="hidden" id="page" value="<?php echo $page; ?>">

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
                        <?php foreach ($sports as $v) { ?>
                            <tr>
                                <td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['sport']; ?></td>
                                <td><?php echo $v['duration']; ?></td>
                                <td><?php echo $v['distance']; ?></td>
                                <td><?php echo $v['start_date'], ' ', $v['start_time'] ?></td>
                                <td><?php echo $v['avg_speed']; ?></td>
                                <td><?php echo $v['calories']; ?></td>
                                <td><?php if ($v['is_public'] == 1) echo 'Public';
                                    else echo 'Private'; ?></td>
                                <td>
                                    <a onclick="edit(<?php echo $v['id'] . ',\'' . $v['sport'] . '\', \'' .  $v['duration'] . '\',\'' . $v['distance'] . '\',\'' . $v['start_date'] . '\',\'' . $v['start_time'] . '\',\'' . $v['avg_speed'] . '\',\'' . $v['calories'] . '\',' . $v['is_public'] ?>)">Edit</a>
                                    <a onclick="deletes(<?php echo $v['id']; ?>)">Delete</a>

                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <div class="footer">
                        <a href="javascript:void(0)" class="page-link next-page" onclick="nextPage();">下一页</a>
                        <a href="javascript:void(0)" class="page-link previous-page" onclick="previousPage();">上一页</a>
                    </div>
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
                <h5 class="modal-title" id="modal_title">Add New Sport</h5>
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
                        <button type="button" class="btn btn-primary btn-block" onclick="return confirm();">Confirm</button>
                    </div>
                    <div class="col-md-5 col-md-offset-">
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:300px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">Are you sure?</h5>
                <input type="hidden" id="delete_id" value="">
            </div>
            
            <div class="modal-footer">
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-1">
                        <button type="button" class="btn btn-primary btn-block" onclick="return confirmDelete();">Confirm</button>
                    </div>
                    <div class="col-md-5 col-md-offset-">
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancel</button>
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
        $('#id').val(0);
        $('#sports').val('');
        $('#duration').val('');
        $('#distance').val('');
        $('#start_date').val('');
        $('#start_time').val('');
        $('#avg_speed').val('');
        $('#calories').val('');
        $("#add_modal").modal('show');
    }

    function edit(id, sport, duration, distance, start_date, start_time, avg_speed, calories, is_public) {
        $('#id').val(id);
        $('#sports').val(sport);
        $('#duration').val(duration);
        $('#distance').val(distance);
        $('#start_date').val(start_date);
        $('#start_time').val(start_time);
        $('#avg_speed').val(avg_speed);
        $('#calories').val(calories);
        if (is_public) {
            $('#is_public').prop("checked", true);
        } else {
            $('#is_public').prop("checked", false);
        }

        $('#modal_title').text('Edit Current Sport');

        $("#add_modal").modal('show');
    }
    function deletes(id) {
        $('#delete_id').val(id);
       
        $("#delete_modal").modal('show');
    }

    function confirm() {
        let data = {
            id: $('#id').val(),
            sports: $('#sports').val(),
            duration: $('#duration').val(),
            start_date: $('#start_date').val(),
            start_time: $('#start_time').val(),
            distance: $('#distance').val(),
            calories: $('#calories').val(),
            avg_speed: $('#avg_speed').val(),
            is_public: $('#is_public').is(':checked')
        };

        ajax('./sportsAPI.php', 'POST', data, function(data) {
            console.log(data);
            window.location.href = './sports.php';
        }, function(data) {
            $('#info').text(data['message']);
        })
    }

    function confirmDelete() {
        let data = {
            id: $('#delete_id').val(),
        };

        ajax('./sportsAPI.php', 'DELETE', data, function(data) {
            console.log(data);
            window.location.href = './sports.php';
        }, function(data) {
            $('#info').text(data['message']);
        })
    }

    function search() {
        sport = $('#search_sports').val();
        start_date = $('#search_start_date').val();
        window.location.href = "./sports.php?sport=" + sport + '&start_date=' + start_date;
    }

    function reset() {
        window.location.href = "./sports.php";
    }

    function sort(type, order) {
        window.location.href = "./sports.php?sport=" + $('#search_sports').val() + '&start_date=' + $('#search_start_date').val() + '&sort=' + type + '&order=' + order;
    }

    function nextPage() {
        let page = $('#page').val();
        if (page) {
            page++;
        } else {
            page = 1;
        }

        window.location.href = "./sports.php?page=" + page + "&sport=" + $('#search_sports').val() + '&start_date=' + $('#search_start_date').val();
    }

    function previousPage() {
        let page = $('#page').val();
        if (page && page > 1) {
            page--;
        } else {
            page = 1;
        }

        window.location.href = "./sports.php?page=" + page + "&sport=" + $('#search_sports').val() + '&start_date=' + $('#search_start_date').val();
    }
</script>
<?php
include_once('footer.php');
?>