<?php

namespace abcms\structure\module\controllers;

use Yii;
use abcms\structure\models\Field;
use abcms\library\base\AdminController;
use yii\web\NotFoundHttpException;
use abcms\structure\models\Structure;

/**
 * FieldController implements the CRUD actions for Field model.
 */
class FieldController extends AdminController
{

    /**
     * Creates a new Field model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $structureId
     * @return mixed
     */
    public function actionCreate($structureId)
    {
        $structure = $this->findStructureModel($structureId);
        $model = new Field(['structureId' => $structure->id]);
        if(isset($this->createScenario)) {
            $model->scenario = $this->createScenario;
        }
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['default/view', 'id' => $structure->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'structure' => $structure,
            ]);
        }
    }

    /**
     * Updates an existing Field model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $structure = $model->structure;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['default/view', 'id' => $structure->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'structure' => $structure,
            ]);
        }
    }

    /**
     * Deletes an existing Field model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $structureId = $model->structureId;
        $model->delete();

        return $this->redirect(['default/view', 'id' => $structureId]);
    }
    
    /**
     * Finds the Structure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Structure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findStructureModel($id)
    {
        if (($model = Structure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Field model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Field the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Field::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
