<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiCaseCobraPlanModel extends Model
{
    public $timestamps = false;

    protected $table = 'api_case_plans_cobra';

    protected $primaryKey = 'case_plan_id';

    public $incrementing = false;

    /**
     * The custom attributes that will be appended to array.
     *
     * @var array
     */
    protected $appends = [
        'page_title',
        'plan_description',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_name',
        'plan_sub_type',
        'plan_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'case_plan_id' => 'string'
    ];

    public function getPageTitleAttribute()
    {
        if ($this->plan_sub_type)
            return $this->plan_sub_type;
        else
            return $this->plan_type;
    }

    public function getPlanDescriptionAttribute()
    {
        $plan_desc = '';

        if ($this->plan_sub_type != $this->plan_name)
            $plan_desc .= $this->plan_sub_type.' - '.$this->plan_name;
        else
            $plan_desc .= $this->plan_sub_type;

        return trim($plan_desc, '-');
    }
}
