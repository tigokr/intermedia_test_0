<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use app\models\ContactForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => 'testme',
            ],
        ];
    }

    public function actionIndex()
    {
        $form = new ContactForm();

        if (Yii::$app->request->isAjax && $form->load(\Yii::$app->request->post()))
        {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($form);
        }

        if($form->load(\Yii::$app->request->post())){
            $form->file = UploadedFile::getInstance($form, 'file');

            if($form->validate()) {
                VarDumper::dump($form, 2, 1);
                die;
            }
        }

        return $this->render('index', [
            'model'=>$form
        ]);
    }

    public function actionStatus()
    {
        return $this->render('about');
    }

    public function actionCities() {
        $parents = \Yii::$app->request->post('depdrop_parents', null);
        if (!empty($parents) && isset($parents[0]) && !empty($parents[0])) {
            $region_id = $parents[0];
            $tmp = \app\models\Region::findOne($region_id)->cities;
            $out = [];
            foreach ($tmp as $model) {
                $out[] = ['id'=>$model->id, 'name'=>$model->title];
            }

            echo Json::encode(['output'=>$out, 'selected'=>'']);
            return;
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
}
