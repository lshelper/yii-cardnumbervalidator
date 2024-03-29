// Generated by CoffeeScript 1.6.3
(function() {
  var CardNumberValidator;

  CardNumberValidator = (function() {
    function CardNumberValidator(config) {
      this.config = config;
    }

    CardNumberValidator.prototype.isValidLuhn = function(luhn) {
      var len, mul, prodArr, sum;
      len = luhn.length;
      mul = 0;
      prodArr = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [0, 2, 4, 6, 8, 1, 3, 5, 7, 9]];
      sum = 0;
      while (len--) {
        sum += prodArr[mul][parseInt(luhn.charAt(len), 10)];
        mul ^= 1;
      }
      return sum % 10 === 0 && sum > 0;
    };

    CardNumberValidator.prototype.validate = function(value, messages) {
      var rawvalue;
      if (messages == null) {
        messages = [];
      }
      rawvalue = value.replace(/[^\d]/g, '');
      if (!value.match(new RegExp(this.config.pattern, 'ig'))) {
        messages.push(this.config.message);
      } else {
        if (this.config.validateLength) {
          if (this.config.minLength > 0 && rawvalue.length < this.config.minLength) {
            messages.push(this.config.messageLengthTooShort);
          } else if (this.config.maxLength > 0 && rawvalue.length > this.config.maxLength) {
            messages.push(this.config.messageLengthTooLong);
          }
        }
        if (this.config.validateLuhn && !this.isValidLuhn(rawvalue)) {
          messages.push(this.config.messageLuhn);
        }
      }
      return messages;
    };

    return CardNumberValidator;

  })();

  window.lshelpers = $.extend(window.lshelpers, {
    CardNumberValidator: CardNumberValidator
  });

}).call(this);
