<?php

namespace common\modules\dashboard\models;

use Yii;
use common\modules\dashboard\models\AdminUsers;

/**
 * This is the model class for table "admin_menu".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $url
 * @property string $css
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'name', 'url', 'css'], 'required'],
            [['parent_id'], 'integer'],
            [['name', 'url', 'css'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'parent_id' => Yii::t('form', 'Parent ID'),
            'name' => Yii::t('form', 'Name'),
            'url' => Yii::t('form', 'Url'),
            'css' => Yii::t('form', 'Css'),
        ];
    }
    
    /**
     * Forming admin menu list
     *
     * @return array
     */
    public static function formAdminMenuList($userID, $groupID)
    {
		$result = [];
		echo 'Work2!';
		if($userID > 0 && $groupID > 0)
		{	echo 'Work!';
			$adminList = self::getAdminMenuList();
			print_r($adminList);
			if(!empty($adminList))
			{
				$model = AdminUsers::find('group_id')->where('id=:id', [':id' => 1])->one();
				
				if($model !== null)
				{
					//initial auth manager
					$authManager = Yii::$app->getAuthManager();
				
					//Get permissions by user id		
					$permissionsList = $authManager->getPermissionsByUser($userID);
					
					//Forming menu list
					foreach($adminList as $key => $menuData)
					{
						//Check admin group
						if(($model->group_id == $groupID))
						{	
							$result[] = $menuData;
						}
						else
						{
							//Check submenu list
							if(isset($menuData['items']))
							{	
								//Forming submenu list
								foreach($menuData['items'] as $key => $itemsData)
								{
									if(isset($itemsData['permission']) && !isset($permissionsList[$itemsData['permission']]))
									{
										unset($menuData['items'][$key]);
									}
								}
								
								if(!empty($menuData['items']))
								{
									$result[] = $menuData;
								}
							}
							else
							{
								//Check menu list
								if(isset($permissionsList['permission']) && $permissionsList[$permissionsList['permission']])
								{
									$result[] = $menuData;
								}
							}
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	/**
     * Get admin menu list
     *
     * @return array
     */
	public static function getAdminMenuList()
    {
		return [
			[
				'label' => Yii::t('menu', 'Admin-stuff'),
				'url' => ['/users'],
				'template' => self::setAdminMenuTemplate(),
				'items' => [
					['label' => Yii::t('menu', 'Users'), 'url' => ['/users'], 'permission' => 'usersView'],
					['label' => Yii::t('menu', 'Permissions'), 'url' => ['/permissions'], 'permission' => 'permissionsView'],
				]
			],
			[
				'label' => Yii::t('menu', 'Content'),
				'url' => ['/content'],
				'template' => self::setAdminMenuTemplate(),
				'items' => [
					['label' => Yii::t('menu', 'Menu'), 'url' => ['/content/menu'], 'permission' => 'menuView'],
					['label' => Yii::t('menu', 'Content'), 'url' => ['/content/content'], 'permission' => 'contentView'],
					['label' => Yii::t('menu', 'Static content'), 'url' => ['/content/static-content'], 'permission' => 'staticContentView']
				]
			],
			[
				'label' => Yii::t('menu', 'News'),
				'url' => ['/news'],
				'template' => self::setAdminMenuTemplate(),
				'items' => [
					['label' => Yii::t('menu', 'News'), 'url' => ['/news'], 'permission' => 'newsView']
				]
			]
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
