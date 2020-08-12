<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div>
	<a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
	<div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
			  <!-- ## Panel Content  -->
				<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
				<h1><?= Yii::t('form', 'Login Form'); ?></h1>
				<?= $form->field($model, 'username')->textInput(['placeholder'=>Yii::t('form', 'Login')]) ?>
				<?= $form->field($model, 'password')->passwordInput(['placeholder'=>Yii::t('form', 'Password')]) ?>
				<?= $form->field($model, 'rememberMe')->checkbox() ?>
				<div class="form-group">
						<?= Html::submitButton(Yii::t('form', 'Login'), ['class' => 'btn btn-default submit', 'name' => 'login-button']) ?>
				</div>
				<?php ActiveForm::end(); ?>
          </section>
        </div>
      </div>
</div>
