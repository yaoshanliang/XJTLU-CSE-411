<?php
include_once('header.php');
include_once('function.php');
?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $result = $dbHelper->getMessages($_GET['id']);
    $data = $result['data'];

    $result = $dbHelper->getUser($_GET['id']);
    $user = $result['data'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $result = $dbHelper->sendMessage($_GET['id'], $_POST['content']);
    $data = $result['data'];
    header("Location: ./message.php?id=" . $_GET['id']);
}
?>

<div id="page-wrapper">
    <div class="center-page" style="height: 550px;">
        <div class="page-common course-page">
            <div class="content">
                <section class="content-detail" style="height: 550px;">
                    <p class="header">Messages with <?php echo $user['name'];?></p>
                    <div class="col-md-12" style="height: 300px; overflow-y:scroll">
                        <?php foreach ($data as $v) {
                            if ($v['from_user_id'] == $_SESSION['id']) { ?>
                                <div class="message-item right" style="float: right;width: 100%">
                                    <div class="message-time" style="float: right;"><?php echo $v['created_at']; ?></div>
                                    <div class="message-text" style="float: right;"><?php echo $v['content']; ?></div>
                                </div>
                            <?php } else { ?>
                                <div class="message-item left" style="float: left;width: 100%">
                                    <div class="message-time" style="float: left;"><?php echo $v['created_at']; ?></div>

                                    <div class="message-text" style="float: left;"><?php echo $v['content']; ?></div>
                                </div>
                        <?php }
                        } ?>
                    </div>

                    <form class="form-horizontal" style="width:800px; height: 100px;" method="POST" action="./message.php?id=<?php echo $_GET['id']; ?>">
                        <div class="col-md-12" style="padding-top: 50px;">
                            <div class="col-md-1">
                            </div>

                            <div class="col-md-8">
                                <textarea style="width: 400px; height: 50px;" name="content" required="required"></textarea>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary login">Send</button>
                            </div>

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