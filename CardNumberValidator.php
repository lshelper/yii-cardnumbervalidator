<?php

class CardNumberValidator extends \CValidator
{

    /**
     * Валидация алгоритмом Луна
     * @link:http://en.wikipedia.org/wiki/Luhn_algorithm
     *
     * @var bool
     */
    public $validateLuhn = true;

    public $validateLength = true;

    public $minLength = 16;

    public $maxLength = 16;

    public $allowEmpty = false;

    public $pattern = '^\d{4}\-\d{4}\-\d{4}\-\d{4}$';

    public $message = 'Номер карты должен состоять из 16 цифр.';

    public $messageLengthTooShort = 'Номер карты должен состоять как минимум из {n} цифр.';

    public $messageLengthTooLong = 'Номер карты должен состоять максимум из {n} цифр.';

    public $messageLuhn = 'Неверный номер карты.';

    /**
     * Validates a single attribute.
     * This method should be overridden by child classes.
     * @param \CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        if (!$value && $this->allowEmpty)
            return;

        if (!preg_match($this->getPattern(), $value))
        {
            $object->addError($attribute, \Yii::t('creditcards', $this->message));
            return;
        }

        $rawvalue = $this->deformat($value);

        if ($this->validateLength)
        {
            if (!is_null($this->minLength) && (strlen($rawvalue) < $this->minLength))
            {
                $object->addError($attribute, \Yii::t('creditcards', $this->messageLengthTooShort, $this->minLength));
                return;
            }
            if (!is_null($this->maxLength) && (strlen($rawvalue) > $this->maxLength))
            {
                $object->addError($attribute, \Yii::t('creditcards', $this->messageLengthTooLong, $this->maxLength));
                return;
            }
        }

        if ($this->validateLuhn)
        {
            if ($this->isValidLuhn($rawvalue))
            {
                $object->addError($attribute, \Yii::t('creditcards', $this->messageLuhn));
                return;
            }
        }

    }

    public function isValidLuhn($number) {
        settype($number, 'string');
        $sumTable = array(
            array(0,1,2,3,4,5,6,7,8,9),
            array(0,2,4,6,8,1,3,5,7,9));
        $sum = 0;
        $flip = 0;
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $sum += $sumTable[$flip++ & 0x1][$number[$i]];
        }
        return $sum % 10 === 0;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function deformat($value)
    {
        return preg_replace('#[^\d]#', '', $value);
    }

    /**
     * Returns the JavaScript needed for performing client-side validation.
     * @param \CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     * @return string the client-side validation script.
     * @see CActiveForm::enableClientValidation
     * @since 1.1.7
     */
    public function clientValidateAttribute($object, $attribute)
    {
        $static = file_get_contents( __DIR__ . '/CardNumberValidator/assets/CardNumberValidator.js');
        \Yii::app()->clientscript->registerScript(get_called_class(). '.clientscript', $static, \CClientScript::POS_READY);
        $str = file_get_contents( __DIR__ . '/CardNumberValidator/assets/validator.js');
        return '(function(config) {'.$str.'})('.$this->getJavascriptConfig().')';
    }

    public function getMessage()
    {
        $message = $this->message !== null ? $this->message : \Yii::t('validators', '{attribute} must be number.');
        return $message;
    }

    protected function getJavascriptConfig()
    {
        return \CJavaScript::encode([
            'validateLuhn' => $this->validateLuhn,
            'validateLength' => $this->validateLength,
            'minLength' => $this->minLength,
            'maxLength' => $this->maxLength,
            'allowEmpty' => $this->allowEmpty,
            'pattern' => $this->getPattern(),
            'message' => $this->message,
            'messageLuhn' => $this->messageLuhn
        ]);
    }

} 