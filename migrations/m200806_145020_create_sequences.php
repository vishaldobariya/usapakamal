<?php

use yii\db\Migration;

/**
 * Class m200806_145020_create_sequences
 */
class m200806_145020_create_sequences extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	return true;
    	//brand
	    $this->execute("CREATE SEQUENCE brand_id_seq MINVALUE 1000");
	    $this->execute("ALTER TABLE brand ALTER id SET DEFAULT nextval('brand_id_seq')");
	    $this->execute("ALTER SEQUENCE brand_id_seq OWNED BY brand.id");
	    
	    //category
	    $this->execute("CREATE SEQUENCE category_id_seq MINVALUE 1000");
	    $this->execute("ALTER TABLE category ALTER id SET DEFAULT nextval('category_id_seq')");
	    $this->execute("ALTER SEQUENCE category_id_seq OWNED BY category.id");
	    
	    //product
	    $this->execute("CREATE SEQUENCE product_id_seq MINVALUE 5000");
	    $this->execute("ALTER TABLE product ALTER id SET DEFAULT nextval('product_id_seq')");
	    $this->execute("ALTER SEQUENCE product_id_seq OWNED BY product.id");
	
	    //setting
	    $this->execute("CREATE SEQUENCE setting_id_seq MINVALUE 1000");
	    $this->execute("ALTER TABLE setting ALTER id SET DEFAULT nextval('setting_id_seq')");
	    $this->execute("ALTER SEQUENCE setting_id_seq OWNED BY setting.id");
	
	    
	
	    //storage
	    $this->execute("CREATE SEQUENCE storage_id_seq MINVALUE 5000");
	    $this->execute("ALTER TABLE storage ALTER id SET DEFAULT nextval('storage_id_seq')");
	    $this->execute("ALTER SEQUENCE storage_id_seq OWNED BY storage.id");
	
	    //user
	    $this->execute("CREATE SEQUENCE user_id_seq MINVALUE 1000");
	    $this->execute("ALTER TABLE public.user ALTER id SET DEFAULT nextval('user_id_seq')");
	    $this->execute("ALTER SEQUENCE user_id_seq OWNED BY public.user.id");
	    
	    //theme
	    $this->execute("CREATE SEQUENCE theme_id_seq MINVALUE 5000");
	    $this->execute("ALTER TABLE theme ALTER id SET DEFAULT nextval('theme_id_seq')");
	    $this->execute("ALTER SEQUENCE theme_id_seq OWNED BY theme.id");
	    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200806_145020_create_sequences cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200806_145020_create_sequences cannot be reverted.\n";

        return false;
    }
    */
}
