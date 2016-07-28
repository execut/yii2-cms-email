<?php if (Yii::$app->getSession()->hasFlash('news')): ?>
<div class="alert alert-success">
    <?= Yii::$app->getSession()->getFlash('news') ?>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('news-error')): ?>
<div class="alert alert-danger">
    <?= Yii::$app->getSession()->getFlash('news-error') ?>
</div>
<?php endif; ?>