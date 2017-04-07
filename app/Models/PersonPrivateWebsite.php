<?php

namespace App\Models;

class PersonPrivateWebsite extends Model
{
  protected $table = 'person_private_websites';
  protected $fillable = ['person_experience_id','person_id','website_type_id','website_url'];

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public function websiteType() {
    return $this->hasOne('App\Models\WebsiteType','id','website_type_id');
  }

}