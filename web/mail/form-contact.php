<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use app\modules\shop\models\Contact;

/**
 * @var $model Contact
 */

?>
<p><strong>Name: </strong><?= $model->first_name . ' ' . $model->last_name ?></p>
<p><strong>Email: </strong><?= $model->email ?></p>
<p><strong>Phone: </strong><?= $model->phone ?></p>
<p><strong>Message: </strong><?= $model->text ?></p>
