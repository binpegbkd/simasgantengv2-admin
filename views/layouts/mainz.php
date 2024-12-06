<?php

/** @var yii\web\View $this */
/** @var string $content */

use dominus77\sweetalert2\Alert;
//use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Modal;

\app\assets\AppAsset::register($this);
\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
<title><?= Html::encode($this->title).' : '.Yii::$app->name ?></title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody(); date_default_timezone_set("Asia/jakarta"); ?>

<header id="header" style="background-color:white;">
    <div class="navbar bg-light" style="background-color:white; background-image: url(<?= Yii::getAlias('@web') ?>/banner-bg-web.png); background-position-x: right;">
        <div class="row">
            <img class="logo_image"	src="<?= Yii::getAlias('@web/banner-red.png') ?>" alt="BKPSDMD Kab. Brebes" height="100">
        </div>
    </div>
    
    <?php
    $session = Yii::$app->session;
    if($session['module'] == 4){
        $items = [
            ['label' => 'Data Pegawai', 'url' => ['/data-pegawai']],
            ['label' => 'Tabel Referensi', 'url' => '#', 'items' => [
                ['label' => 'SIASN Unor', 'url' => ['/ref-unor']],
            ]],
            ['label' => 'Sinkron Data', 'url' => '#', 'items' => [
                ['label' => 'Simgaji - Taspen', 'url' => ['/gaji']],
                ['label' => 'Simpeg - BKPSDMD', 'url' => ['/simpeg']],
                ['label' => 'SIASN - BKN', 'url' => ['/siasn']],
                ['label' => 'Cek Sinkronisasi', 'url' => ['/sinkron']],
            ]],
        ];
    }
    NavBar::begin([
        'brandLabel' => 'Administrator',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-info', 
            'id' => 'navbar-menu',
        ]
    ]);

	echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $items,
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => [
            '<li class="nav-item"><div class="nav-link">'
            . Yii::$app->session['namapengguna'].' - '.Yii::$app->session['nip']
            . '</div></li>'
            . '<li class="nav-item">'
            //. Html::beginForm(['/site/logout'])
            //. Html::beginForm(['#'], 'post', ['id' => 'logout-form'])
            . Html::button(
                '<i class="fas fa-arrow-circle-left"></i>',
                ['class' => 'nav-link btn btn-danger logout', 'title' => 'Kembali', 'name' => 'logout', 'id' => 'logout-button'],
            )
            //. Html::endForm()
            . '</li>'
        ]
    ]);
    NavBar::end();
    
    ?>
    <div>
</header>

<main id="main" class="flex-shrink-0" role="main">

    <div class="container">
        <?php /*if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif*/ ?>
        <?= Alert::widget(['useSessionFlash' => true]); ?>
        <div class="text">
            &nbsp;
            <?= $content ?>
        </div>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-left text-md-start">BKPSDMD Kabupaten Brebes : Simasganteng 2.0</div>
            <div class="col-md-6 text-right text-md-end">
                <!-- Menampilkan Jam (Aktif) -->
	            <div id="clock"></div>
            </div>
        </div>
    </div>   
</footer>

<?php
Modal::begin([
    'title' => Html::encode($this->title),
    'headerOptions' => ['class' => 'bg-info'],
    'id' => 'modal',
    'size' => 'modal-md',
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
]);

echo "<div id='modalContent'></div>";
Modal::end();

$urlData = Url::to(['/site/sign-out']);
$script = <<< JS

$('#logout-button').click(function () {
    Swal.fire({
        title: 'Kembali ke Menu Utama ???',
        icon: 'question',
		text: 'Menuju menu utama',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kembali',
        cancelButtonText: 'Tidak',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        //closeOnConfirm: true,
        //closeOnCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("{$urlData}");
        } 
    });
        
});

JS;
$this->registerJs($script);
?>

<script type="text/javascript">
    function showTime() {
        var today = new Date();
        var curr_hour = today.getHours();
        var curr_minute = today.getMinutes();
        var curr_second = today.getSeconds();
        
        curr_hour = checkTime(curr_hour);
        curr_minute = checkTime(curr_minute);
        curr_second = checkTime(curr_second);
        document.getElementById('clock').innerHTML=thisDay + ', ' + day + ' ' + months[month] + ' ' + year + ' ' 
        + curr_hour + ":" + curr_minute + ":" + curr_second;
        }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    setInterval(showTime, 500);
    //-->

    // Menampilkan Hari, Bulan dan Tahun
    var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth();
    var thisDay = date.getDay(),
        thisDay = myDays[thisDay];
    var yy = date.getYear();
    var year = (yy < 1000) ? yy + 1900 : yy;
    //document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
    //-->
</script>

<script>
    window.onscroll = function() {myFunction()};
    var navbar = document.getElementById("navbar-menu");
    var sticky = navbar.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= sticky) {
            navbar.classList.add("fixed-top")
        } else {
            navbar.classList.remove("fixed-top");
        }
    }
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>