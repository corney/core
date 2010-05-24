/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Common functions and classes
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function parseUri (str) {
	var	o   = parseUri.options,
		m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
		uri = {},
		i   = 14;

	while (i--) uri[o.key[i]] = m[i] || "";

	uri[o.q.name] = {};
	uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
		if ($1) uri[o.q.name][$1] = $2;
	});

	return uri;
};

parseUri.options = {
	strictMode: false,
	key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
	q:   {
		name:   "queryKey",
		parser: /(?:^|&)([^&=]*)=?([^&]*)/g
	},
	parser: {
		strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
	}
};

// Get product id from object class name
function getProductIdFromClassName(obj)
{
  var result = false;

  var c = $(obj).attr('class');

  if (c) {
    var m = c.match(/product-([0-9]+)/);
    if (m) {
      result = parseInt(m[1]);
      if (isNaN(result) || result < 1) {
        result = false;
      }
    }
  }

  return result;
}

function formModify(obj, url)
{
	var form = obj.form;
	if (form) {
		var parsed = parseUri(url);

		for (var key in parsed.queryKey) {
			if (form[key]) {
				form[key].value = parsed.queryKey[key];

			} else {
				var input = document.createElement('INPUT');
				input.type = 'hidden';
				input.name = key;
				input.value = parsed.queryKey[key];

				form.appendChild(input);
			}
		}

		if (
			form.getAttribute('method')
			&& form.getAttribute('method').toUpperCase() == 'POST'
			&& (parsed.query || parsed.path || parsed.host)
		) {
			form.setAttribute('action', url);
		}
	}

	return true;
}

function eventBind(obj, e, func)
{
	if ($) {
		$(obj).bind(e, func);

	} else if (window.addEventListener) {
		obj.addEventListener(e, func, false);

	} else if (window.attachEvent) {
		window.attachEvent('on' + e, func);
	}
}

// URL builder singleton
var URLHandler = {

  mainParams: {target: true, action: true},

  baseURLPart: 'cart.php?',
  argSeparator: '&',
  nameValueSeparator: '=',

  // Return query param
  getParamValue: function(name, params)
  {
    return name + this.nameValueSeparator + params[name];
  },

  // Get param value for the target and action params
  getMainParamValue: function(name, params)
  {
    return URLHandler.getParamValue(name, params);
  },

  // Get param value for the remained params
  getQueryParamValue: function(name, params)
  {
    return URLHandler.getParamValue(name, params);
  },

  // Build HTTP query
  implodeParams: function(params, method)
  {
    result = '';
    isStarted = false;

    for (x in params) {

      if (isStarted) {
        result += this.argSeparator;
      } else {
        isStarted = true;
      }

      result += method(x, params);
    }

    return result;
  },

  // Implode target and action params
  implodeMainParams: function(params)
  {
    return this.implodeParams(params, this.getMainParamValue);
  },

  // Implode remained params
  implodeQueryParams: function(params)
  {
    return this.implodeParams(params, this.getQueryParamValue);
  },
  
  // Return some params
  getParams: function(params, toReturn)
  {
    result = [];

    for (x in toReturn) {
      result[x] = params[x];
    }

    return result;
  },

  // Unset some params
  clearParams: function(params, toClear)
  {
    result = [];

    for (x in params) {
      if (!(x in toClear)) {
        result[x] = params[x];
      }
    }
    
    return result;
  },

  // Compose target and action
  buildMainPart: function(params)
  {
    return this.implodeMainParams(this.getParams(params, this.mainParams));
  },

  // Compose remained params
  buildQueryPart: function(params)
  {
    return this.argSeparator + this.implodeQueryParams(this.clearParams(params, this.mainParams));
  },

  // Compose URL
  buildURL: function(params)
  {
    return this.baseURLPart + this.buildMainPart(params) + this.buildQueryPart(params);
  }
}

// Check for the AJAX support
function hasAJAXSupport()
{
  if (typeof(window.ajaxSupport) == 'undefined') {
    window.ajaxSupport = false;
    try {

      var xhr = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
      window.ajaxSupport = xhr ? true : false;

    } catch(e) { }
  }

  return window.ajaxSupport;
}

/**
 *  Loadable widget (abstract prototype)
 */
function LoadableWidgetAbstract() {
  this.widgetParams = {};
}

LoadableWidgetAbstract.prototype.modalTarget  = null;
LoadableWidgetAbstract.prototype.widgetTarget = 'main';
LoadableWidgetAbstract.prototype.widgetAction = '';
LoadableWidgetAbstract.prototype.widgetClass  = null;
LoadableWidgetAbstract.prototype.widgetParams = {};

LoadableWidgetAbstract.prototype.isShowModalScreen = false;

