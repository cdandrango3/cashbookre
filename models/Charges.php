<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "charges".
 *
 * @property int $id
 * @property int|null $person_id
 * @property bool|null $status
 * @property string|null $n_document
 * @property string|null $date
 * @property string|null $Description
 * @property string|null $type_charge
 * @property int $institution_id
 *
 * @property HeadFact $nDocument
 * @property Person $person
 */
class Charges extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'charges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['person_id'], 'default', 'value' => null],
            [['person_id'], 'integer'],
            [['status'], 'boolean'],
            [['n_document', 'Description', 'type_charge'], 'string'],
            [['date'], 'safe'],
            [['n_document'], 'exist', 'skipOnError' => true, 'targetClass' => HeadFact::className(), 'targetAttribute' => ['n_document' => 'n_documentos']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'person_id' => 'Person ID',
            'status' => 'Status',
            'n_document' => 'N Document',
            'date' => 'Date',
            'Description' => 'Description',
            'type_charge' => 'Type Charge',
            'institution_id' => 'Institution ID',
        ];
    }

    /**
     * Gets query for [[NDocument]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNDocument()
    {
        return $this->hasOne(HeadFact::className(), ['n_documentos' => 'n_document']);
    }

    /**
     * Gets query for [[Person]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }
}
