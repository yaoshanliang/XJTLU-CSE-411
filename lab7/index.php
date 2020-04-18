<!DOCTYPE html>
<html lang="en-us">

<head>
    <title>All about Fruit</title>
    <meta charset="utf-8" />
    <meta name="description" content="Different kind of fruits." />
</head>

<style>
    body {
        font-family: Times, "Times New Roman", serif;
        margin: 0px;
        background-color: #fff;
    }

    h1 {
        color: #212121;
        font-size: 30px;
    }

    h2 {
        color: #424242;
        font-size: 24px;
    }

    h3 {
        color: #616161;
        font-size: 20px;
    }

    table {
        border-collapse: collapse;
    }

    table,
    td,
    th {
        border: 1px solid #616161;
    }

    a {
        color: #4fc3f7;
        text-decoration: none;
    }

    a:hover {
        color: #29b6f6;
    }

    ul {
        list-style-type: square;
    }

    img {
        /* border: 3px solid #616161; */
        padding: 3px;
        background: #fff;
    }

    .nav-menu {
        width: 100%;
        height: 60px;
        background: #9ccc65;
        border-bottom: 1px solid #9ccc65;
        margin-bottom: 0px !important;
    }

    .navbar-nav {
        float: left;
        margin: 0;
    }

    .nav {
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
    }

    .navbar-nav>li {
        float: left;
    }

    .nav>li {
        position: relative;
        display: block;
    }

    .nav.navbar-nav>li>a {
        color: #fff;
        font-size: 18px;
    }

    .navbar-brand,
    .navbar-nav>li>a {
        font-weight: 500;
        margin-left: 0 !important;
        line-height: 32px;
    }

    .nav>li>a {
        position: relative;
        display: block;
        padding: 13px 15px 12px;
    }

    .list {
        width: 100%;
        height: 320px;
        margin: 10px;
    }

    .col-md-20 {
        position: relative;
        min-height: 1px;
        float: left;
        padding-top: 20px;
        padding-right: 10px;
        padding-left: 10px;
        width: 18%;
        background-color: antiquewhite;
    }

    .thumbnail {
        height: 300px;
        overflow: hidden;
    }

    .thumbnail .image {
        height: 200px;
        overflow: hidden;
    }

    .view {
        overflow: hidden;
        position: relative;
        text-align: center;
        box-shadow: 1px 1px 2px #e6e6e6;
        cursor: default;
    }

    .thumbnail .caption {
        padding: 9px;
        color: #333;
    }

    .caption {
        padding: 9px 5px;
        background: #f7f7f7;
    }

    caption p {
        margin-bottom: 5px;
    }

    .image-item {
        height: 90%;
        width: 90%;
        display: block;
    }

    .detail {
        padding: 20px;
        position: relative;
        width: 100%;
        float: left;
        clear: both;
        margin-top: 5px;
    }

    .left {
        width: 30%;
        float: left;
    }

    .right {
        width: 65%;
        position: relative;
        min-height: 1px;
        float: left;
        padding-right: 10px;
        padding-left: 30px;
    }

    .product-image img {
        width: 90%;
    }

    .product-gallery img {
        width: 80px;
        margin-top: 15px;
        border: 1px solid #616161;
        cursor: pointer;
    }

    img {
        vertical-align: middle;
    }

    .prod-title {
        border-bottom: 1px solid #dfdfdf;
        padding-bottom: 5px;
        margin: 30px 0;
        font-size: 24px;
        font-weight: 400;
    }

    .product-image {
        border: 2px solid #616161;
        width: 100%;
        height: 460px;
    }

    .navbar-right {
        float: right;
    }

    .navbar-right a {
        color: #fff;
    }

    .navbar-right>li>a {
        position: relative;
        display: block;
        padding: 9px 15px 12px;
    }

    .audio-item {
        float: left;
        margin: 0px 0px 10px 20px;
    }

    .button {
        float: left;
        width: 80px;
        height: 30px;
        padding-top: 20px;
        padding-left: 20px;
    }

    button {
        padding-left: 20px;
        width: 100px;
        height: 30px;
    }

    .progress {
        margin: 0 0 20px 20px;
        width: 800px;
        height: 5px;
        background-color: antiquewhite;
        border: 1px solid #dfdfdf;
    }

    #bar {
        background-color: #616161;
        width: 0;
        height: 5px;
    }
</style>

