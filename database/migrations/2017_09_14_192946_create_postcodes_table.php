<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postcodes', function (Blueprint $table)
        {
            $table->increments('id');

            //Unit postcode – 7 character version
            $table->string('pcd', 7)->unique('idx_postcodes_postcode');

            //Unit postcode – 8 character version
            $table->string('pcd2', 8)->unique('idx_postcodes_postcode2');;

            //Unit postcode - variable length (e-Gif) version
            $table->string('pcds', 8);

            //Date of introduction
            $table->date('dointr');

            //Date of termination
            $table->date('doterm')->nullable();

            //County
            $table->string('oscty', 9);

            //Local authority district
            $table->string('oslaua', 9);

            //(Electoral) ward/division
            $table->string('osward', 9);

            //Postcode user type - 0 = small user, 1 = large user. Could be defined as boolean but leave it as integer just in case
            $table->tinyInteger('usertype');

            //National grid reference - Easting
            $table->string('oseast1m', 6)->nullable();

            //National grid reference -  Northing
            $table->string('osnrth1m', 7)->nullable();

            //Grid reference positional quality indicator
            $table->tinyInteger('osgrdind');

            //Former Strategic Health Authority
            $table->string('oshlthau', 9)->nullable();

            //Pan SHA
            $table->string('hro', 9);

            //Country
            $table->string('ctry', 9);

            //Region (former GOR)
            $table->string('gor', 9)->nullable();

            //Standard (Statistical) Region (SSR)
            $table->tinyInteger('streg')->nullable();

            //Westminster parliamentary constituency
            $table->string('pcon', 9)->nullable();

            //European Electoral Region (EER)
            $table->string('eer', 9)->nullable();

            //Local Learning  and Skills Council
            $table->string('teclec', 9)->nullable();

            //Travel to Work Area (TTWA)
            $table->string('ttwa', 9)->nullable();

            //Primary Care Trust
            $table->string('pct', 9)->nullable();

            //LAU2 areas
            $table->string('nuts', 10)->nullable();

            //1991 Census Enumeration District (ED)
            $table->string('psed', 8)->nullable();

            //1991 Census Enumeration District (ED)
            $table->string('cened', 8)->nullable();

            //ED positional quality indicator
            $table->tinyInteger('edind');

            //Previous Strategic Health Authority
            $table->string('oshaprev', 3)->nullable();

            //Local Education Authority
            $table->string('lea', 3)->nullable();

            //Health Authority ‘old-style’
            $table->string('oldha', 3)->nullable();

            //1991 ward (Census code range)
            $table->string('wardc91', 6)->nullable();

            //1991 ward (OGSS code range)
            $table->string('wardo91', 6)->nullable();

            //1998 ward
            $table->string('ward98', 6)->nullable();

            //2005 ‘statistical’ ward
            $table->string('statsward', 6)->nullable();

            //2001 Census Output Area (OA)
            $table->string('oa01', 10)->nullable();

            //Census Area Statistics (CAS) ward
            $table->string('casward', 6)->nullable();

            //National park
            $table->string('park', 9)->nullable();

            //2001 Census Lower Layer Super Output Area
            $table->string('lsoa01', 9)->nullable();

            //2001 Census Middle Layer Super Output Area
            $table->string('msoa01', 9)->nullable();

            //2001 Census urban/rural indicator
            $table->string('ur01ind', 1)->default('');

            //2001 Census Output Area classification
            $table->string('oac01', 3)->nullable();

            //‘Old’ Primary Care Trust
            $table->string('oldpct', 5)->nullable();

            //2011 Census Output Area
            $table->string('oa11', 9)->nullable();

            //2011 Census Lower Layer Super Output Area
            $table->string('lsoa11', 9)->nullable();

            //2011 Census Middle Layer Super Output Area
            $table->string('msoa11', 9)->nullable();

            //Parish/community
            $table->string('parish', 9)->nullable();

            //2011 Census Workplace Zone
            $table->string('wz11', 9)->nullable();

            //Clinical Commissioning Group
            $table->string('ccg', 11)->nullable();

            //Built-up Area
            $table->string('bua11', 9)->nullable();

            //Built-up Area Sub-division
            $table->string('buasd11', 9)->nullable();

            //2011 Census rural-urban classification
            $table->string('ru11ind', 2)->nullable();

            //2011 Census Output Area classification
            $table->string('oac11', 3)->nullable();

            //Decimal degrees latitude
            $table->point('lat');

            //Decimal degrees longitude
            $table->point('long');

            //Local Enterprise Partnership (LEP) - first instance
            $table->string('lep1', 9)->nullable();

            //Local Enterprise Partnership (LEP) - second instance
            $table->string('lep2', 9)->nullable();

            //Police Force Area
            $table->string('pfa', 9)->nullable();

            //Index of Multiple Deprivation
            $table->integer('imd')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postcodes');
    }
}
