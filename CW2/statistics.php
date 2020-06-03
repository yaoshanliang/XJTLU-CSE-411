<?php
include_once('header.php');
include_once('function.php');
?>

<?php
if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $result = $dbHelper->getMyStatistics();
    $data = $result['data'];
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="page-common course-page">
            <div class="content">
                <section class="content-detail">
                    <p class="header">My Statistics</p>

                    <div class="statistics-item">
                        <p class="title">Total number of calories burned: </p> <p><?php echo $data['calories'];?></p>
                    </div>
                    <div class="statistics-item">
                        <p class="title">Total distance travelled: </p> <p><?php echo $data['distance'];?></p>
                    </div>
                    <div class="statistics-item">
                        <p class="title">Average speed: </p> <p><?php echo $data['avg_speed'];?></p>
                    </div>
                    <div class="statistics-item">
                        <p class="title">Most frequent sport: </p> <p><?php echo $data['sport'];?></p>
                    </div>

                    <div class="statistics-item">
                        <p class="title">Total number of sports: </p> <p><?php echo $data['total'];?></p>
                    </div>

                    <div class="statistics-item">
                        <p class="title">Total number of public sports: </p> <p><?php echo $data['public'];?></p>
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