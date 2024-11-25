<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\PostRwSkp22 */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Post Data To SIASN';
$this->params['breadcrumbs'][] = $this->title;

$asn = \app\modules\integrasi\models\DataUtama::find()
->select(["*", "CONCAT(nipBaru,' ' , nama) AS asn"])
//->where(['like', 'detail', 'get'])
->asArray()
->all();

?>

<div class="post-rw-skp22-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">

        <?= $form->field($model, 'id')->textInput(['values' => NULL]) ?>

        <?= $form->field($model, 'penilaiNipNrp')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($asn, 'nipBaru', 'asn'),
                'language' => 'id',
                'options' => [
                    'placeholder' => '- Pilih -',  
                    'autocomplete' => 'off',
                    'id'=>'atasan',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]);?>

        <?= $form->field($model, 'penilaiGolongan')->textInput(['maxlength' => true, 'id' => 'penilai-gol']) ?>

        <?= $form->field($model, 'penilaiJabatan')->textInput(['maxlength' => true, 'id' => 'penilai-jab']) ?>

        <?= $form->field($model, 'penilaiNama')->textInput(['maxlength' => true, 'id' => 'penilai-nama']) ?>

        <?= $form->field($model, 'penilaiUnorNama')->textInput(['maxlength' => true, 'id' => 'penilai-unor']) ?>

        <?= $form->field($model, 'statusPenilai')->textInput(['value' => 'ASN']) ?>

    </div>
    <div class="col-lg-6">

        <?= $form->field($model, 'mode')->widget(Select2::classname(), [
                'data' => ['train' => 'Training', 'prod' => 'Production'],
                'language' => 'id',
                'options' => [
                    'placeholder' => '- Pilih -',  
                    'autocomplete' => 'off',
                    'id'=>'mode',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]);?>
            
            <?= $form->field($model, 'pnsDinilaiOrang')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($asn, 'id', 'asn'),
                'language' => 'id',
                'options' => [
                    'placeholder' => '- Pilih -',  
                    'autocomplete' => 'off',
                    'id'=>'pns',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]);?>

        <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>    

        <?= $form->field($model, 'perilakuKerjaNilai')->widget(Select2::classname(), [
                'data' => [1 => 'DIATAS EKSPEKTASI', 2 => 'SESUAI EKSPEKTASI', 3 => 'DIBAWAH EKSPEKTASI'],
                'language' => 'id',
                'options' => [
                    'placeholder' => '- Pilih -',  
                    'autocomplete' => 'off',
                    'id'=>'perilaku',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]);?>

        <?= $form->field($model, 'hasilKinerjaNilai')->widget(Select2::classname(), [
                'data' => [1 => 'DIATAS EKSPEKTASI', 2 => 'SESUAI EKSPEKTASI', 3 => 'DIBAWAH EKSPEKTASI'],
                'language' => 'id',
                'options' => [
                    'placeholder' => '- Pilih -',  
                    'autocomplete' => 'off',
                    'id'=>'kinerja',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]);?>

        <?= $form->field($model, 'kuadranKinerjaNilai')->widget(Select2::classname(), [
                'data' => [1 => 'SANGAT BAIK', 2 => 'BAIK', 3 => 'BUTUH PERBAIKAN', 4 => 'KURANG', 5 => 'SANGAT KURANG'],
                'language' => 'id',
                'options' => [
                    'placeholder' => '- Pilih -',  
                    'autocomplete' => 'off',
                    'id'=>'kuadran',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]);?>

        <?= $form->field($model, 'path')->textInput(['values' => NULL]) ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
            <?= Html::a('<i class="fas fa-minus-circle"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$urlData = Url::to(['/integrasi/training/get-asn']);
$script = <<< JS

$('#atasan').change(function(){
	var zipId = $(this).val();
 
	$.get("{$urlData}",{ zipId : zipId },function(data){
		var data = $.parseJSON(data);
		$('#penilai-gol').attr('value',data.golRuangAkhirId);
        $('#penilai-nama').attr('value',data.nama);
        $('#penilai-jab').attr('value',data.jabatanNama);
        $('#penilai-unor').attr('value',data.unorIndukNama);
	});
});
JS;
$this->registerJs($script);
?>