<?php $this->beginPage() ?>
<!DOCTYPE html>
	<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Order Success</title>
        <style>
            .title {
	            font-family: Open Sans, sans-serif, sans-serif;
	            padding-top: 30px;
	            margin: 0 auto;
	            font-style: normal;
	            font-weight: bold;
	            font-size: 29px;
	            line-height: 34px;
	            padding-bottom: 0;
	            text-align: center;
	            color: #44566C;
            }

            .btn {
	            display: inline-block;
	            min-width: 180px;
	            text-decoration: none;
	            font-style: normal;
	            line-height: 40px;
	            border-radius: 2px;
	            box-shadow: none;
	            border: 0;
	            font-family: Open Sans, sans-serif, sans-serif;
	            height: 40px;
	            text-shadow: none;
	            padding: 0 14px;
	            user-select: none;
	            text-align: center;
	            vertical-align: middle;
	            background: #dd1b25;
	            color: #fff;
	            font-weight: 700;
	            text-transform: uppercase;
	            font-size: 16px;
            }

            .text {
	            padding-top: 30px;
	            padding-bottom: 0;
	            margin: 0 auto;
	            font-style: normal;
	            font-weight: normal;
	            font-size: 18px;
	            line-height: 26px;
	            text-align: center;
	            color: #627D98;
            }
        </style>
	    <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
        </body>

</html>
<?php $this->endPage() ?>
