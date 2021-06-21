<?php
/**
 * @author      Anthony <xristmas365@gmail.com>
 * @copyright   industrialax.com
 * @license     https://industrialax.com/crm-general-license
 */

namespace app\modules\storage\controllers;

use yii\web\Controller;
use app\components\DeleteSliderAction;
use app\components\UploadSliderAction;
use app\modules\storage\actions\DeleteAction;

class DefaultController extends Controller
{
	
	public function actions()
	{
		return [
			'upload'        => [
				'class'                  => 'trntv\filekit\actions\UploadAction',
				'responsePathParam'      => 'path',
				'responseBaseUrlParam'   => 'base_url',
				'responseUrlParam'       => 'url',
				'responseDeleteUrlParam' => 'delete_url',
				'responseMimeTypeParam'  => 'type',
				'responseNameParam'      => 'name',
				'responseSizeParam'      => 'size',
				'deleteRoute'            => 'delete',
				'fileStorage'            => 'fileStorage',
				'fileStorageParam'       => 'fileStorage',
				'sessionKey'             => '_uploadedFiles',
				'allowChangeFilestorage' => false,
			
			],
			'upload-slider' => [
				'class'                  => UploadSliderAction::class,
				'responsePathParam'      => 'path',
				'responseBaseUrlParam'   => 'base_url',
				'responseUrlParam'       => 'url',
				'responseDeleteUrlParam' => 'delete_url',
				'responseMimeTypeParam'  => 'type',
				'responseNameParam'      => 'name',
				'responseSizeParam'      => 'size',
				'deleteRoute'            => 'delete-slider',
				'fileStorage'            => 'fileStorage',
				'fileStorageParam'       => 'fileStorage',
				'sessionKey'             => '_uploadedFiles',
				'allowChangeFilestorage' => false,
				'model_name'             => 'Slider',
			],
			'upload-middle' => [
				'class'                  => UploadSliderAction::class,
				'responsePathParam'      => 'path',
				'responseBaseUrlParam'   => 'base_url',
				'responseUrlParam'       => 'url',
				'responseDeleteUrlParam' => 'delete_url',
				'responseMimeTypeParam'  => 'type',
				'responseNameParam'      => 'name',
				'responseSizeParam'      => 'size',
				'deleteRoute'            => 'delete-slider',
				'fileStorage'            => 'fileStorage',
				'fileStorageParam'       => 'fileStorage',
				'sessionKey'             => '_uploadedFiles',
				'allowChangeFilestorage' => false,
				'model_name'             => 'Middle Image',
			],
			'upload-footer' => [
				'class'                  => UploadSliderAction::class,
				'responsePathParam'      => 'path',
				'responseBaseUrlParam'   => 'base_url',
				'responseUrlParam'       => 'url',
				'responseDeleteUrlParam' => 'delete_url',
				'responseMimeTypeParam'  => 'type',
				'responseNameParam'      => 'name',
				'responseSizeParam'      => 'size',
				'deleteRoute'            => 'delete-slider',
				'fileStorage'            => 'fileStorage',
				'fileStorageParam'       => 'fileStorage',
				'sessionKey'             => '_uploadedFiles',
				'allowChangeFilestorage' => false,
				'model_name'             => 'Footer Image',
			],
			'upload-mobile-slider' => [
				'class'                  => UploadSliderAction::class,
				'responsePathParam'      => 'path',
				'responseBaseUrlParam'   => 'base_url',
				'responseUrlParam'       => 'url',
				'responseDeleteUrlParam' => 'delete_url',
				'responseMimeTypeParam'  => 'type',
				'responseNameParam'      => 'name',
				'responseSizeParam'      => 'size',
				'deleteRoute'            => 'delete-slider',
				'fileStorage'            => 'fileStorage',
				'fileStorageParam'       => 'fileStorage',
				'sessionKey'             => '_uploadedFiles',
				'allowChangeFilestorage' => false,
				'model_name'             => 'Mobile Slider',
			],
			'delete-slider' => [
				'class' => DeleteSliderAction::class,
			],
			'delete'        => [
				'class' => DeleteAction::class,
			],
			'view'          => [
				'class' => 'trntv\filekit\actions\ViewAction',
			],
		];
	}
}
