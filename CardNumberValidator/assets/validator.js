var validator = new window.capitalist.CardNumberValidator(config);
messages = $.extend(messages, validator.validate(value));