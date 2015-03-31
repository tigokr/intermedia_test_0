<?php

namespace app\controllers;

use app\models\Email;
use app\models\EmailSearch;
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

            if($form->validate() && $form->contact($form->recipient)) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Email successfully sended!'));
                $this->redirect(['site/index']);
            }
        }

        return $this->render('index', [
            'model'=>$form
        ]);
    }

    public function actionReaded($id){
        $email = Email::findOne(['id'=>$id]);
        if(!empty($email)) {
            $email->received = 1;
            $email->update();
        }
        header('Content-Type: image/jpeg');
        echo file_get_contents(\Yii::getAlias('@webroot').'/1.gif');
    }

    public function actionStatus()
    {
        $searchModel = new EmailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('status', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
