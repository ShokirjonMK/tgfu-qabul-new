<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Student;

/** @var $model */
/** @var Student $student */
/** @var $id */

$model->birthday = $student->birthday;
$model->seria = $student->passport_serial;
$model->number = $student->passport_number;
?>

<div class="step_one_box">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'top40'],
        'fieldConfig' => [
            'template' => '{label}{input}{error}',
        ]
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'birthday')->textInput(['type' => 'date', 'id' => 'ikBirthdayDate'])->label(Yii::t("app" , "Tug\'ilgan sana").' <span>*</span>'); ?>
    </div>

    <div class="form-group mt-2">
        <?= $form->field($model, 'seria')->textInput([
            'maxlength' => true,
            'placeholder' => '__',
            'oninput' => "this.value = this.value.replace(/\\d/, '').toUpperCase()"
        ])->label(Yii::t("app" , "Pasport seriya").' <span>*</span>') ?>
    </div>

    <div class="form-group mt-2">
        <?= $form->field($model, 'number')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '9999999',
            'options' => [
                'placeholder' => '_______',
            ],
        ])->label(Yii::t("app" , "Pasport raqam").' <span>*</span>') ?>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


<?php
// Register the script to initialize the datepicker with d-m-Y format
$this->registerJs("
    $('#ikBirthdayDate').datepicker({
        dateFormat: 'dd-mm-yy'
    });
");
?>




