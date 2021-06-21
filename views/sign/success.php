<?php

use yii\helpers\Url; ?>
<main class="page">

     <div class="container-sm py-5">
         <h1 class="h1 mb-0 p-0 product-title mb-4 text-center">Registration completed successfully</h1>
	     <h4 class="h4 mb-5 text-center d-block pr-0 ">We've sent you a login link. Please, check Your email or just <a href="<?=Url::toRoute(['/sign/in'])?>">login here</a></h4>
     </div>
 </main>
