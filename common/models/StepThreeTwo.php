<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 *
 */
class StepThreeTwo extends Model
{
    const STEP = 4;

    public $filial_id;
    public $lang_id;
    public $edu_form_id;
    public $edu_type_id;
    public $edu_direction_id;
    public $direction_course_id;
    public $edu_name;
    public $edu_direction;

    public function rules()
    {
        return [
            // `edu_type_id` majburiy maydon
            [['filial_id' , 'lang_id' ,'edu_form_id' , 'edu_direction_id' , 'direction_course_id', 'edu_name', 'edu_direction'], 'required'],

            // `edu_type_id` butun son bo'lishi kerak
            [['filial_id' , 'lang_id' ,'edu_form_id' , 'edu_direction_id' , 'direction_course_id'], 'integer'],

            [['edu_name' , 'edu_direction'], 'string' , 'max' => 255],

            [['filial_id'], function ($attribute) {
                if (!Branch::find()->where(['id' => $this->$attribute, 'status' => 1, 'is_deleted' => 0])->exists()) {
                    $this->addError($attribute, 'Tanlangan filial mavjud emas.');
                }
            }],
            [['lang_id'], function ($attribute) {
                if (!Lang::find()->where(['id' => $this->$attribute, 'status' => 1, 'is_deleted' => 0])->exists()) {
                    $this->addError($attribute, 'Tanlangan til mavjud emas.');
                }
            }],
            [['edu_form_id'], function ($attribute) {
                if (!EduForm::find()->where(['id' => $this->$attribute, 'status' => 1, 'is_deleted' => 0])->exists()) {
                    $this->addError($attribute, 'Tanlangan taʼlim shakli mavjud emas.');
                }
            }],
            [['edu_direction_id'], function ($attribute) {
                if (!EduDirection::find()->where([
                    'id' => $this->$attribute,
                    'edu_form_id' => $this->edu_form_id,
                    'edu_type_id' => $this->edu_type_id,
                    'lang_id' => $this->lang_id,
                    'status' => 1,
                    'is_deleted' => 0
                ])->exists()) {
                    $this->addError($attribute, 'Tanlangan taʼlim yo‘nalishi mavjud emas.');
                }
            }],

            [['direction_course_id'], function ($attribute) {
                if (!DirectionCourse::find()->where([
                    'id' => $this->$attribute,
                    'edu_direction_id' => $this->edu_direction_id,
                    'status' => 1,
                    'is_deleted' => 0
                ])->exists()) {
                    $this->addError($attribute, 'Tanlangan taʼlim yo‘nalishi mavjud emas.');
                }
            }],
        ];
    }

    public function getDirectionCourse()
    {
        return $this->hasOne(DirectionCourse::class, ['id' => 'direction_course_id']);
    }

    public function getEduDirection()
    {
        return $this->hasOne(EduDirection::class, ['id' => 'edu_direction_id']);
    }

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
            foreach ($lev1 as $key => $error) {
                $result[] = $error;
            }
        }
        return array_unique($result);
    }


    public function ikStep($user, $student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$this->validate()) {
            return ['is_ok' => false, 'errors' => $this->simple_errors($this->errors)];
        }
        $student->setAttributes([
            'branch_id' => $this->filial_id,
            'edu_name' => $this->edu_name,
            'edu_direction' => $this->edu_direction,
        ]);

        if ($student->edu_direction_id != $this->edu_direction_id || $student->direction_course_id != $this->direction_course_id) {
            $eduDirection = EduDirection::findOne($this->edu_direction_id);
            $directionCourse = DirectionCourse::findOne($this->direction_course_id);
            $student->setAttributes([
                'lang_id' => $this->lang_id,
                'edu_form_id' => $this->edu_form_id,
                'edu_direction_id' => $this->edu_direction_id,
                'direction_id' => $eduDirection->direction_id,
                'direction_course_id' => $this->direction_course_id,
                'course_id' => $directionCourse->course_id,
            ]);

            $result = StepThree::createEduType($student);
            if (!$result['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $errors];
            }

            $amo = CrmPush::processType(5, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        if (!$student->save(false)) {
            $errors[] = ['Student maʼlumotlarini yangilashda xatolik yuz berdi.'];
        }

        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->type = 101;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            CrmPush::FILIAL => $student->branch->name_uz,
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);

        $noStudentUser = Yii::$app->user->identity;
        if ($noStudentUser->user_role != 'student') {
            $user->step = 5;
        } else {
            $user->step = self::STEP;
        }

        if (!$user->save(false)) {
            $errors[] = ['Foydalanuvchi maʼlumotlarini yangilashda xatolik yuz berdi.'];
        }

        if ($user->step == 5) {
            $amo = CrmPush::processType(6, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }
}
