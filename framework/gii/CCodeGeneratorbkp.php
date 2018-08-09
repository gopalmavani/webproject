<?php
/**
 * CCodeGenerator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CCodeGenerator is the base class for code generator classes.
 *
 * CCodeGenerator is a controller that predefines several actions for code generation purpose.
 * Derived classes mainly need to configure the {@link codeModel} property
 * override the {@link getSuccessMessage} method. The former specifies which
 * code model (extending {@link CCodeModel}) that this generator should use,
 * while the latter should return a success message to be displayed when
 * code files are successfully generated.
 *
 * @property string $pageTitle The page title.
 * @property string $viewPath The view path of the generator.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.gii
 * @since 1.1.2
 */
class CCodeGenerator extends CController
{
	/**
	 * @var string the layout to be used by the generator. Defaults to 'generator'.
	 */
	public $layout='generator';
	/**
	 * @var array a list of available code templates (name=>path)
	 */
	public $templates=array();
	/**
	 * @var string the code model class. This can be either a class name (if it can be autoloaded)
	 * or a path alias referring to the class file.
	 * Child classes must configure this property with a concrete value.
	 */
	public $codeModel;

	private $_viewPath;

	/**
	 * @return string the page title
	 */
	public function getPageTitle()
	{
		return 'Gii - '.ucfirst($this->id).' Generator';
	}

	/**
	 * The code generation action.
	 * This is the action that displays the code generation interface.
	 * Child classes mainly need to provide the 'index' view for collecting user parameters
	 * for code generation.
	 */
	public function actionIndex()
	{
		header('Content-Type: application/json');
		$request = CJSON::decode( Yii::app()->request->getRawBody() );
		$tableName = $request['tableName'];
		$modelClass = $request['modelClass'];
		$template = $request['template'];

		if( $template == '' OR $modelClass == '' OR $tableName == '' ) {
			echo CJSON::encode([
				"result" => false,
				"message" => "tableName, modelClass and templates are mandatory fields",
				"fields" => $request['tableName'] . $request['modelClass'] . $request['template']
			]); exit;
		} else {
			$default = [
				"connectionId" => "db",
				"tablePrefix" => "",
				"tableName" => $tableName,
				"modelClass" => $modelClass,
				"baseClass" => "CActiveRecord",
				"modelPath" => "application.models",
				"buildRelations" => "1",
				"commentsAsLabels" => "0",
				"template" => $template
			];
		}

		$model=$this->prepare($default);
		//if($model->files!=array() && isset($_POST['generate'], $_POST['answers']))
		if($model->files!=array()) {
			// $model->status=$model->save() ? CCodeModel::STATUS_SUCCESS : CCodeModel::STATUS_ERROR;
			if( $model->save() ) {
				$model->status = CCodeModel::STATUS_SUCCESS;
				echo CJSON::encode([
					"result" => true,
					"message" => "Model Generated successfully",
					"status" => $model->status
				]);
			} else {
				$model->status= CCodeModel::STATUS_ERROR;
				echo CJSON::encode([
						"result" => false,
						"message" => "Error",
						"status" => $model->status
				]);
			}
		} else {
			echo CJSON::encode([
					"result" => false,
					"message" => "Could not generate file"
			]);
			exit;
		}
	}

	/**
	 * The code preview action.
	 * This action shows up the specified generated code.
	 * @throws CHttpException if unable to find code generated.
	 */
	public function actionCode()
	{
		$model=$this->prepare();
		if(isset($_GET['id']) && isset($model->files[$_GET['id']]))
		{
			$this->renderPartial('/common/code', array(
					'file'=>$model->files[$_GET['id']],
			));
		}
		else
			throw new CHttpException(404,'Unable to find the code you requested.');
	}

	/**
	 * The code diff action.
	 * This action shows up the difference between the newly generated code and the corresponding existing code.
	 * @throws CHttpException if unable to find code generated.
	 */
	public function actionDiff()
	{
		Yii::import('gii.components.TextDiff');

		$model=$this->prepare();
		if(isset($_GET['id']) && isset($model->files[$_GET['id']]))
		{
			$file=$model->files[$_GET['id']];
			if(!in_array($file->type,array('php', 'txt','js','css','sql')))
				$diff=false;
			elseif($file->operation===CCodeFile::OP_OVERWRITE)
				$diff=TextDiff::compare(file_get_contents($file->path), $file->content);
			else
				$diff='';

			$this->renderPartial('/common/diff',array(
					'file'=>$file,
					'diff'=>$diff,
			));
		}
		else
			throw new CHttpException(404,'Unable to find the code you requested.');
	}

	/**
	 * Returns the view path of the generator.
	 * The "views" directory under the directory containing the generator class file will be returned.
	 * @return string the view path of the generator
	 */
	public function getViewPath()
	{
		if($this->_viewPath===null)
		{
			$class=new ReflectionClass(get_class($this));
			$this->_viewPath=dirname($class->getFileName()).DIRECTORY_SEPARATOR.'views';
		}
		return $this->_viewPath;
	}

	/**
	 * @param string $value the view path of the generator.
	 */
	public function setViewPath($value)
	{
		$this->_viewPath=$value;
	}

	/**
	 * Prepares the code model.
	 */
	protected function prepare($attributes)
	{
		if($this->codeModel===null)
			throw new CException(get_class($this).'.codeModel property must be specified.');
		$modelClass=Yii::import($this->codeModel,true);
		$model=new $modelClass;
		$model->loadStickyAttributes();

		$model->attributes=$attributes;
		$model->status=CCodeModel::STATUS_PREVIEW;
		if($model->validate()) {
			$model->saveStickyAttributes();
			$model->prepare();
		} else {
			echo CJSON::encode([
					"result" => false,
					"message" => "Invalid request"
			]);
			exit;
		}
		return $model;
	}
}