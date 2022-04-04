<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property bool $status
 * @property string|null $category
 * @property int $product_type_id
 * @property string|null $brand
 * @property float $product_iva_id
 * @property float|null $precio
 * @property float|null $costo
 * @property int|null $chairaccount_id
 * @property int|null $Chairinve
 * @property int|null $charingresos
 * @property int|null $institution_id
 *
 * @property FacturaBody[] $facturaBodies
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'product_type_id', 'product_iva_id'], 'required'],
            [['status'], 'boolean'],
            [['product_type_id', 'chairaccount_id', 'Chairinve', 'charingresos', 'institution_id'], 'default', 'value' => null],
            [['product_type_id', 'chairaccount_id', 'Chairinve', 'charingresos', 'institution_id'], 'integer'],
            [['product_iva_id', 'precio', 'costo'], 'number'],
            [['name', 'brand'], 'string', 'max' => 250],
            [['category'], 'string', 'max' => 258],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'status' => 'Disponible',
            'category' => 'Categoria',
            'product_type_id' => 'Product Type ID',
            'brand' => 'Marca',
            'product_iva_id' => 'Product Iva',
            'precio' => 'Precio',
            'costo' => 'Costo',
            'chairaccount_id' => 'Chairaccount ID',
            'Chairinve' => 'Chairinve',
            'charingresos' => 'Charingresos',
            'institution_id' => 'Institution ID',
        ];
    }

    /**
     * Gets query for [[FacturaBodies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacturaBodies()
    {
        return $this->hasMany(FacturaBody::className(), ['id_producto' => 'id']);
    }
}
