<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FunctionSlugifyCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = <<<EOT
CREATE FUNCTION slugify(`str` varchar(460) )
RETURNS varchar(460) CHARSET utf8
    READS SQL DATA
BEGIN
    DECLARE url_str varchar(460) CHARACTER SET utf8;
    SET url_str = replace(str,'<', '');
    SET url_str = replace(url_str,'>', '');
    SET url_str = replace(url_str,'#', '');
    SET url_str = replace(url_str,'%', '');
    SET url_str = replace(url_str,'{', '(');
    SET url_str = replace(url_str,'}', ')');
    SET url_str = replace(url_str,'|', '-');
    SET url_str = replace(url_str,'[', '(');
    SET url_str = replace(url_str,']', ')');
    SET url_str = replace(url_str,'`', '');
    SET url_str = replace(url_str,';', '');
    SET url_str = replace(url_str,'?', '');
    SET url_str = replace(url_str,':', '');
    SET url_str = replace(url_str,'@', '');
    SET url_str = replace(url_str,'&', '-and-');
    SET url_str = replace(url_str,'.', '');
    SET url_str = replace(url_str,'=', '');
    SET url_str = replace(url_str,',', '-');
    SET url_str = replace(url_str,'+', '');
    SET url_str = replace(url_str,'$', 'USD');
    SET url_str = replace(url_str,'£', 'GBP');
    SET url_str = replace(url_str,'?', 'EUR');
    SET url_str = replace(url_str,' ', '-');
    SET url_str = replace(url_str,'--', '-');
    SET url_str = replace(url_str,'^', '');
    SET url_str = replace(url_str,'\\\', '-');
    SET url_str = replace(url_str,'/', '-');
    RETURN lcase(url_str);
END
EOT;
        
        DB::unprepared($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
