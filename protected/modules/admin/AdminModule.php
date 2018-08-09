<?php

class AdminModule extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            //'api.models.*',
            'admin.components.*',
            'admin.views.*'
        ));

        $this->layoutPath = Yii::getPathOfAlias('admin.views.layouts');
        $this->layout = 'main';

        $this->setComponents(array(
            'errorHandler' => array(
                'errorAction' => 'admin/home/error'),
            'user' => array(
                'class' => 'CWebUser',
                'loginUrl' => Yii::app()->createUrl('admin/home/login'),
            ),
            'servicehelper' => new ServiceHelper()
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            if(Yii::app()->getModule('admin')->user->isGuest) {
                Yii::app()->getModule('admin')->user->setReturnUrl('admin/home/login');
                return true;
            } else {
                return true;
            }
        }
        else
            return false;
    }
}
