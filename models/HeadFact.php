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
 * @property string|null $orden_cv
 * @property bool|null $Entregado
 * @property string|null $autorizacion
 * @property string|null $tipo_de_documento
 * @property int|null $id_saleman
 * @property int $id
 * @property int|null $id_anulacion
 *
 * @property Charges[] $charges
 * @property Person $personas
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
            [['f_timestamp'], 'safe'],
            [['n_documentos', 'id_personas'], 'required'],
            [['id_personas', 'id_saleman', 'id_anulacion'], 'default', 'value' => null],
            [['id_personas', 'id_saleman', 'id_anulacion'], 'integer'],
            [['Entregado'], 'boolean'],
            [['n_documentos', 'referencia', 'orden_cv', 'autorizacion', 'tipo_de_documento'], 'string', 'max' => 50],
            [['n_documentos'], 'unique'],
            [['id_personas'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['id_personas' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'f_timestamp' => 'F Timestamp',
            'n_documentos' => 'N Documentos',
            'id_personas' => 'Id Personas',
            'referencia' => 'Referencia',
            'orden_cv' => 'Orden Cv',
            'Entregado' => 'Entregado',
            'autorizacion' => 'Autorizacion',
            'tipo_de_documento' => 'Tipo De Documento',
            'id_saleman' => 'Id Saleman',
            'id' => 'ID',
            'id_anulacion' => 'Id Anulacion',
        ];
    }

    /**
     * Gets query for [[Charges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCharges()
    {
        return $this->hasMany(Charges::className(), ['n_document' => 'n_documentos']);
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
}
