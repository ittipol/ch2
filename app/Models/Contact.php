<?php

namespace App\Models;

class Contact extends Model
{
  public $table = 'contacts';
  protected $fillable = ['model','model_id','phone_number','fax','email','website','line','person_id'];
  public $timestamps  = false;

  public $formHelper = true;

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      if(!empty($attributes['phone_number'])) {

        $phoneNumber = array();
        foreach ($attributes['phone_number'] as $value) {
          if(empty($value['value'])) {
            continue;
          }
          $phoneNumber[] = $value['value'];
        }

        $attributes['phone_number'] = null;
        if(!empty($phoneNumber)) {
          $attributes['phone_number'] = json_encode($phoneNumber);        
        }
 
      }

      if(!empty($attributes['fax'])) {
        
        $fax = array();
        foreach ($attributes['fax'] as $value) {
          if(empty($value['value'])) {
            continue;
          }
          $fax[] = $value['value'];
        }

        $attributes['fax'] = null;
        if(!empty($fax)) {
          $attributes['fax'] = json_encode($fax);        
        }

      }

      if(!empty($attributes['email'])) {

        $email = array();
        foreach ($attributes['email'] as $value) {
          if(empty($value['value'])) {
            continue;
          }
          $email[] = $value['value'];
        }

        $attributes['email'] = null;
        if(!empty($email)) {
          $attributes['email'] = json_encode($email);        
        }

      }

      if(!empty($attributes['website'])) {
        
        $website = array();
        foreach ($attributes['website'] as $value) {
          if(empty($value['value'])) {
            continue;
          }
          $website[] = $value['value'];
        }

        $attributes['website'] = null;
        if(!empty($website)) {
          $attributes['website'] = json_encode($website);        
        }

      }

      if(!empty($attributes['line'])) {
        
        $line = array();
        foreach ($attributes['line'] as $value) {
          if(empty($value['value'])) {
            continue;
          }
          $line[] = $value['value'];
        }

        $attributes['line'] = null;
        if(!empty($line)) {
          $attributes['line'] = json_encode($line);        
        }

      }

    }

    return parent::fill($attributes);

  }

  public function __saveRelatedData($model,$options = array()) {

    $contact = $model->getModelRelationData($this->modelName,
      array(
        'first' => true
      )
    );

    if(($model->state == 'update') && !empty($contact)){
      return $contact
      ->fill($options['value'])
      ->save();
    }else{
      return $this->fill($model->includeModelAndModelId($options['value']))->save();
    }
    
  }

  public function buildModelData() {

    $phoneNumber = '';
    $fax = '';
    $email = '';
    $website = '';
    $line = '';

    if(!empty($this->phone_number)) {
      $phoneNumber = implode(', ',json_decode($this->phone_number,true));        
    }

    if(!empty($this->fax)) {
      $fax = implode(', ',json_decode($this->fax,true));        
    }

    if(!empty($this->email)) {
      $email = implode(', ',json_decode($this->email,true));        
    }

    if(!empty($this->website)) {
      $website = implode(', ',json_decode($this->website,true));        
    }

    if(!empty($this->line)) {
      $line = implode(', ',json_decode($this->line,true));        
    }

    return array(
      'phone_number' => $phoneNumber,
      'fax' => $fax,
      'email' => $email,
      'website' => $website,
      'line' => $line
    );
  }
  
}
