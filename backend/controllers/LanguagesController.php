<?php

namespace backend\controllers;

use common\models\Direction;
use common\models\Languages;
use common\models\LanguagesSearch;
use yii\base\NotSupportedException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * LanguagesController implements the CRUD actions for Languages model.
 */
class LanguagesController extends Controller
{
    use ActionTrait;

    public function actionIndex()
    {
        $directions = Direction::find()
            ->where([
                'is_deleted' => 0
            ])
            ->all();

        foreach ($directions as $direction) {
            $new = new Direction();
            $new->name_uz = $direction->name_uz;
            $new->name_ru = $direction->name_ru;
            $new->name_en = $direction->name_en;
            $new->code = $direction->code;
            $new->branch_id = 3;
            $new->save(false);
        }
        dd(21212);
        $searchModel = new LanguagesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Languages::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
