<?php

?>

<p>Provider <b><?= $name ?></b> refused the order #<?= 10000 + $order ?></p>
<?php if($sec_provider) : ?>
<p>The order #<?= 10000 + $order ?> was offered to the provider <?= $sec_provider?></p>
<?php else: ?>
	The order <b>#<?= 10000 + $order ?></b>. <b>Executor not found.</b>
<?php endif;?>
