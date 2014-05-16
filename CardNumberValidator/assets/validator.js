var validator = new window.lshelpers.CardNumberValidator(config);
messages = $.extend(messages, validator.validate(value));