// Load widget
LoadableWidgetAbstract.prototype.loadWidget = function()
{
  if (!hasAJAXSupport()) {
    return false;
  }

  this.showModalScreen();

  var o = this;
  $.ajax(
    {
      type: 'get',
      url: this.buildWidgetRequestURL(),
      timeout: 15000,
      complete: function(xhr, s) {
        return o.loadHandler(xhr, s);
      }
    }
  );

  return true;
}

// Build request widget URL (AJAX)
LoadableWidgetAbstract.prototype.buildWidgetRequestURL = function()
{
  var params = {
    target:     'get_widget',
    action:     '',
    ajaxTarget: this.widgetTarget,
    ajaxAction: this.widgetAction,
    ajaxClass:  this.widgetClass
  };

  params = this.addWidgetParams(params);

  return URLHandler.buildURL(params);
}

// Add widget arguments to parameters list
LoadableWidgetAbstract.prototype.addWidgetParams = function(params)
{
  $.each(
    this.widgetParams,
    function(key, value) {
      params[key] = value;
    }
  );

  return params;
}

// onload handler
LoadableWidgetAbstract.prototype.loadHandler = function(xhr, s)
{
  var processed = false;

  if (xhr.status == 200 && xhr.responseText) {
    var div = document.createElement('DIV');
    div.style.display = 'none';
    $('body').get(0).appendChild(div);
    div = $(div);
    div.html(xhr.responseText);

    processed = this.placeRequestData(this.extractRequestData(div));

    div.remove();
  }

  this.hideModalScreen();

  this.postprocess(processed);

  return processed;
}

// Extract widget data
LoadableWidgetAbstract.prototype.extractRequestData = function(div)
{
  return div.children().eq(0);
}

// Place request data
LoadableWidgetAbstract.prototype.placeRequestData = function(box)
{
  var id = 'temporary-ajax-id-' + (new Date()).getTime();
  box.addClass(id);
  this.modalTarget.replaceWith(box);
  this.modalTarget = $('.' + id);
  this.modalTarget.removeClass(id);

  return true;
}

// Widget post processing (after new widge data placing)
LoadableWidgetAbstract.prototype.postprocess = function(isSuccess)
{
}

// Show modal screen
LoadableWidgetAbstract.prototype.showModalScreen = function()
{
  if (!this.modalTarget) {
    return false;
  }

  if (this.isShowModalScreen) {
    return true;
  }

  this.modalTarget.block(
    {
      message: '<div></div>',
      css: {
        width: '30%',
        top: '35%',
        left: '35%'
      },
      overlayCSS: {
        opacity: 0.1
      }
    }
  );

  $('.blockElement')
    .css({padding: null, border: null, margin: null, textAlign: null, color: null, backgroundColor: null, cursor: null})
    .addClass('wait-block');

  $('.blockOverlay')
    .css({padding: null, border: null, margin: null, textAlign: null, color: null, backgroundColor: null, cursor: null})
    .addClass('wait-block-overlay');

  this.isShowModalScreen = true;

  return true;
}

// Hide modal screen
LoadableWidgetAbstract.prototype.hideModalScreen = function()
{
  if (!this.modalTarget || !this.isShowModalScreen) {
    return false;
  }

  this.modalTarget.unblock();

  this.isShowModalScreen = false;

  return true;
}

/**
 * Common input validator
 */
function InputValidator(container)
{
  if (!container) {
    return false;
  }

  container = $(container);
  if (!container.length) {
    return false;
  }

  var o = this;

  $(':input', container).each(
    function() {
      o.assignValidator(this);
    }
  );
}

InputValidator.prototype.classRegExp = /^field-(.+)$/;

InputValidator.prototype.assignValidator = function(elm)
{
  if (elm.className && typeof(elm.validators) == 'undefined') {
    elm.validators = [];

    var classes = elm.className.split(/ /);
    var m, methodName;
    for (var i = 0; i < classes.length; i++) {

      m = classes[i].match(this.classRegExp);

      if (m && m[1]) {
        methodName = m[1].replace(/-[a-z]/, this.buildMethodName);
        methodName = 'validate' + methodName.substr(0, 1).toUpperCase() + methodName.substr(1);
        if (typeof(this[methodName]) !== 'undefined') {
          elm.validators[elm.validators.length] = this[methodName];
        }
      }
    }

    if (elm.validators.length) {
      elm.validator = this;
      var o = this;

      elm.labelName = null;
      if (elm.id) {
        var lbl = $('label[for="' + elm.id + '"]').eq(0);
        if (lbl.length) {
          elm.labelName = $.trim(lbl.html()).replace(/:$/, '');
        }
      }
      elm.validate = function(silent) {
        return o.checkElement.call(this, null, silent);
      }

      $(elm).change(
        function(event) {
          return this.validate();
        }
      );

      if (elm.form && !elm.form.validate) {
        elm.form.validate = function() {
          return o.checkForm.call(this);
        }

        $(elm.form).submit(
          function(event) {
            return this.validate();
          }
        );
      }

    }
  }
}

