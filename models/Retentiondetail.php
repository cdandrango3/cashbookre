<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "retentiondetail".
 *
 * @property int $id
 * @property string $id_factura
 * @property int $code
 * @property string $n_retencion
 * @property float|null $base
 * @property int|null $porcentage
 * @property float|null $total
 * @property string|null $slug
 * @property int|null $autorizacion
 */
class Retentiondetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'retentiondetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_factura', 'code', 'n_retencion'], 'required'],
            [['id_factura', 'n_retencion', 'slug'], 'string'],
            [['code', 'porcentage', 'autorizacion'], 'default', 'value' => null],
            [['code', 'porcentage', 'autorizacion'], 'integer'],
            [['base', 'total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_factura' => 'Id Factura',
            'code' => 'Code',
            'n_retencion' => 'N de factura',
            'base' => 'Base',
            'porcentage' => 'Porcentage',
            'total' => 'Total',
            'slug' => 'Slug',
            'autorizacion' => 'Autorizacion',
        ];
    }
}
