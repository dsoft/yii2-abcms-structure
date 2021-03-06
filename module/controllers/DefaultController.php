<?php

namespace abcms\structure\module\controllers;

use Yii;
use abcms\structure\models\Structure;
use abcms\structure\module\models\StructureSearch;
use abcms\library\base\AdminController;
use yii\web\NotFoundHttpException;
use abcms\structure\module\models\FieldSearch;

/**
 * DefaultController implements the CRUD actions for Structure model.
 */
class DefaultController extends AdminController
{

    /**
     * Lists all Structure models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StructureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Structure model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new FieldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);
        
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Structure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Structure();
        if(isset($this->createScenario)) {
            $model->scenario = $this->createScenario;
        }
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->name){
                $model->name = null;
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Structure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->name){
                $model->name = null;
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Structure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Structure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Structure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Structure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
