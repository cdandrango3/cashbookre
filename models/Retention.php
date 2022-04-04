<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "retention".
 *
 * @property int $id
 * @property int|null $id_chart
 * @property float $percentage
 * @property string|null $codesri
 * @property string|null $slug
 * @property int|null $id_charting
 */
class Retention extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'retention';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_chart', 'id_charting'], 'default', 'value' => null],
            [['id_chart', 'id_charting'], 'integer'],
            [['percentage'], 'number'],
            [['codesri', 'slug'], 'string'],
            [['codesri'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_chart' => 'Id Chart',
            'percentage' => 'Percentage',
            'codesri' => 'Codesri',
            'slug' => 'Slug',
            'id_charting' => 'Id Charting',
        ];
    }
}
