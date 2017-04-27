<?php

namespace App\Http\Middleware;

use App\library\service;
// use App\library\string;
use Closure;
use Route;

class CheckForDataOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(empty($request->id)) {
        return redirect('home');
      }

      $pages = array(
        'item.edit' => array(
          // 'action' => 'edit',
          'modelName' => 'Item'
        ),
        'item.delete' => array(
          'modelName' => 'Item'
        ),
        'real_estate.edit' => array(
          'modelName' => 'RealEstate'
        ),
        'real_estate.delete' => array(
          'modelName' => 'RealEstate'
        ),
        'freelance.edit' => array(
          'modelName' => 'Freelance'
        ),
        'freelance.delete' => array(
          'modelName' => 'Freelance'
        ),
        'private_website.edit' => array(
          'modelName' => 'PersonPrivateWebsite'
        ),
        'private_website.delete' => array(
          'modelName' => 'PersonPrivateWebsite'
        ),
        'person_experience.working.edit' => array(
          'modelName' => 'PersonWorkingExperience'
        ),
        'person_experience.internship.edit' => array(
          'modelName' => 'PersonInternship'
        ),
        'person_experience.education.edit' => array(
          'modelName' => 'PersonEducation'
        ),
        'person_experience.project.edit' => array(
          'modelName' => 'PersonProject'
        ),
        'person_experience.certificate.edit' => array(
          'modelName' => 'PersonCertificate'
        ),
        'person_experience.skill.edit' => array(
          'modelName' => 'PersonSkill'
        ),
        'person_experience.language_skill.edit' => array(
          'modelName' => 'PersonLanguageSkill'
        ),
        'person_experience.working.delete' => array(
          'modelName' => 'PersonWorkingExperience'
        ),
        'person_experience.internship.delete' => array(
          'modelName' => 'PersonInternship'
        ),
        'person_experience.education.delete' => array(
          'modelName' => 'PersonEducation'
        ),
        'person_experience.project.delete' => array(
          'modelName' => 'PersonProject'
        ),
        'person_experience.certificate.delete' => array(
          'modelName' => 'PersonCertificate'
        ),
        'person_experience.skill.delete' => array(
          'modelName' => 'PersonSkill'
        ),
        'person_experience.language_skill.delete' => array(
          'modelName' => 'PersonLanguageSkill'
        ),
      );

      $name = Route::currentRouteName();
    
      if(empty($name) || empty($pages[$name])) {
        return $next($request);
      }

      $model = Service::loadModel($pages[$name]['modelName'])->select(array('id','person_id'))->find($request->id);

      if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
        return response(view('errors.error',array(
          'error'=>array(
            'message'=>'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้หรือข้อมูลนี้อาจถูกลบแล้ว'
          ))
        ));
      }

      return $next($request);
    }
}
