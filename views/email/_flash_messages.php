<?php if (Yii::$app->getSession()->hasFlash('email')): ?>
<div class="alert alert-success">
    <?= Yii::$app->getSession()->getFlash('email') ?>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('email-error')): ?>
<div class="alert alert-danger">
    <?= Yii::$app->getSession()->getFlash('email-error') ?>
</div>
<?php endif; ?>