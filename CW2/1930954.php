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

    $result = $dbHelper->getAllSports($page, @$_GET['sport'], @$_GET['start_date'], @$_GET['sort'], @$_GET['order']);
    $sports = $result['data'];
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="page-common course-page">
            <div class="content">
                <section class="content-detail">
                    <p class="header">Public Sports</p>
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
                            <th>User</th>
                            <th>Sport</th>
                            <th>Duration</th>
                            <th>Distance</th>
                            <th>Start Time</th>
                            <th>Average Speed</th>
                            <th>Calories</th>
                            <th>Is Public</th>
                        </tr>
                        <?php foreach ($sports as $v) { ?>
                            <tr>
                                <td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['name']; ?></td>
                                <td><?php echo $v['sport']; ?></td>
                                <td><?php echo $v['duration']; ?></td>
                                <td><?php echo $v['distance']; ?></td>
                                <td><?php echo $v['start_date'], ' ', $v['start_time'] ?></td>
                                <td><?php echo $v['avg_speed']; ?></td>
                                <td><?php echo $v['calories']; ?></td>
                                <td><?php if ($v['is_public'] == 1) echo 'Public';
                                    else echo 'Private'; ?></td>
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

<script>

    function search() {
        sport = $('#search_sports').val();
        start_date = $('#search_start_date').val();
        window.location.href = "./1930954.php?sport=" + sport + '&start_date=' + start_date;
    }

    function reset() {
        window.location.href = "./1930954.php";
    }

    function sort(type, order) {
        window.location.href = "./1930954.php?sport=" + $('#search_sports').val() + '&start_date=' + $('#search_start_date').val() + '&sort=' + type + '&order=' + order;
    }

    function nextPage() {
        let page = $('#page').val();
        if (page) {
            page++;
        } else {
            page = 1;
        }

        window.location.href = "./1930954.php?page=" + page + "&sport=" + $('#search_sports').val() + '&start_date=' + $('#search_start_date').val();
    }

    function previousPage() {
        let page = $('#page').val();
        if (page && page > 1) {
            page--;
        } else {
            page = 1;
        }

        window.location.href = "./1930954.php?page=" + page + "&sport=" + $('#search_sports').val() + '&start_date=' + $('#search_start_date').val();
    }
</script>
<?php
include_once('footer.php');
?>