InputValidator.prototype.buildMethodName = function(str)
{
  return str.substr(1).toUpperCase();
}

InputValidator.prototype.checkElement = function(event, silent)
{
  var result = {status: true};

  // Check visibility
  if (0 < this.validators.length) {
    var hidden = $(this).parents().filter(
      function() {
        return this.style.display == 'none';
      }
    );

    if (0 < hidden.length) {
      return true;
    }
  }

  for (var i = 0; i < this.validators.length && result.status; i++) {
    result = this.validators[i].call(this, event);
    if (!result.status) {
      $(this).addClass('validation-error');
      if (!silent) {
        if (this.labelName) {
          result.message = result.message.replace(/Field/, '\'' + this.labelName + '\' field');
        }
        alert(result.message);
        var o = $(this);
        setTimeout(
          function() {
            o.focus();
          },
          200
        );
      }

    } else {
      $(this).removeClass('validation-error');
    }
  }

  return result.status;
}

InputValidator.prototype.checkForm = function()
{
  var result = true;

  $(':input', this).each(
    function() {
      if (this.validate && result) {
        result = this.validate();
      }
    }
  );

  return result;
}

/**
 * Validators
 */

InputValidator.prototype.validateEmail = function()
{
  var re = new RegExp(
    "^[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z](?:[a-z0-9-]*[a-z0-9])?$",
    'gi'
  );

  return {
    status: !this.value.length || this.value.search(re) !== -1,
    message: 'Field is not e-mail address! Please correct'
  };
}

InputValidator.prototype.validateInteger = function()
{
  return {
    status: !this.value.length || this.value.search(/^[-+]?[0-9]+$/) !== -1,
    message: 'Field is not integer! Please correct'
  };
}

InputValidator.prototype.validateFloat = function()
{
  return {
    status: !this.value.length || this.value.search(/^[-+]?[0-9]+\.?[0-9]*$/) !== -1,
    message: 'Field is not float! Please correct'
  };
}

InputValidator.prototype.validatePositive = function()
{
  var value = parseFloat(this.value);

  return {
    status: !this.value.length || 0 <= value,
    message: 'Field is not positive! Please correct'
  };
}

InputValidator.prototype.validateNegative = function()
{
  var value = parseFloat(this.value);

  return {
    status: !this.value.length || 0 >= value,
    message: 'Field is not negative! Please correct'
  };
}

InputValidator.prototype.validateNonZero = function()
{
  var value = parseFloat(this.value);

  return {
    status: !this.value.length || 0 != value,
    message: 'Field is zero! Please correct'
  };
}

InputValidator.prototype.validateRange = function()
{
  var result = {
    status: true,
    message: 'Field is invalid! Please correct'
  };

  var value = parseFloat(this.value);

  if (this.value.length) {
    if (typeof(this.min) !== 'undefined' && this.min > value) {
      result.status = false;
      result.message = 'Field too small!';
    }

    if (typeof(this.max) !== 'undefined' && this.max < value) {
      result.status = false;
      result.message = 'Field too big!';
    }
  }

  return result;
}

InputValidator.prototype.validateRequired = function()
{
  return {
    status: this.value !== null && 0 < this.value.length,
    message: 'Field is required!'
  };
}

$(document).ready(
  function() {
    new InputValidator(document);
  }
);

/**
 * Mousewheel controller
 */
function updateByMouseWheel(event, delta) {
  event.stopPropagation();

  var value = false;
  var mantis = 0;

  if (this.value.length == 0) {
    value = 0;

  } else if (this.value.search(/^ *[+-]?[0-9]+\.?[0-9]* *$/) != -1) {
    var m = this.value.match(/^ *[+-]?[0-9]+\.([0-9]+) *$/);
    if (m && m[1]) {
      mantis = m[1].length;
    }

    value = parseFloat(this.value);
    if (isNaN(value)) {
      value = false;
    }
  }

  if (value !== false) {
    var min = $(this).data('min');
    var max = $(this).data('max');

    value = value + delta * -1;

    if (typeof(min) != 'undefined' && min > value) {
      value = min;
    }

    if (typeof(max) != 'undefined' && max < value) {
      value = max;
    }

    if (mantis) {
      value = Math.round(value * Math.pow(10, mantis)) / Math.pow(10, mantis);

    } else {
      value = Math.round(value);
    }

    var oldValue = this.value;
    this.value = value;

    if (typeof(this.validate) != 'undefined' && !this.validate(true)) {
      this.value = oldValue;
    }

  }

  return false;
}

$(document).ready(
  function() {
    $('input.wheel-ctrl:text').mousewheel(updateByMouseWheel);
  }
);