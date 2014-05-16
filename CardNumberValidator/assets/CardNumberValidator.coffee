class CardNumberValidator
  constructor: (@config) ->

  isValidLuhn: (luhn) ->
    len = luhn.length
    mul = 0
    prodArr = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [0, 2, 4, 6, 8, 1, 3, 5, 7, 9]]
    sum = 0
    while (len--)
      sum += prodArr[mul][parseInt(luhn.charAt(len), 10)];
      mul ^= 1;
    sum % 10 is 0 && sum > 0;

  validate: (value, messages = []) ->
    rawvalue = value.replace(/[^\d]/g, '')

    if (!value.match(new RegExp(@config.pattern, 'ig')))
      messages.push(@config.message)
    else if (@config.validateLuhn && !@isValidLuhn(rawvalue))
      messages.push(@config.messageLuhn);

    messages

window.capitalist = $.extend(window.capialist, {CardNumberValidator: CardNumberValidator});