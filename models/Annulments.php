<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "annulments".
 *
 * @property int $id
 * @property string $descripcion
 * @property string $n_factura
 *
 * @property HeadFact[] $headFacts
 */
class Annulments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'annulments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'n_factura'], 'required'],
            [['n_factura'], 'string'],
            [['descripcion'], 'string', 'max' => 100],
            [['n_factura'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'n_factura' => 'N Factura',
        ];
    }

    /**
     * Gets query for [[HeadFacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHeadFacts()
    {
        return $this->hasMany(HeadFact::className(), ['id_anulacion ' => 'id']);
    }
}