<body>

    <?php
    ini_set('display_errors', 1);
    $menus = [
        'apple' => ['name' => 'Apple', 'image' => 'apple.jpg', 'desc' => 'Green	Medium	Crispy'],
        'orange' => ['name' => 'Orange', 'image' => 'orange.jpg', 'desc' => 'Orange	Large	Sweet'],
        'lemon' => ['name' => 'Lemon', 'image' => 'lemon.jpg', 'desc' => 'Yellow	Small	Tangy'],
        'banana' => ['name' => 'Banana', 'image' => 'banana.jpg', 'desc' => 'Yellow	Large	Soft']
    ];
    $fruitType = $_GET['type'] ?? 'all'; // Requires PHP7.0+


    // This is an example of showing the content.
    // The content of description should be much more and stored in database.
    $fruits = [
        'all' => [
            'title' => 'All about fruits',
            'content' =>
            "<h3>Background</h3>
                <p>In botany, a fruit is the seed-bearing structure in flowering plants (also known as angiosperms) formed from the ovary after flowering.

                Fruits are the means by which angiosperms disseminate seeds. Edible fruits, in particular, have propagated with the movements of humans and animals in a symbiotic relationship as a means for seed dispersal and nutrition; in fact, humans and many animals have become dependent on fruits as a source of food.[1] Accordingly, fruits account for a substantial fraction of the world's agricultural output, and some (such as the apple and the pomegranate) have acquired extensive cultural and symbolic meanings.
                
                In common language usage, fruit normally means the fleshy seed-associated structures of a plant that are sweet or sour, and edible in the raw state, such as apples, bananas, grapes, lemons, oranges, and strawberries. On the other hand, in botanical usage, fruit includes many structures that are not commonly called fruits, such as bean pods, corn kernels, tomatoes, and wheat grains.[2][3] The section of a fungus that produces spores is also called a fruiting body
                </p>"
        ],
        'apple' => [
            'title' => 'Apple',
            'content' =>
            "<h3>Background</h3>
                <p>The apple tree (Malus pumila, commonly and erroneously called Malus domestica) is a deciduous tree in the
                    rose
                    family best known for its sweet, pomaceous fruit, the apple. It is cultivated worldwide as a fruit tree,
                    and is
                    the most widely grown species in the genus Malus. The tree originated in Central Asia, where its wild
                    ancestor,
                    Malus sieversii, is still found today. Apples have been grown for thousands of years in Asia and Europe,
                    and
                    were brought to North America by European colonists. Apples have religious and mythological significance
                    in many
                    cultures, including Norse, Greek and European Christian traditions. </p>
                <p>Apple trees are large if grown from seed. Generally apple varieties are propagated by grafting onto
                    rootstocks,
                    which control the size of the resulting tree. There are more than 7,500 known cultivars of apples,
                    resulting in
                    a range of desired characteristics. Different cultivars are bred for various tastes and uses, including
                    cooking,
                    eating raw and cider production. Trees and fruit are prone to a number of fungal, bacterial and pest
                    problems,
                    which can be controlled by a number of organic and non-organic means. In 2010, the fruit's genome was
                    sequenced
                    as part of research on disease control and selective breeding in apple production. </p>
                "
        ],
        'orange' => [
            'title' => 'Orange',
            'content' =>
            "<h3>Background</h3>
                <p>The orange (specifically, the sweet orange) is the fruit of the citrus species Citrus � sinensis in the
                    family
                    Rutaceae. </p>
                <p> The fruit of the Citrus  sinensis is considered a sweet orange, whereas the fruit of the Citrus � aurantium
                    is
                    considered a bitter orange. The sweet orange reproduces asexually (apomixis through nucellar embryony);
                    varieties of sweet orange arise through mutations. </p> The orange is a hybrid, between pomelo (Citrus
                maxima)
                and mandarin (Citrus reticulata). It has genes that are ~25% pomelo and ~75% mandarin;however, it is not a
                simple
                backcrossed BC1 hybrid, but hybridized over multiple generations. The chloroplast genes, and therefore the
                maternal
                line, seem to be pomelo. The sweet orange has had its full genome sequenced. Earlier estimates of the percentage
                of
                pomelo genes varying from ~50% to 6% have been reported.
                <p></p>
                <p> Sweet oranges were mentioned in Chinese literature in 314 BC. As of 1987, orange trees were found to be the
                    most
                    cultivated fruit tree in the world. Orange trees are widely grown in tropical and subtropical climates for
                    their
                    sweet fruit. The fruit of the orange tree can be eaten fresh, or processed for its juice or fragrant peel.
                    As of
                    2012, sweet oranges accounted for approximately 70% of citrus production. </p>"
        ],
        'lemon' => [
            'title' => 'Lemon',
            'content' =>
            "<h3>Background</h3>
                <p>The lemon (Citrus limon) is a species of small evergreen tree native to Asia. </p>
                <p> The tree's ellipsoidal yellow fruit is used for culinary and non-culinary purposes throughout the world,
                    primarily for its juice, which has both culinary and cleaning uses. The pulp and rind (zest) are also used
                    in
                    cooking and baking. The juice of the lemon is about 5% to 6% citric acid, which gives a sour taste. The
                    distinctive sour taste of lemon juice makes it a key ingredient in drinks and foods such as lemonade and
                    lemon
                    meringue pie. </p>
                <p> The origin of the lemon is unknown, though lemons are thought to have first grown in Assam (a region in
                    northeast India), northern Burma or China. A study of the genetic origin of the lemon reported it to be
                    hybrid
                    between bitter orange (sour orange) and citron. </p>
                <p> Lemons entered Europe near southern Italy no later than the first century AD, during the time of Ancient
                    Rome.
                    However, they were not widely cultivated. They were later introduced to Persia and then to Iraq and Egypt
                    around
                    700 AD. The lemon was first recorded in literature in a 10th-century Arabic treatise on farming, and was
                    also
                    used as an ornamental plant in early Islamic gardens. It was distributed widely throughout the Arab world
                    and
                    the Mediterranean region between 1000 and 1150. </p>"
        ],
        'banana' => [
            'title' => 'Banana',
            'content' =>
            "<h3>Background</h3>
                <p>The banana is an edible fruit, botanically a berry,[1][2] produced by several kinds of large herbaceous
                    flowering
                    plants in the genus Musa.[3] In some countries, bananas used for cooking may be called plantains, in
                    contrast to
                    dessert bananas. The fruit is variable in size, color and firmness, but is usually elongated and curved,
                    with
                    soft flesh rich in starch covered with a rind which may be green, yellow, red, purple, or brown when ripe.
                    The
                    fruits grow in clusters hanging from the top of the plant. Almost all modern edible parthenocarpic
                    (seedless)
                    bananas come from two wild species Musa acuminata and Musa balbisiana. The scientific names of most
                    cultivated
                    bananas are Musa acuminata, Musa balbisiana, and Musa  paradisiaca for the hybrid Musa acuminata M.
                    balbisiana, depending on their genomic constitution. The old scientific name Musa sapientum is no longer
                    used.
                </p>
                <p> Musa species are native to tropical Indomalaya and Australia, and are likely to have been first domesticated
                    in
                    Papua New Guinea.[4][5] They are grown in 135 countries,[6] primarily for their fruit, and to a lesser
                    extent to
                    make fiber, banana wine and banana beer and as ornamental plants. </p>
                <p> Worldwide, there is no sharp distinction between &quot;bananas&quot; and &quot;plantains&quot;. Especially
                    in
                    the Americas and Europe, &quot;banana&quot; usually refers to soft, sweet, dessert bananas, particularly
                    those
                    of the Cavendish group, which are the main exports from banana-growing countries. By contrast, Musa
                    cultivars
                    with firmer, starchier fruit are called &quot;plantains&quot;. In other regions, such as Southeast Asia,
                    many
                    more kinds of banana are grown and eaten, so the simple two-fold distinction is not useful and is not made
                    in
                    local languages. </p>"
        ],
    ];
    ?>

    <!-- The menu -->
    <div class="nav-menu">
        <nav>
            <ul class="nav navbar-nav">
                <li class="" id="menu">
                    <a style="font-size: 20px; font-weight: bold;" href="./index.php">
                        All About Fruits
                    </a>

                </li>
                <?php foreach ($menus as $k => $v) { ?>
                    <li>
                        <a href="./index.php?type=<?php echo $k; ?>">
                            <?php echo $v['name']; ?>
                        </a>
                    </li>
                <?php } ?>

            </ul>
            <ul class="nav navbar-right">
                <li class="">
                    <a style="font-size: 16px;" href="javascript:;" onclick="return changeBackgroundColor();">
                        Change Background
                    </a>
                </li>

            </ul>
        </nav>
    </div>

    <!-- The fruit images -->
    <div class="list" id="list">
        <?php foreach ($menus as $k => $v) { ?>
            <div class="col-md-20">
                <div class="thumbnail">
                    <div class="image view view-first">
                        <a href="index.php?type=<?php echo $k; ?>">
                            <img class="image-item" src="./images/<?php echo $v['image']; ?>" alt="<?php echo $v['name']; ?>">
                        </a>
                    </div>
                    <div class="caption">
                        <p><?php echo $v['desc']; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- The detail content -->
    <div class="detail">
        <h3 class="prod-title"><?php echo $fruits[$fruitType]['title']; ?></h3>
        <?php echo $fruits[$fruitType]['content']; ?>
    </div>
</body>

<script>
    function changeBackgroundColor() {
        var ele = document.querySelector("body");
        console.log(ele.style.backgroundColor)
        if (ele.style.backgroundColor == '' || ele.style.backgroundColor == "rgb(255, 255, 255)") {
            ele.style.backgroundColor = "#E8F5E9";
        } else {
            ele.style.backgroundColor = "#FFF";
        }
    }
</script>

</html>