<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

use yii\db\Migration;

/**
 * Class m200305_060417_create_theme_table
 */
class m200305_060417_create_theme_table extends Migration
{
	
	public $table = '{{%theme}}';
	
	public function up()
	{
		$this->createTable($this->table, [
			'id'               => $this->primaryKey(),
			'user_id'          => $this->integer(),
			'sidebar_position' => $this->string()->notNull()->defaultValue('fixed'),
			'version'          => $this->string()->notNull()->defaultValue('dark'),
			'header_position'  => $this->string()->notNull()->defaultValue('fixed'),
			'sidebar_style'    => $this->string()->notNull()->defaultValue('full'),
			'layout'           => $this->string()->notNull()->defaultValue('vertical'),
			'container_layout' => $this->string()->notNull()->defaultValue('wide'),
			'navheader_bg'     => $this->string()->notNull()->defaultValue('color_1'),
			'header_bg'        => $this->string()->notNull()->defaultValue('color_1'),
			'sidebar_bg'       => $this->string()->notNull()->defaultValue('color_1'),
		]);
		$this->addForeignKey('fk-theme-user', $this->table, 'user_id', 'user', 'id', 'CASCADE');
		
		$this->insert($this->table, ['user_id' => 1]);
		$this->insert($this->table, ['user_id' => 2]);
		
	}
	
	public function down()
	{
		$this->dropTable($this->table);
	}
}
