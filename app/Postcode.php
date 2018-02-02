<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Postcode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
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

    const EARTH_KM = 6371;

    public static function boot()
    {
        $hashField = 'hash';

        //On create and update recalculate the geo-spatial fields
        static::creating(function ($model) use ($hashField) {
            $model->calculateSpatialFields();
        });
        static::updating(function ($model) {
            $model->calculateSpatialFields();
        });

        parent::boot();
    }

    /**
     * Calculate spatial fields used in sqlite to replace the MySQL geo-spatial functions
     */
    public function calculateSpatialFields()
    {
        $this->cos_lat  = cos(pi() * $this->lat  / 180);
        $this->cos_long = cos(pi() * $this->long  / 180);
        $this->sin_lat  = sin(pi() * $this->lat  / 180);
        $this->sin_long = sin(pi() * $this->long / 180);
    }

    /**
     * Search by partial postcode
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $pcd
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWherePostcode(\Illuminate\Database\Eloquent\Builder $query, string $pcd)
    {
        $searchPCD = trim($pcd);
        return $query->where('pcd', 'LIKE', "%{$searchPCD}%")->orWhereRaw("replace(pcd, ' ', '')  LIKE '%{$searchPCD}%'");
    }

    /**
     * Search by LAT and LONG within a maximum distance
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $lat
     * @param $long
     * @param int $distance
     * @return $this
     */
    public function scopeWhereClosest(\Illuminate\Database\Eloquent\Builder $query, $lat, $long, $distance = 20)
    {
        $cosLat  = cos($lat * pi() / 180);
        $sinLat  = sin($lat * pi() / 180) ;
        $cosLong = cos($long * pi() / 180);
        $sinLong = sin($long * pi() / 180);
        $cosDistance = cos($distance / static::EARTH_KM);

        return $query->select([
            '*',
            DB::raw("$sinLat * sin_lat + $cosLat * cos_lat * (cos_long * $cosLong + sin_long * $sinLong) AS distance")
        ])->whereRaw("$sinLat * sin_lat + $cosLat * cos_lat * (cos_long * $cosLong + sin_long * $sinLong) > $cosDistance")->orderBy('distance', 'desc');
    }

    /**
     * Search by LAT and LONG within a maximum distance using MySQL DISTANCE function
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $lat
     * @param $long
     * @param int $distance
     * @return mixed
     */
    public function scopeWhereClosestDistance(\Illuminate\Database\Eloquent\Builder $query, $lat, $long, $distance = 20)
    {
        return $query->select([
            '*',
            DB::raw("DISTANCE(lat, long, $lat, $long) AS distance")
        ])->where("distance <= $distance")->orderBy('distance');
    }
}
