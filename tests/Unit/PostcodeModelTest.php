<?php

namespace Tests\Unit;

use App\Postcode;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostcodeModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a Postcode
     *
     * @test
     */
    public function create()
    {
        //ToDo - Ideally this would be based on a factory
        $postcodeData = [
            'pcd'       => "AB1 0AA",
            'pcd2'      => "AB1  0AA",
            'pcds'      => "AB1 0AA",
            'dointr'    => "198001",
            'doterm'    => "199606",
            'oscty'     => "S99999999",
            'oslaua'    => "S12000033",
            'osward'    => "S13002843",
            'usertype'  => "0",
            'oseast1m'  => "385386",
            'osnrth1m'  => "0801193",
            'osgrdind'  => "1",
            'oshlthau'  => "S08000020",
            'hro'       => "S99999999",
            'ctry'      => "S92000003",
            'gor'       => "S92000003",
            'streg'     => "0",
            'pcon'      => "S14000002",
            'eer'       => "S15000001",
            'teclec'    => "S09000001",
            'ttwa'      => "S22000047",
            'pct'       => "S03000012",
            'nuts'      => "S31000935",
            'psed'      => "99ZZ0099",
            'cened'     => "ZZ0099",
            'edind'     => "9",
            'oshaprev'  => "SN9",
            'lea'       => "QA",
            'oldha'     => "SN9",
            'wardc91'   => "72UB43",
            'wardo91'   => "72UB43",
            'ward98'    => "00QA36",
            'statsward' => "99ZZ00",
            'oa01'      => "S00001364",
            'casward'   => "01C30",
            'park'      => "S99999999",
            'lsoa01'    => "S01000011",
            'msoa01'    => "S02000007",
            'ur01ind'   => "6",
            'oac01'     => "3C2",
            'oldpct'    => "X98",
            'oa11'      => "S00090303",
            'lsoa11'    => "S01006514",
            'msoa11'    => "S02001237",
            'parish'    => "S99999999",
            'wz11'      => "S34002990",
            'ccg'       => "S03000012",
            'bua11'     => "S99999999",
            'buasd11'   => "S99999999",
            'ru11ind'   => "3",
            'oac11'     => "1C3",
            'lat'       => 57.101474,
            'long'      => -2.242851,
            'lep1'      => "S99999999",
            'lep2'      => "S99999999",
            'pfa'       => "S23000009",
            'imd'       => 6808
        ];

        $postcode = Postcode::create($postcodeData);

        $this->assertInstanceOf(Postcode::class, $postcode);

        $this->assertEquals(sha1(json_encode($postcodeData, true)), $postcode->hash);
    }
}
