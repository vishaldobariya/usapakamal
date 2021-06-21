<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\components;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

class AdminGrid extends GridView
{

	const COLUMN_ACTION   = ['class' => 'app\components\ActionColumn'];
	const COLUMN_CHECKBOX = ['class' => 'kartik\grid\CheckboxColumn'];

	public $title                = 'List';

	public $createButton;

	public $extraSearch          = '';

	public $pjax                 = true;

	public $bordered             = false;

	public $export               = false;

	public $striped              = false;

	public $toolbar              = false;

	public $panel                = [
		'heading' => 'USERS',
		'before'  => false,
		'after'   => false,
	];

	public $tableOptions         = [
		'class' => 'text-nowrap',
	];

	public $dataColumnClass      = DataColumn::class;

	public $resizableColumns     = false;

	public $summaryOptions       = [
		'class' => 'pt-3',
	];

	public $panelHeadingTemplate = '
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <div class="d-flex justify-content-start align-items-center">{createButton}{gridTitle}</div>
    <div class="d-flex justify-content-end align-items-start">{extraSearch}{export}</div>
</div>';

	public $panelFooterTemplate  = <<< HTML
<div class="d-flex justify-content-between">
     <div class="d-flex justify-content-start align-items-center">{summary}</div>
     <div class="d-flex justify-content-start align-items-center">{pager}</div>
    <div class="d-flex justify-content-end align-items-center"><span class="text-nowrap mr-2">Page size:</span>{sizer}</div>
</div>
HTML;

	public $pager                = [
		'options'        => [
			'class' => 'pagination pagination-sm mt-0',
		],
		'pageCssClass'   => 'page-item',
		'maxButtonCount' => 5,
	];

	public function renderSection($name)
	{
		switch ($name) {
			case '{sizer}':
				return $this->renderSizer();
			case '{gridTitle}':
				return $this->renderGridTitle();
			case '{extraSearch}':
				return $this->renderExtraSearch();
			case '{createButton}':
				return $this->renderCreateButton();
			default:
				return parent::renderSection($name);
		}
	}

	public function renderSizer()
	{
		$value = Yii::$app->session->get('page-size', Yii::$app->params['grid']['default-size']);

		return Html::dropDownList('grid-sizer', $value, Yii::$app->params['grid']['sizes'], [
			'class'     => 'form-control form-control-sm page-sizer',
			'data-url'  => Url::toRoute(['/settings/view/page-size']),
			'data-pjax' => $this->pjaxSettings['options']['id'],
		]);
	}

	public function renderGridTitle()
	{
		return Html::tag('h2', $this->title, ['class' => 'pt-2']);
	}

	public function renderExtraSearch()
	{
		return $this->extraSearch;
	}

	public function renderCreateButton()
	{
		return $this->createButton ?? Html::a('<div class="fa fa-plus"></div>', ['create'], ['class' => 'btn btn-sm btn-light mr-4', 'data-pjax' => 0]);
	}
}
