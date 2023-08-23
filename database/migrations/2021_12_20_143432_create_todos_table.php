<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->integer('idx');
        });

        Schema::create('todos_categories', function (Blueprint  $table){
            $table->id();
            $table->timestamps();
            $table->string('category_title');
            $table->string('category_idx');
        });

        Schema::create('todos_products', function (Blueprint  $table){
            $table->id();
            $table->timestamps();
            $table->string('product_title');
            $table->integer('product_idx');
            $table->unsignedBigInteger('todo_id');
            $table->unsignedBigInteger('todo_category_id');
            $table
                ->foreign('todo_id','todo_product_x_id')
                ->references('id')
                ->on('todos');
        });

        Schema::create('todos_items', function (Blueprint  $table){
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('product_id');
            $table->integer('item_idx');
            $table->string('item_title');
            $table->mediumText('description');
            $table->integer('max_score');
            $table->string('item_type'); // aya, hadith, zekr
            $table
                ->foreign('product_id')
                ->references('id')
                ->on('todos_products')
                ->onDelete('cascade');
        });

        Schema::create('todos_users_scores', function (Blueprint  $table){
            $table->id();
            $table->timestamps();
            $table->integer('score');
            $table->unsignedBigInteger('user_id');
            $table->mediumText('note');
            $table->boolean('checked');
            $table->unsignedBigInteger('todo_item_id');
            $table
                ->foreign('todo_item_id')
                ->references('id')
                ->on('todos_items');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');

        });

        //default data
        //use upsert() instead of insert() to automatically add timestamps() values

        \App\Models\Todo\Todo::upsert(
            [
                'idx'=>1,
                'title'=>'المستوى الأول',
            ],
            ['idx','title']
        );

        \App\Models\Todo\TodoCategory::upsert([
            ['category_idx'=>1,'category_title'=>'الصلاة'],
            ['category_idx'=>2,'category_title'=>'القرآن'],
            ['category_idx'=>3,'category_title'=>'الذكر'],
            ['category_idx'=>4,'category_title'=>'أعمال أخرى']
        ],['category_idx','category_title']);

        \App\Models\Todo\TodoProduct::upsert([
            ['product_idx'=>1,'product_title'=>'الفجر','todo_id'=>1,'todo_category_id'=>1],
            ['product_idx'=>2,'product_title'=>'الظهر','todo_id'=>1,'todo_category_id'=>1],
            ['product_idx'=>3,'product_title'=>'العصر','todo_id'=>1,'todo_category_id'=>1],
            ['product_idx'=>4,'product_title'=>'المغرب','todo_id'=>1,'todo_category_id'=>1],
            ['product_idx'=>5,'product_title'=>'العشاء','todo_id'=>1,'todo_category_id'=>1],
            ['product_idx'=>6,'product_title'=>'أوراد قرآنية','todo_id'=>1,'todo_category_id'=>2],
            ['product_idx'=>7,'product_title'=>'أذكار يومية','todo_id'=>1,'todo_category_id'=>3],
            ['product_idx'=>8,'product_title'=>'أذكار الصباح والمساء','todo_id'=>1,'todo_category_id'=>3],
            ['product_idx'=>9,'product_title'=>'أعمال هامة متفرقة','todo_id'=>1,'todo_category_id'=>4],
        ],['product_idx','product_title','todo_id','todo_category_id']);

        \App\Models\Todo\TodoItem::upsert([
            [
                'product_id'=>1,
                'item_idx'=>1,
                'item_title'=>'تكبيرة الإحرام',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>1,
                'item_idx'=>2,
                'item_title'=>'صلاة الجماعة',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>1,
                'item_idx'=>3,
                'item_title'=>'السنة القبلية - ٢ ركعة',
                'description'=>'',
                'max_score'=>2,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>1,
                'item_idx'=>4,
                'item_title'=>'حضور القلب',
                'description'=>'',
                'max_score'=>100,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>2,
                'item_idx'=>1,
                'item_title'=>'تكبيرة الإحرام',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>2,
                'item_idx'=>2,
                'item_title'=>'صلاة الجماعة',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>2,
                'item_idx'=>3,
                'item_title'=>'السنة القبلية - ٢ ركعة',
                'description'=>'',
                'max_score'=>2,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>2,
                'item_idx'=>4,
                'item_title'=>'حضور القلب',
                'description'=>'',
                'max_score'=>100,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>3,
                'item_idx'=>1,
                'item_title'=>'تكبيرة الإحرام',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>3,
                'item_idx'=>2,
                'item_title'=>'صلاة الجماعة',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>3,
                'item_idx'=>3,
                'item_title'=>'السنة القبلية - ٢ ركعة',
                'description'=>'',
                'max_score'=>2,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>3,
                'item_idx'=>4,
                'item_title'=>'حضور القلب',
                'description'=>'',
                'max_score'=>100,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>4,
                'item_idx'=>1,
                'item_title'=>'تكبيرة الإحرام',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>4,
                'item_idx'=>2,
                'item_title'=>'صلاة الجماعة',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>4,
                'item_idx'=>3,
                'item_title'=>'السنة القبلية - ٢ ركعة',
                'description'=>'',
                'max_score'=>2,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>4,
                'item_idx'=>4,
                'item_title'=>'حضور القلب',
                'description'=>'',
                'max_score'=>100,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>5,
                'item_idx'=>1,
                'item_title'=>'تكبيرة الإحرام',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>5,
                'item_idx'=>2,
                'item_title'=>'صلاة الجماعة',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>5,
                'item_idx'=>3,
                'item_title'=>'السنة القبلية - ٢ ركعة',
                'description'=>'',
                'max_score'=>2,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>5,
                'item_idx'=>4,
                'item_title'=>'حضور القلب',
                'description'=>'',
                'max_score'=>100,
                'item_type'=>'int'
            ] ,
            //  quran
            [
                'product_id'=>6,
                'item_idx'=>1,
                'item_title'=>'قراءة جزء من القرآن',
                'description'=>'',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>6,
                'item_idx'=>2,
                'item_title'=>'تدبر آية',
                'description'=>'اكتب رقم آية مما تدبرت',
                'max_score'=>200,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>6,
                'item_idx'=>3,
                'item_title'=>'قيام الليل',
                'description'=>'قيام الليل ١١ ركعة',
                'max_score'=>11,
                'item_type'=>'int'
            ] ,
            //  azkar
            [
                'product_id'=>7,
                'item_idx'=>1,
                'item_title'=>'استغفار',
                'description'=>'استغر الله الذي لا إله إلا هو الحي القيوم وأتوب إليه',
                'max_score'=>100,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>7,
                'item_idx'=>2,
                'item_title'=>'تهليل',
                'description'=>'لا إله إلا الله',
                'max_score'=>200,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>7,
                'item_idx'=>3,
                'item_title'=>'الصلاة على النبي',
                'description'=>'اللهم صلِّ وسلم وبارك على سيدنا محمد وعلى آله وصحبه وسلم',
                'max_score'=>200,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>7,
                'item_idx'=>4,
                'item_title'=>'الباقيات الصالحات',
                'description'=>'سبحان الله وبحمده سبحان الله العظيم',
                'max_score'=>200,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>7,
                'item_idx'=>5,
                'item_title'=>'الباقيات الصالحات',
                'description'=>'سبحان الله وبحمده سبحان الله العظيم',
                'max_score'=>200,
                'item_type'=>'int'
            ] ,

            [
                'product_id'=>7,
                'item_idx'=>5,
                'item_title'=>'الحبيبتان',
                'description'=>'سبحان الله وبحمده سبحان الله العظيم',
                'max_score'=>200,
                'item_type'=>'int'
            ] ,
            [
                'product_id'=>8,
                'item_idx'=>1,
                'item_title'=>'أذكار الصباح',
                'description'=>'أصبحنا وأصبح الملك لله ولا إله إلا الله',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>8,
                'item_idx'=>2,
                'item_title'=>'أذكار المساء',
                'description'=>'أمسينا وأمسى الملك لله ولا إله إلا الله',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>1,
                'item_title'=>'خلوة حصينة',
                'description'=>'خلوة حصينة',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>2,
                'item_title'=>'التفكر والمناجاة',
                'description'=>'التفكر والمناجاة',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>3,
                'item_title'=>'الصدقة',
                'description'=>'هل تصدقت اليوم',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>4,
                'item_title'=>'الصيام',
                'description'=>'هل أنت صائم',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>5,
                'item_title'=>'التوبة',
                'description'=>'هل تبت اليوم ؟',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>6,
                'item_title'=>'الدعاء',
                'description'=>'هل دعوت الله عز وجل',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>7,
                'item_title'=>'صلة الأرحام',
                'description'=>'صلة الأرحام',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>8,
                'item_title'=>'بر الوالدين',
                'description'=>'بر الوالدين',
                'max_score'=>1,
                'item_type'=>'bool'
            ] ,
            [
                'product_id'=>9,
                'item_idx'=>9,
                'item_title'=>'حال قلبك',
                'description'=>'شوق ، خوف ، إنابة ، ...',
                'max_score'=>1,
                'item_type'=>'text'
            ] ,
        ],['product_id',
            'item_idx',
            'item_title',
            'description',
            'max_score',
            'item_type']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
