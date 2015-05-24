<?php
/**
 * @var $breadcrumbs array
 * @var $this yii\web\View
 * @var $contentPage webvimark\ybc\content\models\ContentPage
 */

$this->title = $contentPage->meta_title ? $contentPage->meta_title : $contentPage->name;

//$this->params['breadcrumbs'] = $breadcrumbs;

if ( $contentPage->meta_keywords )
{
	$this->registerMetaTag([
		'name'=>'keywords',
		'content'=>$contentPage->meta_keywords,
	], 'keywords');
}
if ( $contentPage->meta_description )
{
	$this->registerMetaTag([
		'name'=>'description',
		'content'=>$contentPage->meta_description,
	], 'description');
}
?>

<?= $contentPage->body ?>