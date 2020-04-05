<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'cities';

    public function country_relation()
    {
        return $this->belongsTo('App\Model\Country', 'country_id');
      
    }
    public function get_city(){
    	return $this->hasMany('App\Model\Country', 'country_id');
    }
	public function feature_utility_data(){
    	return $this->hasMany('App\Model\FeatureUtility', 'from_city_id');
    }
	public function feature_hotel_list(){
    	return $this->hasMany('App\Model\Hotel', 'city');
    }
    public function feature_hotel_price(){
    	return $this->hasMany('App\Model\HotelPrice', 'from');
    }
	public function utility_cureted_feature(){
    	return $this->hasMany('App\Model\UtilityCuretedFeature', 'city_id');
    }

}
