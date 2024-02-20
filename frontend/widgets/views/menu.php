<?php
/* @var $menu common\models\entity\Menu */

?>

<?php if($menu) :?>
    <?php foreach ($menu as $item): ?>
        <li><a class="" href="<?=$item->url?>"><?=$item->title?></a></li>
    <?php endforeach; ?>
<?php endif; ?>