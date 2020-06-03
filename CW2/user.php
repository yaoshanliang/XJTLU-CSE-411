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

    $result = $dbHelper->getAllUsers($page, @$_GET['name'], @$_GET['username']);
    $sports = $result['data'];
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="page-common course-page">
            <div class="content">
                <section class="content-detail">
                    <p class="header">All Users</p>
                    <input type="text" class="form-control" style="width: 150px; display:inline-block;" id="name" placeholder="Name" value="<?php echo @$_GET['name']; ?>">
                    <input type="text" class="form-control" style="width: 150px; display:inline-block;" id="username" placeholder="Username" value="<?php echo @$_GET['username']; ?>">
                    <button type="button" class="btn btn-default" onclick="search();">Search</button>
                    <button type="button" class="btn btn-default" onclick="reset();">Reset</button><br /><br />

                    <input type="hidden" id="page" value="<?php echo $page; ?>">

                    <table class="common-lists-style course-lists">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Gender</th>
                            <th>Profile Text</th>
                            <th>Operation</th>
                        </tr>
                        <?php foreach ($sports as $v) { ?>
                            <tr>
                                <td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['name']; ?></td>
                                <td><?php echo $v['username']; ?></td>
                                <td><?php if ($v['gender'] == 0) echo 'Male';
                                    else echo 'Female'; ?></td>
                                <td><?php echo $v['profile']; ?></td>
                                <td>
                                    <a onclick="chat(<?php echo $v['id']; ?>)">Chat</a>

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

<script>
    function search() {
        name = $('#name').val();
        username = $('#username').val();
        window.location.href = "./user.php?name=" + name + '&username=' + username;
    }

    function reset() {
        window.location.href = "./user.php";
    }

    function nextPage() {
        let page = $('#page').val();
        if (page) {
            page++;
        } else {
            page = 1;
        }

        window.location.href = "./user.php?page=" + page + "&name=" + $('#name').val() + '&username=' + $('#username').val();
    }

    function previousPage() {
        let page = $('#page').val();
        if (page && page > 1) {
            page--;
        } else {
            page = 1;
        }

        window.location.href = "./user.php?page=" + page + "&name=" + $('#name').val() + '&username=' + $('#username').val();
    }

    function chat(id) {
        window.location.href = "./message.php?id=" + id;
    }
</script>
<?php
include_once('footer.php');
?>