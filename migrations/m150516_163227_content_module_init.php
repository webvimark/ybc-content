<?php

use yii\db\Migration;

class m150516_163227_content_module_init extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ( $this->db->driverName === 'mysql' )
		{
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%content_menu}}', array(
			'id'             => 'pk',
			'active'         => 'smallint(1) not null default 1',
			'has_submenu'    => 'smallint(1) not null',
			'has_menu_image' => 'smallint(1) not null default 0',
			'name'           => 'string not null',
			'code'           => 'string',
			'position'       => 'string',
			'created_at'     => 'int not null',
			'updated_at'     => 'int not null',
		), $tableOptions);


		$this->createTable('{{%content_template}}', array(
			'id'             => 'pk',
			'active'         => 'smallint(1) not null default 1',
			'can_be_deleted' => 'smallint(1) not null default 1',
			'name'           => 'string not null',
			'layout'         => 'string not null',
			'created_at'     => 'int not null',
			'updated_at'     => 'int not null',
		), $tableOptions);


		$this->createTable('{{%content_page}}', array(
			'id'                  => 'pk',
			'active'              => 'smallint(1) not null default 1',
			'sorter'              => 'int not null',
			'is_main'             => 'smallint(1) not null default 0',
			'open_in_new_tab'     => 'smallint(1) not null default 0',
			'type'                => 'int not null',
			'name'                => 'string not null',
			'slug'                => 'string',
			'menu_image'          => 'string',
			'meta_title'          => 'string',
			'meta_keywords'       => 'string',
			'meta_description'    => 'string',
			'body'                => 'mediumtext',
			'parent_id'           => 'int',
			'content_template_id' => 'int',
			'content_menu_id'     => 'int',
			'created_at'          => 'int not null',
			'updated_at'          => 'int not null',
			0                     => 'FOREIGN KEY (parent_id ) REFERENCES {{%content_page}} (id) ON DELETE SET NULL ON UPDATE CASCADE',
			1                     => 'FOREIGN KEY (content_template_id ) REFERENCES {{%content_template}} (id) ON DELETE SET NULL ON UPDATE CASCADE',
			2                     => 'FOREIGN KEY (content_menu_id ) REFERENCES {{%content_menu}} (id) ON DELETE SET NULL ON UPDATE CASCADE',
		), $tableOptions);


		$this->createTable('{{%content_template_widget}}', array(
			'id'               => 'pk',
			'active'           => 'smallint(1) not null default 1',
			'single_per_page'  => 'smallint(1) not null',
			'name'             => 'string not null',
			'position'         => 'string',
			'widget_class'     => 'string not null',
			'widget_options'   => 'string',
			'code'             => 'string not null unique',
			'has_settings'     => 'smallint(1) not null',
			'link_to_settings' => 'string',
			'created_at'       => 'int not null',
			'updated_at'       => 'int not null',
		), $tableOptions);


		$this->createTable('{{%content_template_has_widget}}', array(
			'id'                         => 'pk',
			'content_template_id'        => 'int',
			'content_template_widget_id' => 'int',
			'position'                   => 'string',
			'sorter'                     => 'int not null',
			'created_at'                 => 'int not null',
			'updated_at'                 => 'int not null',
			1                            => 'FOREIGN KEY (content_template_id ) REFERENCES {{%content_template}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
			2                            => 'FOREIGN KEY (content_template_widget_id ) REFERENCES {{%content_template_widget}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
		), $tableOptions);


	}

	public function safeDown()
	{
		$this->dropTable('{{%content_template_has_widget}}');
		$this->dropTable('{{%content_template_widget}}');
		$this->dropTable('{{%content_page}}');
		$this->dropTable('{{%content_template}}');
		$this->dropTable('{{%content_menu}}');

	}
}
