<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "head_fact".
 *
 * @property string|null $f_timestamp
 * @property string $n_documentos
 * @property int $id_personas
 * @property string|null $referencia
 * @property string $orden_cv
 * @property bool|null $Entregado
 * @property string|null $autorizacion
 * @property string|null $tipo_de_documento
 * @property int|null $id_saleman
 * @property int $id
 * @property Person $personas
 * @property Person $saleman
 */
class HeadFact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'head_fact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['f_timestamp'], 'required'],
            [['n_documentos', 'id_personas','referencia','autorizacion'], 'required'],
            [['id_personas', 'id_saleman'], 'default', 'value' => null],
            [['id_personas', 'id_saleman'], 'integer'],
            [['Entregado'], 'boolean'],
            [['n_documentos', 'referencia', 'orden_cv', 'autorizacion', 'tipo_de_documento'], 'string', 'max' => 50],
            [['n_documentos'], 'unique'],
            [['id_personas'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['id_personas' => 'id']],
            [['id_saleman'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['id_saleman' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'f_timestamp' => 'F Timestamp',
            'n_documentos' => 'N° Documentos',
            'id_personas' => 'Id Personas',
            'referencia' => 'Referencia',
            'orden_cv' => 'Orden CV',
            'Entregado' => 'Entregado',
            'autorizacion' => 'Autorización',
            'tipo_de_documento' => 'Tipo De Documento',
            'id_saleman' => 'Id Saleman',
            'id' => 'ID'
        ];
    }

    /**
     * Gets query for [[Personas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasOne(Person::className(), ['id' => 'id_personas']);
    }
    public function getInstitution()
    {
        return $this->hasOne(Institution::className(), ['id' => 'institution_id']);
    }
    /**
     * Gets query for [[Saleman]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSaleman()
    {
        return $this->hasOne(Person::className(), ['id' => 'id_saleman']);
    }
}
