<?php
namespace App\Models;

// use App\Results\Library; // برای نتایج اختصاصی که در پوشه Results ذخیره شده اند
use TypeRocket\Models\Model;

class Sample extends Model
{
    protected $resource = 'samples';
    // جدول ساخته شده با Migration باید جمع حالت Model مفرد باشد
    // یعنی Model مفرد باشد
    // و Migration جمع باشد
    // چونکه خود Model داخل خود پراپرتی جمع همان مدل را دارد


    
    // protected $fillable = [ // برای مشخص کردن اینکه چه فیلدهایی می توانند پر شوند
    //     'title',
    //     'json',
    //     'version',
    //     'slug'
    // ];

    // protected $guard = [ // برای مشخص کردن اینکه چه فیلدهایی اصلا نباید بطور دستی پر شوند
    //                     //حتی اگر از اشاره مستقیم یا صریح استفاده شود
    //     'id',
    //     'created_at',
    //     'modified_at'
    // ];

    // protected $cast = [ // برای مشخص کردن نوع هر فیلدی که باید پر شود
    //     'id' => 'int', // cast to integer
    //     'json' => 'array', // cast a json string to an array
    // ];

    // protected $format = [ // برای اعتبارسنجی فیلدها قبل از پر شدن
    //     'slug' => 'sanitize_title',
    //     'version' => 'static::sanitizeVersion'
    // ];



    // protected $resultsClass = Library::class; // برای نتایج اختصاصی که در پوشه Results ذخیره شده اند
}


//////////////////////////////////////////////////////////////////
//$sample = new App\Models\Sample;  // برای نمونه سازی از Model

// انواع متد برای صدا زدن یک Model
// findById(), findAll(), find(), first(), findOrNew(), findOrCreate(), findOrDie(), findFirstWhereOrNew(), findFirstWhereOrDie()
// get(), getProperties(), where(), orWhere(), whereMeta(), take(), orderBy(), getDateTime()
// select(), create(), update(), save(), delete(), count(), getID(), getDependencyInjectionKey(), with(), load(), 
// published(), toArray(), toJson(), wpPost(), when()
// paginate(), getResults(), getCount(), getCurrentPage(), getNumberPerPage(), getNumberOfPages(), getNextPage(), getPreviousPage(), getLastPage(), getFirstPage(), getLinks()
// setArrayReplaceRecursiveKey(), setArrayReplaceRecursiveStops()

// $sample->findAll()->get();

// hasOne() && belongsTo(), hasMany() && belongsTo(), belongsToMany() ارتباطات یک به یک، یک به چند، چند به چند بین مدل ها
// attach(), detach(), sync() برای مشخص کردن فیلدهای مورد جستجو در رابطه چند به چند
// belongsToTaxonomy() برای رابطه های تاکسونومی