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
    if($session['module'] == 3){
        $app_name = 'Integrasi SIASN';
        $items = [
            ['label' => 'Data ASN', 'url' => ['/asn'], 'options' => ['title' => 'Data ASN']],
            // ['label' => 'Data ASN OPD', 'url' => ['/asn-opd'], 'options' => ['title' => 'Data ASN']],
            ['label' => 'Tabel Referensi', 'url' => '#', 'items' => [
                ['label' => 'Referensi Unor', 'url' => ['/unor'], 'options' => ['title' => 'Referensi Unor SIASN']],
                ['label' => 'Referensi Dokumen', 'url' => ['/dokumen'], 'options' => ['title' => 'Referensi Dokumen SIASN']],
            ]],
            // ['label' => 'Anomali/ Disparitas', 'url' => '#', 'items' => [
            //     ['label' => 'ASN BUP Aktif', 'url' => ['/anomali-bup'], 'options' => ['title' => 'ASN BUP masih aktif']],
            //     ['label' => 'ASN Belum SKP', 'url' => ['/anomali-skp'], 'options' => ['title' => 'ASN Belum SKP tahun sebelumnya']],
            //     ['label' => 'ASN Unor Non Aktif', 'url' => ['/anomali-unor'], 'options' => ['title' => 'ASN Unor Non Aktif']],
            //     ['label' => 'ASN Belum Validasi NIK', 'url' => ['/anomali-nik'], 'options' => ['title' => 'ASN Belum Validasi NIK']],
            // ]],
        ];
    }else if($session['module'] == 4){
        $app_name = 'Sinkron Data';
        $items = [
            //['label' => 'Data Pegawai', 'url' => ['/data-pegawai']],
            ['label' => 'Sinkronisasi', 'url' => ['/sinkron']],
            ['label' => 'SIASN - BKN', 'url' => ['/siasn']],
            ['label' => 'Simpeg - BKPSDMD', 'url' => ['/simpeg']],
            ['label' => 'Simgaji - Taspen', 'url' => ['/gaji']],
            ['label' => 'Tools', 'url' => '#', 'items' => [
                ['label' => 'Response WS SIASN BKN', 'url' => ['/response-siasn']],
                ['label' => 'Tabel Referensi Unor SIASN', 'url' => ['/tabref-unor']],
            ]],
        ];
    }else if($session['module'] == 7){
        $app_name = 'Sitampan';
        $items = [
            ['label' => 'Setting', 'url' => '#', 'items' => [
                ['label' => 'Data ASN', 'url' => ['/setting-asn']],
                ['label' => 'Hari Libur', 'url' => ['/setting-libur']],
                ['label' => 'Jenis Libur', 'url' => ['/setting-jenis-libur']],
                ['label' => 'Jenis Hari Kerja', 'url' => ['/setting-hari-kerja']],
                ['label' => 'Jenis Jam Kerja', 'url' => ['/setting-jam-kerja']],
                ['label' => 'Jenis Keterangan Presensi', 'url' => ['/setting-ket-presensi']],
                ['label' => 'Jenis Keterangan Absen', 'url' => ['/setting-ket-absen']],
                ['label' => 'Kelas Jabatan', 'url' => ['/setting-kelas-tpp']],
                ['label' => 'Lokasi Presensi', 'url' => ['/setting-lokasi-presensi']],
                ['label' => 'Parameter', 'url' => ['/setting-parameter']], //setting waktu stop input
            ]],
            ['label' => 'Presensi', 'url' => ['#'], 'items' => [
                ['label' => 'Jadwal Kerja', 'url' => ['/presensi-jadwal']],
                ['label' => 'Capaian Presensi', 'url' => ['/presensi-hasil']],
                ['label' => 'Presensi ASN', 'url' => ['/presensi-asn']],
                ['label' => 'Keterangan Absen', 'url' => ['/keterangan-absen']],
            ]],
            ['label' => 'Kinerja', 'url' => ['/kinerja-data']],
            ['label' => 'TPP', 'url' => ['/#'], 'items' => [
                //['label' => 'TPP PNS', 'url' => ['/tpp-pns']],
                ['label' => 'TPP PLT', 'url' => ['/tpp-plt']],
                ['label' => 'Penerima TPP', 'url' => ['/tpp-penerima']],
                ['label' => 'Cetak TPP', 'url' => ['/tpp-cetak']],
            ]],
            //['label' => 'Laporan', 'url' => ['/preskin-report']], //laporan capaian presensi dan kinerja
            ['label' => 'Reset Presensi APK', 'url' => ['/reset-apk']],
        ];
    }else if($session['module'] == 8){
        $app_name = '';
        $items = [
            ['label' => 'Reset ', 'url' => ['/data-pegawai']],
            // ['label' => 'Tabel Referensi', 'url' => '#', 'items' => [
            //     ['label' => 'SIASN Unor', 'url' => ['/ref-unor']],
            // ]],
        ];
    }else if($session['module'] == 10){
        $app_name = 'Sitampan';
        $items = [
            ['label' => 'Data ASN', 'url' => ['/asn-presensi']],
            ['label' => 'Presensi', 'url' => ['/data-presensi']],
            ['label' => 'Kinerja', 'url' => ['/data-kinerja']],
            ['label' => 'TPP', 'url' => ['/data-tpp']],
            ['label' => 'Reset', 'url' => ['/reset']],
        ];
    }else if($session['module'] == 11){
        $app_name = 'Simpeg Integrasi';
        $items = [
            ['label' => 'Data ASN', 'url' => ['/dataasn']],
        ];
    }

    NavBar::begin([
        'brandLabel' => $app_name,
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
            //. Yii::$app->session['namapengguna'].' - '.Yii::$app->session['nip']
            . Yii::$app->session['namapengguna']
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
    'size' => 'modal-lg',
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