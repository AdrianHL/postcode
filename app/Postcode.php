<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{

    protected $table = 'postcodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pcd',
        'pcd2','pcds','dointr','doterm','oscty','oslaua','osward','usertype','oseast1m','osnrth1m','osgrdind',
        'oshlthau','hro','ctry','gor','streg','pcon','eer','teclec','ttwa','pct','nuts','psed','cened','edind','oshaprev',
        'lea','oldha','wardc91','wardo91','ward98','statsward','oa01','casward','park','lsoa01','msoa01','ur01ind',
        'oac01','oldpct','oa11','lsoa11','msoa11','parish','wz11','ccg','bua11','buasd11','ru11ind','oac11',
        'lat','long',
        'lep1','lep2','pfa','imd'
    ];

    /**
     * Same fields than fillable (but kept separate to avoid updates)
     *
     * @var array
     */
    protected $hashFields = [
        'pcd',
        'pcd2','pcds','dointr','doterm','oscty','oslaua','osward','usertype','oseast1m','osnrth1m','osgrdind',
        'oshlthau','hro','ctry','gor','streg','pcon','eer','teclec','ttwa','pct','nuts','psed','cened','edind','oshaprev',
        'lea','oldha','wardc91','wardo91','ward98','statsward','oa01','casward','park','lsoa01','msoa01','ur01ind',
        'oac01','oldpct','oa11','lsoa11','msoa11','parish','wz11','ccg','bua11','buasd11','ru11ind','oac11',
        'lat','long',
        'lep1','lep2','pfa','imd'
    ];

    /**
     * The hashing algorithm (function)
     *
     * @var string
     */
    protected $hashAlgo = 'sha1';


    public static function boot()
    {
        $hashField = 'hash';

        static::creating(function ($model) use ($hashField) {
            $model->hash = $model->toHash();
        });

        static::updating(function ($model) {
            $model->hash = $model->toHash();
        });

        parent::boot();
    }

    public function toHash()
    {
        $data = [];

        foreach ($this->hashFields as $fieldToHash) {
            $data[$fieldToHash] = $this->$fieldToHash;
        }

        return ($this->hashAlgo)(json_encode($data, true));
    }

    public function scopeWherePostcode($query, $pcd)
    {
        return $query->where('pcd', 'LIKE', "%{$pcd}%");
    }

}
