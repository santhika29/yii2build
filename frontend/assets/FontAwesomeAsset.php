<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright(c)2008 YiiSoftware LLC
 * @license http://www.yiiframework.com/license/
 */
 namespace frontend\assets;

 use yii\web\AssetBundle;
 /**
  * @author JoaoMarques<joao@jjmf.com>
  */

 class FontAwesomeAsset extends AssetBundle
 { 
 	#source Path points to the composer package.
 	public $sourcePath = '@vendor/fortawesome/font-awesome';

 	#CSS file to be loaded.
 	public $css = ['css/font-awesome.min.css',];

 	/**
 	 * Sets the publish Options property.
 	 * Needed because it's necessary to 
 	 * concatenate
 	 * the namespace value.
 	 */

 	public function init()
 	{
 		$this->publishOptions =[
 			'forceCopy' => YII_DEBUG,
 			'beforeCopy' => __NAMESPACE__.'\FontAwesomeAsset::filterFolders'
 		];
 		parent::init();
 	}
 	
 	/**
 	 * Filters the published files and folders.
 	 * It's not necessary publish all files and folders
 	 * from the font-awesome package
 	 * Just the CSS and FONTS folder.
 	 * @param string $from 
 	 * @param string $to
 	 * @return bool true to publish to file/folder.
 	 */
 	public static function filterFolders($from,$to)
 	{
 		$validFilesAndFolders=[
 			'css',
 			'fonts',
 			'font-awesome.css',
 			'font-awesome.min.css',
 			'FontAwesome.otf',
 			'fontawesome-webfont.eot',
 			'fontawesome-webfont.svg','
 			fontawesome-webfont.ttf',
 			'fontawesome-webfont.woff',
 		];

 		$pathItems = array_reverse(explode(DIRECTORY_SEPARATOR,$from));

 		if(in_array($pathItems[0],$validFilesAndFolders))return true;
 			else return false;
 	}
}