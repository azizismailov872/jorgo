<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_menu".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $url
 * @property string $css
 */
class AdminMenu
{
	/**
     * Get admin menu list
     *
     * @return array
     */
    public static function getAdminMenuList()
    {
		return [
			[
				'label' => Yii::t('menu', 'News'),
				'url' => ['/news'],
				'template' => self::setAdminMenuTemplate(),
				'items' => [
					['label' => Yii::t('menu', 'News'), 'url' => ['/news'], 'permission' => 'partnersView'],
				]
			],
			[
				'label' => Yii::t('menu', 'Content'),
				'url' => ['/menu'],
				'template' => self::setAdminMenuTemplate(),
				'items' => [
					['label' => Yii::t('menu', 'Menu categories'), 'url' => ['/menu-categories'], 'permission' => 'partnersView'],
					['label' => Yii::t('menu', 'Menu'), 'url' => ['/content/menu'], 'permission' => 'menuView'],
				]
			],
		];
    }
    
    /**
     * Set admin menu template
     * @param bool $multi define has menu multi levels or not
     * @return string
     */ 
	public static function setAdminMenuTemplate($multi = true)
    {
		return ($multi) ? 
		'<a>
			<i class="fa fa-sitemap"></i>
			{label}
			<span class="fa fa-chevron-down"></span>
		</a>' 
		: 
		'<a href="{url}">
			<i class="fa fa-bars"></i>
			{label}
		</a>';
	}
}
