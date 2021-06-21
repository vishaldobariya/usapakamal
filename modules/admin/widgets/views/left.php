<?php

/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

use yii\helpers\Html;

?>
<div class="nk-sidebar">
	<div class="nav-header bg-primary">
		<div class="nav-control">
			<div class="hamburger"><span class="line"></span> <span class="line"></span> <span class="line"></span>
			</div>
		</div>
	</div>
	<div class="nk-nav-scroll-custom">
		<ul class="metismenu" id="menu">
			<?php foreach ($items as $item) : ?>
				<?php if (isset($item['visible']) && $item['visible'] == false) {
					continue;
				} ?>
				<?php if (isset($item['section'])) : ?>
					<li class="nav-label"><?= $item['section'] ?></li>
				<?php elseif (isset($item['sub'])) : ?>
					<li class="mega-menu"><?= Html::a('<i class="' . $item['icon'] . '"></i><span class="nav-text">' . $item['label'] . '</span>', $item['url'], ['class' => 'dropdown-toggle']) ?>
						<ul style=" " class="collapse" id="<?php $item['label'] ?>">
							<?php foreach ($item['sub'] as $sub) : ?>
								<li class="hh"><?= Html::a('<i class="' . $sub['icon'] . '"></i><span class="nav-text">' . $sub['label'] . '</span>', $sub['url']) ?></li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php else : ?>
					<li class="mega-menu"><?= Html::a('<i class="' . $item['icon'] . '"></i><span class="nav-text">' . $item['label'] . '</span>', $item['url']) ?></li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	</div>
</div>