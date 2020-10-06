<?php

namespace common\modules\shop\models\backend;

use common\modules\shop\models\backend\query\ProductQuery;
use common\modules\shop\Module;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property int|null $code
 * @property string $name
 * @property string $description
 * @property string $alias
 * @property int $category_id
 * @property int|null $new
 * @property int|null $sale
 * @property int|null $active
 * @property int $status_id
 * @property int|null $percent
 * @property int|null $price
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AttributeValue[] $attributeValues
 * @property Attribute[] $productAttributes
 * @property Image[] $images
 * @property Category $category
 * @property Status $status
 * @property ProductTag[] $productTags
 * @property-read array $relatedData
 * @property Tag[] $tags
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'category_id', 'new', 'sale', 'active', 'status_id', 'percent', 'price', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'alias', 'category_id', 'status_id'], 'required'],
            [['name', 'description', 'alias'], 'string', 'max' => 255],
            [['alias'], 'unique'],
            [['code'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('module', 'ID'),
            'code' => Module::t('module', 'CODE'),
            'name' => Module::t('module', 'NAME'),
            'description' => Module::t('module', 'DESCRIPTION'),
            'alias' => Module::t('module', 'ALIAS'),
            'category_id' => Module::t('module', 'CATEGORY_ID'),
            'new' => Module::t('module', 'NEW'),
            'sale' => Module::t('module', 'SALE'),
            'active' => Module::t('module', 'ACTIVE'),
            'status_id' => Module::t('module', 'STATUS_ID'),
            'percent' => Module::t('module', 'PERCENT'),
            'price' => Module::t('module', 'PRICE'),
            'created_at' => Module::t('module', 'CREATED_AT'),
            'updated_at' => Module::t('module', 'UPDATED_AT'),
            'tag_id' => Module::t('module', 'TAG_ID'),
        ];
    }

    /**
     * Gets query for [[AttributeValues]].
     *
     */
    public function getAttributeValues()
    {
        return $this->hasMany(AttributeValue::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Attributes]].
     *
     * @throws InvalidConfigException
     */
    public function getProductAttributes()
    {
        return $this->hasMany(Attribute::class, ['id' => 'attribute_id'])->viaTable('{{%attribute_value}}', ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Images]].
     *
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[category]].
     *
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[ProductTags]].
     *
     */
    public function getProductTags()
    {
        return $this->hasMany(ProductTag::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @throws InvalidConfigException
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('{{%product_tag}}', ['product_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    /**
     * Gets list for relations models
     *
     * @return array
     */
    public function getRelatedData()
    {
        $categories = (new Category())->getRelatedData();

        return $relatedData = [
            'tags' => Tag::find()->select(['name', 'id'])->indexBy('id')->column(),
            'statuses' => Status::find()->select(['status', 'id'])->indexBy('id')->column(),
            'categories' => $categories,
        ];
    }
}
