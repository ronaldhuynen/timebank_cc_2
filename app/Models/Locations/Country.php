<?php

namespace App\Models\Locations;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Country extends Model
{
    use HasRelationships;

    /**
     * The database table doesn't use 'created_at' and 'updated_at' so we disable it from Inserts/Updates.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'location_countries';


    /**
     * Return all available locales.
     *
     * @return void
     */
    public function locales()
    {
        return $this->hasMany(CountryLocale::class, 'country_id');
    }


    /**
     * Get the country name.
     * In the App::getLocale, or if not exists, in the App::getFallbackLocale language.
     * @return void
     */
    public function nameLocale()
    {
        // Groot leermoment: relaties altijd zonder ->get() ->first() etc zodat deze geschakeld kunnen worden!
        // Groot leermoment: gebruik bij CounutryLocale een hasOne relatie ondanks dat er hasMany vertalingen zijn.
        // Want zo kun je binair kiezen tussen App::getLocale en indien niet aanwezig de App::getFallbackLocal !!
        // Sorten op 'name' wordt als laatste gedaan op de collectie in de blade view !
        return $this->hasOne(CountryLocale::class, 'country_id')
        ->where('locale', App::getLocale())
        ->orWhere('locale', App::getFallbackLocale());
    }


    /**
     * Get all the users of the countries.
     * Many-to-many polymorphic.
     * @return void
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'countryable', 'location_countryables');
    }


    /**
     * Get all the related divisions of the country.
     * @return void
     */
    public function divisionsRelation()
    {
        return $this->hasManyThrough(DivisionLocale::class, Division::class, 'country_id', 'division_id');
    }


    /**
     * Get the divisions of the country in the App::getLocale, or if not exists, in the App::getFallbackLocale language.
     * The optional paramameter will filter the localized division names.
     * @param string $search
     * @return void
     */
    public function divisions(string $search = '')
    {
        $locale = collect(
            $this->hasManyThrough(DivisionLocale::class, Division::class, 'country_id', 'division_id')
                ->where('locale', App::getLocale())
                ->get()
        )->keyBy('division_id');

        $fallback = collect(
            $this->hasManyThrough(DivisionLocale::class, Division::class, 'country_id', 'division_id')
                ->where('locale', App::getFallbackLocale())
                ->get()
        )->keyBy('division_id');

        $result = $locale
            ->union($fallback)
            ->filter(function ($item) use ($search) {
                return false !== stripos($item->name, $search);
            })
            ->sortBy('name');

        return $result;

    }


    /**
    * Get all the related cities of the country.
    * @return void
    */
    public function citiesRelation()
    {
        return $this->hasManyThrough(CityLocale::class, City::class, 'country_id');
    }


    /**
     * Get the related cities of the country in the App::getLocale, or if not exists, in the App::getFallbackLocale language.
     * The optional paramameter will filter the localized city names.
     * @return void
     */
    public function cities(string $search = '')
    {
        $locale = collect(
            $this->hasManyThrough(CityLocale::class, City::class, 'country_id')
                ->where('locale', App::getLocale())
                ->get()
        )->keyBy('city_id');

        $fallback = collect(
            $this->hasManyThrough(CityLocale::class, City::class, 'country_id')
                ->where('locale', App::getFallbackLocale())
                ->get()
        )->keyBy('city_id');

        $result = $locale
            ->union($fallback)
            ->filter(function ($item) use ($search) {
                return false !== stripos($item->name, $search);
            })
            ->sortBy('name');

        return $result;

    }



    /**
    * Get all the related districts of the country.
    *
    * @return void
    */
    public function districtsRelation()
    {
        return $this->hasManyDeep(DistrictLocale::class, [City::class, District::class], ['country_id', 'city_id', 'district_id'], ['id', 'id', 'id']);
    }


    /**
     * Get all the districts of the countries.
     * Only fallback locale is used when App language is not a country language of the city to prevent double results.
     *
     * @return void
     */
    public function districts(string $search = '')
    {
        $locale = collect(
            $this->hasManyDeep(DistrictLocale::class, [City::class, District::class], ['country_id', 'city_id', 'district_id'], ['id', 'id', 'id'])
                ->where('locale', App::getLocale())
                ->get()
        )->keyBy('district_id');

        $fallback = collect(
            $this->hasManyDeep(DistrictLocale::class, [City::class, District::class], ['country_id', 'city_id', 'district_id'], ['id', 'id', 'id'])
                ->where('locale', App::getFallbackLocale())
                ->get()
        )->keyBy('district_id');

        $result = $locale
            ->union($fallback)
            ->filter(function ($item) use ($search) {
                return false !== stripos($item->name, $search);
            })
            ->sortBy('name');

        return $result;
    }


     /**
     * Get the related children of this model.
     *
     * @return collection
     */
    public function children(string $search = '')
    {
        if ($this->divisions() == true) {
            return $this->divisions($search);
        }
        return $this->cities($search);
    }

}
