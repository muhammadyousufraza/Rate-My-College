/*!
 * Bowser - a browser detector
 * https://github.com/ded/bowser
 * MIT License | (c) Dustin Diaz 2015
 */

!function (name, definition) {
  if (typeof module != 'undefined' && module.exports) module.exports = definition()
  else if (typeof define == 'function' && define.amd) define(name, definition)
  else this[name] = definition()
}('bowser', function () {
  /**
    * See useragents.js for examples of navigator.userAgent
    */

  var t = true

  function detect(ua) {

    function getFirstMatch(regex) {
      var match = ua.match(regex);
      return (match && match.length > 1 && match[1]) || '';
    }

    function getSecondMatch(regex) {
      var match = ua.match(regex);
      return (match && match.length > 1 && match[2]) || '';
    }

    var iosdevice = getFirstMatch(/(ipod|iphone|ipad)/i).toLowerCase()
      , likeAndroid = /like android/i.test(ua)
      , android = !likeAndroid && /android/i.test(ua)
      , nexusMobile = /nexus\s*[0-6]\s*/i.test(ua)
      , nexusTablet = !nexusMobile && /nexus\s*[0-9]+/i.test(ua)
      , chromeos = /CrOS/.test(ua)
      , silk = /silk/i.test(ua)
      , sailfish = /sailfish/i.test(ua)
      , tizen = /tizen/i.test(ua)
      , webos = /(web|hpw)os/i.test(ua)
      , windowsphone = /windows phone/i.test(ua)
      , windows = !windowsphone && /windows/i.test(ua)
      , mac = !iosdevice && !silk && /macintosh/i.test(ua)
      , linux = !android && !sailfish && !tizen && !webos && /linux/i.test(ua)
      , edgeVersion = getFirstMatch(/edge\/(\d+(\.\d+)?)/i)
      , versionIdentifier = getFirstMatch(/version\/(\d+(\.\d+)?)/i)
      , tablet = /tablet/i.test(ua)
      , mobile = !tablet && /[^-]mobi/i.test(ua)
      , xbox = /xbox/i.test(ua)
      , result

    if (/opera|opr|opios/i.test(ua)) {
      result = {
        name: 'Opera'
      , opera: t
      , version: versionIdentifier || getFirstMatch(/(?:opera|opr|opios)[\s\/](\d+(\.\d+)?)/i)
      }
    }
    else if (/coast/i.test(ua)) {
      result = {
        name: 'Opera Coast'
        , coast: t
        , version: versionIdentifier || getFirstMatch(/(?:coast)[\s\/](\d+(\.\d+)?)/i)
      }
    }
    else if (/yabrowser/i.test(ua)) {
      result = {
        name: 'Yandex Browser'
      , yandexbrowser: t
      , version: versionIdentifier || getFirstMatch(/(?:yabrowser)[\s\/](\d+(\.\d+)?)/i)
      }
    }
    else if (/ucbrowser/i.test(ua)) {
      result = {
          name: 'UC Browser'
        , ucbrowser: t
        , version: getFirstMatch(/(?:ucbrowser)[\s\/](\d+(?:\.\d+)+)/i)
      }
    }
    else if (/mxios/i.test(ua)) {
      result = {
        name: 'Maxthon'
        , maxthon: t
        , version: getFirstMatch(/(?:mxios)[\s\/](\d+(?:\.\d+)+)/i)
      }
    }
    else if (/epiphany/i.test(ua)) {
      result = {
        name: 'Epiphany'
        , epiphany: t
        , version: getFirstMatch(/(?:epiphany)[\s\/](\d+(?:\.\d+)+)/i)
      }
    }
    else if (/puffin/i.test(ua)) {
      result = {
        name: 'Puffin'
        , puffin: t
        , version: getFirstMatch(/(?:puffin)[\s\/](\d+(?:\.\d+)?)/i)
      }
    }
    else if (/sleipnir/i.test(ua)) {
      result = {
        name: 'Sleipnir'
        , sleipnir: t
        , version: getFirstMatch(/(?:sleipnir)[\s\/](\d+(?:\.\d+)+)/i)
      }
    }
    else if (/k-meleon/i.test(ua)) {
      result = {
        name: 'K-Meleon'
        , kMeleon: t
        , version: getFirstMatch(/(?:k-meleon)[\s\/](\d+(?:\.\d+)+)/i)
      }
    }
    else if (windowsphone) {
      result = {
        name: 'Windows Phone'
      , windowsphone: t
      }
      if (edgeVersion) {
        result.msedge = t
        result.version = edgeVersion
      }
      else {
        result.msie = t
        result.version = getFirstMatch(/iemobile\/(\d+(\.\d+)?)/i)
      }
    }
    else if (/msie|trident/i.test(ua)) {
      result = {
        name: 'Internet Explorer'
      , msie: t
      , version: getFirstMatch(/(?:msie |rv:)(\d+(\.\d+)?)/i)
      }
    } else if (chromeos) {
      result = {
        name: 'Chrome'
      , chromeos: t
      , chromeBook: t
      , chrome: t
      , version: getFirstMatch(/(?:chrome|crios|crmo)\/(\d+(\.\d+)?)/i)
      }
    } else if (/chrome.+? edge/i.test(ua)) {
      result = {
        name: 'Microsoft Edge'
      , msedge: t
      , version: edgeVersion
      }
    }
    else if (/vivaldi/i.test(ua)) {
      result = {
        name: 'Vivaldi'
        , vivaldi: t
        , version: getFirstMatch(/vivaldi\/(\d+(\.\d+)?)/i) || versionIdentifier
      }
    }
    else if (sailfish) {
      result = {
        name: 'Sailfish'
      , sailfish: t
      , version: getFirstMatch(/sailfish\s?browser\/(\d+(\.\d+)?)/i)
      }
    }
    else if (/seamonkey\//i.test(ua)) {
      result = {
        name: 'SeaMonkey'
      , seamonkey: t
      , version: getFirstMatch(/seamonkey\/(\d+(\.\d+)?)/i)
      }
    }
    else if (/firefox|iceweasel|fxios/i.test(ua)) {
      result = {
        name: 'Firefox'
      , firefox: t
      , version: getFirstMatch(/(?:firefox|iceweasel|fxios)[ \/](\d+(\.\d+)?)/i)
      }
      if (/\((mobile|tablet);[^\)]*rv:[\d\.]+\)/i.test(ua)) {
        result.firefoxos = t
      }
    }
    else if (silk) {
      result =  {
        name: 'Amazon Silk'
      , silk: t
      , version : getFirstMatch(/silk\/(\d+(\.\d+)?)/i)
      }
    }
    else if (/phantom/i.test(ua)) {
      result = {
        name: 'PhantomJS'
      , phantom: t
      , version: getFirstMatch(/phantomjs\/(\d+(\.\d+)?)/i)
      }
    }
    else if (/slimerjs/i.test(ua)) {
      result = {
        name: 'SlimerJS'
        , slimer: t
        , version: getFirstMatch(/slimerjs\/(\d+(\.\d+)?)/i)
      }
    }
    else if (/blackberry|\bbb\d+/i.test(ua) || /rim\stablet/i.test(ua)) {
      result = {
        name: 'BlackBerry'
      , blackberry: t
      , version: versionIdentifier || getFirstMatch(/blackberry[\d]+\/(\d+(\.\d+)?)/i)
      }
    }
    else if (webos) {
      result = {
        name: 'WebOS'
      , webos: t
      , version: versionIdentifier || getFirstMatch(/w(?:eb)?osbrowser\/(\d+(\.\d+)?)/i)
      };
      if( /touchpad\//i.test(ua) ){
        result.touchpad = t;
      }
    }
    else if (/bada/i.test(ua)) {
      result = {
        name: 'Bada'
      , bada: t
      , version: getFirstMatch(/dolfin\/(\d+(\.\d+)?)/i)
      };
    }
    else if (tizen) {
      result = {
        name: 'Tizen'
      , tizen: t
      , version: getFirstMatch(/(?:tizen\s?)?browser\/(\d+(\.\d+)?)/i) || versionIdentifier
      };
    }
    else if (/qupzilla/i.test(ua)) {
      result = {
        name: 'QupZilla'
        , qupzilla: t
        , version: getFirstMatch(/(?:qupzilla)[\s\/](\d+(?:\.\d+)+)/i) || versionIdentifier
      }
    }
    else if (/chromium/i.test(ua)) {
      result = {
        name: 'Chromium'
        , chromium: t
        , version: getFirstMatch(/(?:chromium)[\s\/](\d+(?:\.\d+)?)/i) || versionIdentifier
      }
    }
    else if (/chrome|crios|crmo/i.test(ua)) {
      result = {
        name: 'Chrome'
        , chrome: t
        , version: getFirstMatch(/(?:chrome|crios|crmo)\/(\d+(\.\d+)?)/i)
      }
    }
    else if (android) {
      result = {
        name: 'Android'
        , version: versionIdentifier
      }
    }
    else if (/safari|applewebkit/i.test(ua)) {
      result = {
        name: 'Safari'
      , safari: t
      }
      if (versionIdentifier) {
        result.version = versionIdentifier
      }
    }
    else if (iosdevice) {
      result = {
        name : iosdevice == 'iphone' ? 'iPhone' : iosdevice == 'ipad' ? 'iPad' : 'iPod'
      }
      // WTF: version is not part of user agent in web apps
      if (versionIdentifier) {
        result.version = versionIdentifier
      }
    }
    else if(/googlebot/i.test(ua)) {
      result = {
        name: 'Googlebot'
      , googlebot: t
      , version: getFirstMatch(/googlebot\/(\d+(\.\d+))/i) || versionIdentifier
      }
    }
    else {
      result = {
        name: getFirstMatch(/^(.*)\/(.*) /),
        version: getSecondMatch(/^(.*)\/(.*) /)
     };
   }

    // set webkit or gecko flag for browsers based on these engines
    if (!result.msedge && /(apple)?webkit/i.test(ua)) {
      if (/(apple)?webkit\/537\.36/i.test(ua)) {
        result.name = result.name || "Blink"
        result.blink = t
      } else {
        result.name = result.name || "Webkit"
        result.webkit = t
      }
      if (!result.version && versionIdentifier) {
        result.version = versionIdentifier
      }
    } else if (!result.opera && /gecko\//i.test(ua)) {
      result.name = result.name || "Gecko"
      result.gecko = t
      result.version = result.version || getFirstMatch(/gecko\/(\d+(\.\d+)?)/i)
    }

    // set OS flags for platforms that have multiple browsers
    if (!result.msedge && (android || result.silk)) {
      result.android = t
    } else if (iosdevice) {
      result[iosdevice] = t
      result.ios = t
    } else if (mac) {
      result.mac = t
    } else if (xbox) {
      result.xbox = t
    } else if (windows) {
      result.windows = t
    } else if (linux) {
      result.linux = t
    }

    // OS version extraction
    var osVersion = '';
    if (result.windowsphone) {
      osVersion = getFirstMatch(/windows phone (?:os)?\s?(\d+(\.\d+)*)/i);
    } else if (iosdevice) {
      osVersion = getFirstMatch(/os (\d+([_\s]\d+)*) like mac os x/i);
      osVersion = osVersion.replace(/[_\s]/g, '.');
    } else if (android) {
      osVersion = getFirstMatch(/android[ \/-](\d+(\.\d+)*)/i);
    } else if (result.webos) {
      osVersion = getFirstMatch(/(?:web|hpw)os\/(\d+(\.\d+)*)/i);
    } else if (result.blackberry) {
      osVersion = getFirstMatch(/rim\stablet\sos\s(\d+(\.\d+)*)/i);
    } else if (result.bada) {
      osVersion = getFirstMatch(/bada\/(\d+(\.\d+)*)/i);
    } else if (result.tizen) {
      osVersion = getFirstMatch(/tizen[\/\s](\d+(\.\d+)*)/i);
    }
    if (osVersion) {
      result.osversion = osVersion;
    }

    // device type extraction
    var osMajorVersion = osVersion.split('.')[0];
    if (
         tablet
      || nexusTablet
      || iosdevice == 'ipad'
      || (android && (osMajorVersion == 3 || (osMajorVersion >= 4 && !mobile)))
      || result.silk
    ) {
      result.tablet = t
    } else if (
         mobile
      || iosdevice == 'iphone'
      || iosdevice == 'ipod'
      || android
      || nexusMobile
      || result.blackberry
      || result.webos
      || result.bada
    ) {
      result.mobile = t
    }

    // Graded Browser Support
    // http://developer.yahoo.com/yui/articles/gbs
    if (result.msedge ||
        (result.msie && result.version >= 10) ||
        (result.yandexbrowser && result.version >= 15) ||
		    (result.vivaldi && result.version >= 1.0) ||
        (result.chrome && result.version >= 20) ||
        (result.firefox && result.version >= 20.0) ||
        (result.safari && result.version >= 6) ||
        (result.opera && result.version >= 10.0) ||
        (result.ios && result.osversion && result.osversion.split(".")[0] >= 6) ||
        (result.blackberry && result.version >= 10.1)
        || (result.chromium && result.version >= 20)
        ) {
      result.a = t;
    }
    else if ((result.msie && result.version < 10) ||
        (result.chrome && result.version < 20) ||
        (result.firefox && result.version < 20.0) ||
        (result.safari && result.version < 6) ||
        (result.opera && result.version < 10.0) ||
        (result.ios && result.osversion && result.osversion.split(".")[0] < 6)
        || (result.chromium && result.version < 20)
        ) {
      result.c = t
    } else result.x = t

    return result
  }

  var bowser = detect(typeof navigator !== 'undefined' ? navigator.userAgent : '')

  bowser.test = function (browserList) {
    for (var i = 0; i < browserList.length; ++i) {
      var browserItem = browserList[i];
      if (typeof browserItem=== 'string') {
        if (browserItem in bowser) {
          return true;
        }
      }
    }
    return false;
  }

  /**
   * Get version precisions count
   *
   * @example
   *   getVersionPrecision("1.10.3") // 3
   *
   * @param  {string} version
   * @return {number}
   */
  function getVersionPrecision(version) {
    return version.split(".").length;
  }

  /**
   * Array::map polyfill
   *
   * @param  {Array} arr
   * @param  {Function} iterator
   * @return {Array}
   */
  function map(arr, iterator) {
    var result = [], i;
    if (Array.prototype.map) {
      return Array.prototype.map.call(arr, iterator);
    }
    for (i = 0; i < arr.length; i++) {
      result.push(iterator(arr[i]));
    }
    return result;
  }

  /**
   * Calculate browser version weight
   *
   * @example
   *   compareVersions(['1.10.2.1',  '1.8.2.1.90'])    // 1
   *   compareVersions(['1.010.2.1', '1.09.2.1.90']);  // 1
   *   compareVersions(['1.10.2.1',  '1.10.2.1']);     // 0
   *   compareVersions(['1.10.2.1',  '1.0800.2']);     // -1
   *
   * @param  {Array<String>} versions versions to compare
   * @return {Number} comparison result
   */
  function compareVersions(versions) {
    // 1) get common precision for both versions, for example for "10.0" and "9" it should be 2
    var precision = Math.max(getVersionPrecision(versions[0]), getVersionPrecision(versions[1]));
    var chunks = map(versions, function (version) {
      var delta = precision - getVersionPrecision(version);

      // 2) "9" -> "9.0" (for precision = 2)
      version = version + new Array(delta + 1).join(".0");

      // 3) "9.0" -> ["000000000"", "000000009"]
      return map(version.split("."), function (chunk) {
        return new Array(20 - chunk.length).join("0") + chunk;
      }).reverse();
    });

    // iterate in reverse order by reversed chunks array
    while (--precision >= 0) {
      // 4) compare: "000000009" > "000000010" = false (but "9" > "10" = true)
      if (chunks[0][precision] > chunks[1][precision]) {
        return 1;
      }
      else if (chunks[0][precision] === chunks[1][precision]) {
        if (precision === 0) {
          // all version chunks are same
          return 0;
        }
      }
      else {
        return -1;
      }
    }
  }

  /**
   * Check if browser is unsupported
   *
   * @example
   *   bowser.isUnsupportedBrowser({
   *     msie: "10",
   *     firefox: "23",
   *     chrome: "29",
   *     safari: "5.1",
   *     opera: "16",
   *     phantom: "534"
   *   });
   *
   * @param  {Object}  minVersions map of minimal version to browser
   * @param  {Boolean} [strictMode = false] flag to return false if browser wasn't found in map
   * @param  {String}  [ua] user agent string
   * @return {Boolean}
   */
  function isUnsupportedBrowser(minVersions, strictMode, ua) {
    var _bowser = bowser;

    // make strictMode param optional with ua param usage
    if (typeof strictMode === 'string') {
      ua = strictMode;
      strictMode = void(0);
    }

    if (strictMode === void(0)) {
      strictMode = false;
    }
    if (ua) {
      _bowser = detect(ua);
    }

    var version = "" + _bowser.version;
    for (var browser in minVersions) {
      if (minVersions.hasOwnProperty(browser)) {
        if (_bowser[browser]) {
          // browser version and min supported version.
          return compareVersions([version, minVersions[browser]]) < 0;
        }
      }
    }

    return strictMode; // not found
  }

  /**
   * Check if browser is supported
   *
   * @param  {Object} minVersions map of minimal version to browser
   * @param  {Boolean} [strictMode = false] flag to return false if browser wasn't found in map
   * @param  {String}  [ua] user agent string
   * @return {Boolean}
   */
  function check(minVersions, strictMode, ua) {
    return !isUnsupportedBrowser(minVersions, strictMode, ua);
  }

  bowser.isUnsupportedBrowser = isUnsupportedBrowser;
  bowser.compareVersions = compareVersions;
  bowser.check = check;

  /*
   * Set our detect method to the main bowser object so we can
   * reuse it to test other user agents.
   * This is needed to implement future tests.
   */
  bowser._detect = detect;

  return bowser
});

(function($){
  UABBTrigger = {

      /**
       * Trigger a hook.
       *
       * @since 1.1.0.3
       * @method triggerHook
       * @param {String} hook The hook to trigger.
       * @param {Array} args An array of args to pass to the hook.
       */
      triggerHook: function( hook, args )
      {
        $( 'body' ).trigger( 'uabb-trigger.' + hook, args );
      },
    
      /**
       * Add a hook.
       *
       * @since 1.1.0.3
       * @method addHook
       * @param {String} hook The hook to add.
       * @param {Function} callback A function to call when the hook is triggered.
       */
      addHook: function( hook, callback )
      {
        $( 'body' ).on( 'uabb-trigger.' + hook, callback );
      },
    
      /**
       * Remove a hook.
       *
       * @since 1.1.0.3
       * @method removeHook
       * @param {String} hook The hook to remove.
       * @param {Function} callback The callback function to remove.
       */
      removeHook: function( hook, callback )
      {
        $( 'body' ).off( 'uabb-trigger.' + hook, callback );
      },
  };
})(jQuery);

jQuery(document).ready(function( $ ) {

    if( typeof bowser !== 'undefined' && bowser !== null ) {

      var uabb_browser   = bowser.name,
          uabb_browser_v = bowser.version,
          uabb_browser_class = uabb_browser.replace(/\s+/g, '-').toLowerCase(),
          uabb_browser_v_class = uabb_browser_class + parseInt( uabb_browser_v );
      
      $('html').addClass(uabb_browser_class).addClass(uabb_browser_v_class);
      
    }

    $('.uabb-row-separator').parents('html').css('overflow-x', 'hidden');
});
var wpAjaxUrl = 'http://192.168.33.10/wp-admin/admin-ajax.php';var flBuilderUrl = 'http://192.168.33.10/wp-content/plugins/bb-plugin/';var FLBuilderLayoutConfig = {
	anchorLinkAnimations : {
		duration 	: 1000,
		easing		: 'swing',
		offset 		: 100
	},
	paths : {
		pluginUrl : 'http://192.168.33.10/wp-content/plugins/bb-plugin/',
		wpAjaxUrl : 'http://192.168.33.10/wp-admin/admin-ajax.php'
	},
	breakpoints : {
		small  : 768,
		medium : 992	},
	waypoint: {
		offset: 80
	}
};
(function($){

	if(typeof FLBuilderLayout != 'undefined') {
		return;
	}

	/**
	 * Helper class with generic logic for a builder layout.
	 *
	 * @class FLBuilderLayout
	 * @since 1.0
	 */
	FLBuilderLayout = {

		/**
		 * Initializes a builder layout.
		 *
		 * @since 1.0
		 * @method init
		 */
		init: function()
		{
			// Destroy existing layout events.
			FLBuilderLayout._destroy();

			// Init CSS classes.
			FLBuilderLayout._initClasses();

			// Init backgrounds.
			FLBuilderLayout._initBackgrounds();

			// Only init if the builder isn't active.
			if ( 0 === $('.fl-builder-edit').length ) {

				// Init module animations.
				FLBuilderLayout._initModuleAnimations();

				// Init anchor links.
				FLBuilderLayout._initAnchorLinks();

				// Init the browser hash.
				FLBuilderLayout._initHash();

				// Init forms.
				FLBuilderLayout._initForms();
			}
		},

		/**
		 * Public method for refreshing Wookmark or MosaicFlow galleries
		 * within an element.
		 *
		 * @since 1.7.4
		 * @method refreshGalleries
		 */
		refreshGalleries: function( element )
		{
			var $element  = 'undefined' == typeof element ? $( 'body' ) : $( element ),
				mfContent = $element.find( '.fl-mosaicflow-content' ),
				wmContent = $element.find( '.fl-gallery' ),
				mfObject  = null;

			if ( mfContent ) {

				mfObject = mfContent.data( 'mosaicflow' );

				if ( mfObject ) {
					mfObject.columns = $( [] );
					mfObject.columnsHeights = [];
					mfContent.data( 'mosaicflow', mfObject );
					mfContent.mosaicflow( 'refill' );
				}
			}
			if ( wmContent ) {
				wmContent.trigger( 'refreshWookmark' );
			}
		},

		/**
		 * Public method for refreshing Masonry within an element
		 *
		 * @since 1.8.1
		 * @method refreshGridLayout
		 */
		refreshGridLayout: function( element )
		{
			var $element 		= 'undefined' == typeof element ? $( 'body' ) : $( element ),
				msnryContent	= $element.find('.masonry');

			if ( msnryContent.length )	{
				msnryContent.masonry('layout');
			}
		},

		/**
		 * Public method for reloading BxSlider within an element
		 *
		 * @since 1.8.1
		 * @method reloadSlider
		 */
		reloadSlider: function( element )
		{
			var $element 	= 'undefined' == typeof element ? $( 'body' ) : $( element ),
				bxContent	= $element.find('.bx-viewport > div').eq(0),
				bxObject   	= null;

			if ( bxContent.length ) {
				bxObject = bxContent.data( 'bxSlider');
				if ( bxObject ) {
					bxObject.reloadSlider();
				}
			}
		},

		/**
		 * Public method for resizing WP audio player
		 *
		 * @since 1.8.2
		 * @method resizeAudio
		 */
		resizeAudio: function( element )
		{
			var $element 	 	= 'undefined' == typeof element ? $( 'body' ) : $( element ),
				audioPlayers 	= $element.find('.wp-audio-shortcode.mejs-audio'),
				player 		 	= null,
				mejsPlayer 	 	= null,
				rail 			= null,
				railWidth 		= 400;

			if ( audioPlayers.length && typeof mejs !== 'undefined' ) {
            	audioPlayers.each(function(){
	            	player 		= $(this);
	            	mejsPlayer 	= mejs.players[player.attr('id')];
	            	rail 		= player.find('.mejs-controls .mejs-time-rail');
	            	var innerMejs = player.find('.mejs-inner'),
	            		total 	  = player.find('.mejs-controls .mejs-time-total');

	            	if ( typeof mejsPlayer !== 'undefined' ) {
	            		railWidth = Math.ceil(player.width() * 0.8);

	            		if ( innerMejs.length ) {

		            		rail.css('width', railWidth +'px!important');
		            		//total.width(rail.width() - 10);

		            		mejsPlayer.options.autosizeProgress = true;

		            		// webkit has trouble doing this without a delay
							setTimeout(function () {
								mejsPlayer.setControlsSize();
							}, 50);

			            	player.find('.mejs-inner').css({
			            		visibility: 'visible',
			            		height: 'inherit'
			            	});
		            	}
		           	}
	            });
	        }
		},

		/**
		 * Public method for preloading WP audio player when it's inside the hidden element
		 *
		 * @since 1.8.2
		 * @method preloadAudio
		 */
		preloadAudio: function(element)
		{
			var $element 	 = 'undefined' == typeof element ? $( 'body' ) : $( element ),
				contentWrap  = $element.closest('.fl-accordion-item'),
				audioPlayers = $element.find('.wp-audio-shortcode.mejs-audio');

			if ( ! contentWrap.hasClass('fl-accordion-item-active') && audioPlayers.find('.mejs-inner').length ) {
				audioPlayers.find('.mejs-inner').css({
					visibility : 'hidden',
					height: 0
				});
			}
		},

		/**
		 * Public method for resizing slideshow momdule within the tab
		 *
		 * @since 1.10.5
		 * @method resizeSlideshow
		 */
		resizeSlideshow: function(){
			if(typeof YUI !== 'undefined') {
				YUI().use('node-event-simulate', function(Y) {
					Y.one(window).simulate("resize");
				});
			}
		},

		/**
		 * Public method for reloading an embedded Google Map within the tabs or hidden element.
		 *
		 * @since 2.2
		 * @method reloadGoogleMap
		 */
		reloadGoogleMap: function(element){
			var $element  = 'undefined' == typeof element ? $( 'body' ) : $( element ),
			    googleMap = $element.find( 'iframe[src*="google.com/maps"]' );

			if ( googleMap.length ) {
			    googleMap.attr( 'src', function(i, val) {
			        return val;
			    });
			}
		},

		/**
		 * Unbinds builder layout events.
		 *
		 * @since 1.0
		 * @access private
		 * @method _destroy
		 */
		_destroy: function()
		{
			var win = $(window);

			win.off('scroll.fl-bg-parallax');
			win.off('resize.fl-bg-video');
		},

		/**
		 * Checks to see if the current device has touch enabled.
		 *
		 * @since 1.0
		 * @access private
		 * @method _isTouch
		 * @return {Boolean}
		 */
		_isTouch: function()
		{
			if(('ontouchstart' in window) || (window.DocumentTouch && document instanceof DocumentTouch)) {
				return true;
			}

			return false;
		},

		/**
		 * Checks to see if the current device is mobile.
		 *
		 * @since 1.7
		 * @access private
		 * @method _isMobile
		 * @return {Boolean}
		 */
		_isMobile: function()
		{
			return /Mobile|Android|Silk\/|Kindle|BlackBerry|Opera Mini|Opera Mobi|webOS/i.test( navigator.userAgent );
		},

		/**
		 * Initializes builder body classes.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initClasses
		 */
		_initClasses: function()
		{
			var body = $( 'body' ),
				ua   = navigator.userAgent;

			// Add the builder body class.
			if ( ! body.hasClass( 'archive' ) && $( '.fl-builder-content-primary' ).length > 0 ) {
				body.addClass('fl-builder');
			}

			// Add the builder touch body class.
			if(FLBuilderLayout._isTouch()) {
				body.addClass('fl-builder-touch');
			}

			// Add the builder mobile body class.
			if(FLBuilderLayout._isMobile()) {
				body.addClass('fl-builder-mobile');
			}

			// IE11 body class.
			if ( ua.indexOf( 'Trident/7.0' ) > -1 && ua.indexOf( 'rv:11.0' ) > -1 ) {
				body.addClass( 'fl-builder-ie-11' );
			}
		},

		/**
		 * Initializes builder node backgrounds that require
		 * additional JavaScript logic such as parallax.
		 *
		 * @since 1.1.4
		 * @access private
		 * @method _initBackgrounds
		 */
		_initBackgrounds: function()
		{
			var win = $(window);

			// Init parallax backgrounds.
			if($('.fl-row-bg-parallax').length > 0 && !FLBuilderLayout._isMobile()) {
				FLBuilderLayout._scrollParallaxBackgrounds();
				FLBuilderLayout._initParallaxBackgrounds();
				win.on('scroll.fl-bg-parallax', FLBuilderLayout._scrollParallaxBackgrounds);
			}

			// Init video backgrounds.
			if($('.fl-bg-video').length > 0) {
				FLBuilderLayout._initBgVideos();
				FLBuilderLayout._resizeBgVideos();
				win.on('resize.fl-bg-video', FLBuilderLayout._resizeBgVideos);
			}
		},

		/**
		 * Initializes all parallax backgrounds in a layout.
		 *
		 * @since 1.1.4
		 * @access private
		 * @method _initParallaxBackgrounds
		 */
		_initParallaxBackgrounds: function()
		{
			$('.fl-row-bg-parallax').each(FLBuilderLayout._initParallaxBackground);
		},

		/**
		 * Initializes a single parallax background.
		 *
		 * @since 1.1.4
		 * @access private
		 * @method _initParallaxBackgrounds
		 */
		_initParallaxBackground: function()
		{
			var row     = $(this),
				content = row.find('> .fl-row-content-wrap'),
				src     = row.data('parallax-image'),
				loaded  = row.data('parallax-loaded'),
				img     = new Image();

			if(loaded) {
				return;
			}
			else if(typeof src != 'undefined') {

				$(img).on('load', function() {
					content.css('background-image', 'url(' + src + ')');
					row.data('parallax-loaded', true);
				});

				img.src = src;
			}
		},

		/**
		 * Fires when the window is scrolled to adjust
		 * parallax backgrounds.
		 *
		 * @since 1.1.4
		 * @access private
		 * @method _scrollParallaxBackgrounds
		 */
		_scrollParallaxBackgrounds: function()
		{
			$('.fl-row-bg-parallax').each(FLBuilderLayout._scrollParallaxBackground);
		},

		/**
		 * Fires when the window is scrolled to adjust
		 * a single parallax background.
		 *
		 * @since 1.1.4
		 * @access private
		 * @method _scrollParallaxBackground
		 */
		_scrollParallaxBackground: function()
		{
			var win     = $(window),
				row     = $(this),
				content = row.find('> .fl-row-content-wrap'),
				speed   = row.data('parallax-speed'),
				offset  = content.offset(),
				yPos    = -((win.scrollTop() - offset.top) / speed);

			content.css('background-position', 'center ' + yPos + 'px');
		},

		/**
		 * Initializes all video backgrounds.
		 *
		 * @since 1.6.3.3
		 * @access private
		 * @method _initBgVideos
		 */
		_initBgVideos: function()
		{
			$('.fl-bg-video').each(FLBuilderLayout._initBgVideo);
		},

		/**
		 * Initializes a video background.
		 *
		 * @since 1.6.3.3
		 * @access private
		 * @method _initBgVideo
		 */
		_initBgVideo: function()
		{
			var wrap   = $( this ),
				width       = wrap.data( 'width' ),
				height      = wrap.data( 'height' ),
				mp4         = wrap.data( 'mp4' ),
				youtube     = wrap.data( 'youtube'),
				vimeo       = wrap.data( 'vimeo'),
				mp4Type     = wrap.data( 'mp4-type' ),
				webm        = wrap.data( 'webm' ),
				webmType    = wrap.data( 'webm-type' ),
				fallback    = wrap.data( 'fallback' ),
				loaded      = wrap.data( 'loaded' ),
				fallbackTag = '',
				videoTag    = null,
				mp4Tag      = null,
				webmTag     = null;

			// Return if the video has been loaded for this row.
			if ( loaded ) {
				return;
			}

			videoTag  = $( '<video autoplay loop muted playsinline></video>' );

			/**
			 * Add poster image (fallback image)
			 */
			if( 'undefined' != typeof fallback && '' != fallback ) {
				videoTag.attr( 'poster', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' )
				videoTag.css( 'background', 'transparent url("' + fallback + '") no-repeat center center' )
				videoTag.css( 'background-size', 'cover' )
				videoTag.css( 'height', '100%' )
			}

			// MP4 Source Tag
			if ( 'undefined' != typeof mp4 && '' != mp4 ) {

				mp4Tag = $( '<source />' );
				mp4Tag.attr( 'src', mp4 );
				mp4Tag.attr( 'type', mp4Type );

				videoTag.append( mp4Tag );
			}
			// WebM Source Tag
			if ( 'undefined' != typeof webm && '' != webm ) {

				webmTag = $( '<source />' );
				webmTag.attr( 'src', webm );
				webmTag.attr( 'type', webmType );

				videoTag.append( webmTag );
			}

			// Check what video player we are going to load in a row
			if ( 'undefined' != typeof youtube && ! FLBuilderLayout._isMobile() ) {
				FLBuilderLayout._initYoutubeBgVideo.apply( this );
			}
			else if ( 'undefined' != typeof vimeo && ! FLBuilderLayout._isMobile() ) {
				FLBuilderLayout._initVimeoBgVideo.apply( this );
			}
			else {
				wrap.append( videoTag );
			}

			// Mark this video as loaded.
			wrap.data('loaded', true);
		},

		/**
		 * Initializes Youtube video background
		 *
		 * @since 1.9
		 * @access private
		 * @method _initYoutubeBgVideo
		 */
		_initYoutubeBgVideo: function()
		{
			var playerWrap	= $(this),
				videoId 		= playerWrap.data('video-id'),
				videoPlayer = playerWrap.find('.fl-bg-video-player'),
				enableAudio = playerWrap.data('enable-audio'),
				audioButton = playerWrap.find('.fl-bg-video-audio'),
				startTime 	= 'undefined' !== typeof playerWrap.data('start') ? playerWrap.data('start') : 0,
				endTime 		= 'undefined' !== typeof playerWrap.data('end') ? playerWrap.data('end') : 0,
				loop 				= 'undefined' !== typeof playerWrap.data('loop') ? playerWrap.data('loop') : 1,
				vidPlayed   = false,
				didUnmute   = false,
				stateCount  = 0,
				player;

			if ( videoId ) {
				FLBuilderLayout._onYoutubeApiReady( function( YT ) {
					setTimeout( function() {

						player = new YT.Player( videoPlayer[0], {
							videoId: videoId,
							events: {
								onReady: function(event) {
									if ( "no" === enableAudio ) {
										event.target.mute();
									}
									else if ("yes" === enableAudio && event.target.isMuted ) {
										event.target.unMute();
									}

									// Store an instance to a parent
									playerWrap.data('YTPlayer', player);
									FLBuilderLayout._resizeYoutubeBgVideo.apply(playerWrap);

									// Queue the video.
									event.target.playVideo();

									if ( audioButton.length > 0 ) {
										audioButton.on( 'click', {button: audioButton, player: player}, FLBuilderLayout._toggleBgVideoAudio );
									}
								},
								onStateChange: function( event ) {
									// Manual check if video is not playable in some browsers.
									// StateChange order: [-1, 3, -1]
									if ( stateCount < 4 ) {
										stateCount++;
									}

									// Comply with the audio policy in some browsers like Chrome and Safari.
									if ( stateCount > 1 && (-1 === event.data || 2 === event.data) && "yes" === enableAudio ) {
										player.mute();
										player.playVideo();
										audioButton.show();
									}

									if ( event.data === YT.PlayerState.ENDED && 1 === loop ) {
										if ( startTime > 0 ) {
											player.seekTo( startTime );
										}
										else {
											player.playVideo();
										}
									}
								},
								onError: function(event) {
									console.info('YT Error: ' + event.data)
									FLBuilderLayout._onErrorYoutubeVimeo(playerWrap)
								}
							},
							playerVars: {
								controls: 0,
								showinfo: 0,
								rel : 0,
								start: startTime,
								end: endTime,
							}
						} );
					}, 1 );
				} );
			}
		},

		/**
		 * On youtube or vimeo error show the fallback image if available.
		 * @since 2.0.7
		 */
		_onErrorYoutubeVimeo: function(playerWrap) {

			fallback = playerWrap.data('fallback') || false
			if( ! fallback ) {
				return false;
			}
			playerWrap.find('iframe').remove()
			fallbackTag = $( '<div></div>' );
			fallbackTag.addClass( 'fl-bg-video-fallback' );
			fallbackTag.css( 'background-image', 'url(' + playerWrap.data('fallback') + ')' );
			playerWrap.append( fallbackTag );
		},

		/**
		 * Check if Youtube API has been downloaded
		 *
		 * @since 1.9
		 * @access private
		 * @method _onYoutubeApiReady
		 * @param  {Function} callback Method to call when YT API has been loaded
		 */
		_onYoutubeApiReady: function( callback ) {
			if ( window.YT && YT.loaded ) {
				callback( YT );
			} else {
				// If not ready check again by timeout..
				setTimeout( function() {
					FLBuilderLayout._onYoutubeApiReady( callback );
				}, 350 );
			}
		},

		/**
		 * Initializes Vimeo video background
		 *
		 * @since 1.9
		 * @access private
		 * @method _initVimeoBgVideo
		 */
		_initVimeoBgVideo: function()
		{
			var playerWrap	= $(this),
				videoId 	= playerWrap.data('video-id'),
				videoPlayer = playerWrap.find('.fl-bg-video-player'),
				enableAudio = playerWrap.data('enable-audio'),
				audioButton = playerWrap.find('.fl-bg-video-audio'),
				player,
				width = playerWrap.outerWidth();

			if ( typeof Vimeo !== 'undefined' && videoId )	{
				player = new Vimeo.Player(videoPlayer[0], {
					id         : videoId,
					loop       : true,
					title      : false,
					portrait   : false,
					background : true,
					autopause  : false,
					muted      : true
				});

				playerWrap.data('VMPlayer', player);
				if ( "no" === enableAudio ) {
					player.setVolume(0);
				}
				else if ("yes" === enableAudio ) {
					// Chrome and Safari have audio policy restrictions for autoplay videos.
					if ( $.browser.safari || $.browser.chrome ) {
						player.setVolume(0);
						audioButton.show();
					}
					else {
						player.setVolume(1);
					}
				}

				player.play().catch(function(error) {
					FLBuilderLayout._onErrorYoutubeVimeo(playerWrap)
				});

				if ( audioButton.length > 0 ) {
					audioButton.on( 'click', {button: audioButton, player: player}, FLBuilderLayout._toggleBgVideoAudio );
				}
			}
		},

		/**
		 * Mute / unmute audio on row's video background.
		 * It works for both Youtube and Vimeo.
		 *
		 * @since 2.1.3
		 * @access private
		 * @method _toggleBgVideoAudio
		 * @param {Object} e Method arguments
		 */
		_toggleBgVideoAudio: function( e ) {
			var player  = e.data.player,
			    control = e.data.button.find('.fl-audio-control');

			if ( control.hasClass( 'fa-volume-off' ) ) {
				// Unmute
				control
					.removeClass( 'fa-volume-off' )
					.addClass( 'fa-volume-up' );
				e.data.button.find( '.fa-times' ).hide();

				if ( 'function' === typeof player.unMute ) {
					player.unMute();
				}
				else {
					player.setVolume( 1 );
				}
			}
			else {
				// Mute
				control
					.removeClass( 'fa-volume-up' )
					.addClass( 'fa-volume-off' );
				e.data.button.find( '.fa-times' ).show();

				if ( 'function' === typeof player.unMute ) {
					player.mute();
				}
				else {
					player.setVolume( 0 );
				}
			}
		},

		/**
		 * Fires when there is an error loading a video
		 * background source and shows the fallback.
		 *
		 * @since 1.6.3.3
		 * @access private
		 * @method _videoBgSourceError
		 * @param {Object} e An event object
		 * @deprecated 2.0.3
		 */
		_videoBgSourceError: function( e )
		{
			var source 		= $( e.target ),
				wrap   		= source.closest( '.fl-bg-video' ),
				vid		    = wrap.find( 'video' ),
				fallback  	= wrap.data( 'fallback' ),
				fallbackTag = '';
			source.remove();

			if ( vid.find( 'source' ).length ) {
				// Don't show the fallback if we still have other sources to check.
				return;
			} else if ( '' !== fallback ) {
				fallbackTag = $( '<div></div>' );
				fallbackTag.addClass( 'fl-bg-video-fallback' );
				fallbackTag.css( 'background-image', 'url(' + fallback + ')' );
				wrap.append( fallbackTag );
				vid.remove();
			}
		},

		/**
		 * Fires when the window is resized to resize
		 * all video backgrounds.
		 *
		 * @since 1.1.4
		 * @access private
		 * @method _resizeBgVideos
		 */
		_resizeBgVideos: function()
		{
			$('.fl-bg-video').each( function() {

				FLBuilderLayout._resizeBgVideo.apply( this );

				if ( $( this ).parent().find( 'img' ).length > 0 ) {
					$( this ).parent().imagesLoaded( $.proxy( FLBuilderLayout._resizeBgVideo, this ) );
				}
			} );
		},

		/**
		 * Fires when the window is resized to resize
		 * a single video background.
		 *
		 * @since 1.1.4
		 * @access private
		 * @method _resizeBgVideo
		 */
		_resizeBgVideo: function()
		{
			if ( 0 === $( this ).find( 'video' ).length && 0 === $( this ).find( 'iframe' ).length ) {
				return;
			}

			var wrap        = $(this),
				wrapHeight  = wrap.outerHeight(),
				wrapWidth   = wrap.outerWidth(),
				vid         = wrap.find('video'),
				vidHeight   = wrap.data('height'),
				vidWidth    = wrap.data('width'),
				newWidth    = wrapWidth,
				newHeight   = Math.round(vidHeight * wrapWidth/vidWidth),
				newLeft     = 0,
				newTop      = 0,
				iframe 		= wrap.find('iframe');

			if ( vid.length ) {
				if(vidHeight === '' || typeof vidHeight === 'undefined' || vidWidth === '' || typeof vidWidth === 'undefined') {
					vid.css({
						'left'      : '0px',
						'top'       : '0px',
						'width'     : newWidth + 'px'
					});

					// Try to set the actual video dimension on 'loadedmetadata' when using URL as video source
					vid.on('loadedmetadata', FLBuilderLayout._resizeOnLoadedMeta);

				}
				else {

					if(newHeight < wrapHeight) {
						newHeight   = wrapHeight;
						newWidth    = Math.round(vidWidth * wrapHeight/vidHeight);
						newLeft     = -((newWidth - wrapWidth)/2);
					}
					else {
						newTop      = -((newHeight - wrapHeight)/2);
					}

					vid.css({
						'left'      : newLeft + 'px',
						'top'       : newTop + 'px',
						'height'    : newHeight + 'px',
						'width'     : newWidth + 'px'
					});
				}
			}
			else if ( iframe.length ) {

				// Resize Youtube video player within iframe tag
				if ( typeof wrap.data('youtube') !== 'undefined' ) {
					FLBuilderLayout._resizeYoutubeBgVideo.apply(this);
				}
			}
		},

		/**
		 * Fires when video meta has been loaded.
		 * This will be Triggered when width/height attributes were not specified during video background resizing.
		 *
		 * @since 1.8.5
		 * @access private
		 * @method _resizeOnLoadedMeta
		 */
		_resizeOnLoadedMeta: function(){
			var video 		= $(this),
				wrapHeight 	= video.parent().outerHeight(),
				wrapWidth 	= video.parent().outerWidth(),
				vidWidth 	= video[0].videoWidth,
				vidHeight 	= video[0].videoHeight,
				newHeight   = Math.round(vidHeight * wrapWidth/vidWidth),
				newWidth    = wrapWidth,
				newLeft     = 0,
				newTop 		= 0;

			if(newHeight < wrapHeight) {
				newHeight   = wrapHeight;
				newWidth    = Math.round(vidWidth * wrapHeight/vidHeight);
				newLeft     = -((newWidth - wrapWidth)/2);
			}
			else {
				newTop      = -((newHeight - wrapHeight)/2);
			}

			video.parent().data('width', vidWidth);
			video.parent().data('height', vidHeight);

			video.css({
				'left'      : newLeft + 'px',
				'top'       : newTop + 'px',
				'width'     : newWidth + 'px',
				'height' 	: newHeight + 'px'
			});
		},

		/**
		 * Fires when the window is resized to resize
		 * a single Youtube video background.
		 *
		 * @since 1.9
		 * @access private
		 * @method _resizeYoutubeBgVideo
		 */
		_resizeYoutubeBgVideo: function()
		{
			var wrap				= $(this),
				wrapWidth 			= wrap.outerWidth(),
				wrapHeight 			= wrap.outerHeight(),
				player 				= wrap.data('YTPlayer'),
				video 				= player ? player.getIframe() : null,
				aspectRatioSetting 	= '16:9', // Medium
				aspectRatioArray 	= aspectRatioSetting.split( ':' ),
				aspectRatio 		= aspectRatioArray[0] / aspectRatioArray[1],
				ratioWidth 			= wrapWidth / aspectRatio,
				ratioHeight 		= wrapHeight * aspectRatio,
				isWidthFixed 		= wrapWidth / wrapHeight > aspectRatio,
				width 				= isWidthFixed ? wrapWidth : ratioHeight,
				height 				= isWidthFixed ? ratioWidth : wrapHeight;

			if ( video ) {
				$(video).width( width ).height( height );
			}
		},

		/**
		 * Initializes module animations.
		 *
		 * @since 1.1.9
		 * @access private
		 * @method _initModuleAnimations
		 */
		_initModuleAnimations: function()
		{
			if(typeof jQuery.fn.waypoint !== 'undefined') {
				$('.fl-animation').each( function() {
					var node = $( this ),
						nodeTop = node.offset().top,
						winHeight = $( window ).height(),
						bodyHeight = $( 'body' ).height(),
						waypoint = FLBuilderLayoutConfig.waypoint,
						offset = '80%';

					if ( typeof waypoint.offset !== undefined ) {
						offset = FLBuilderLayoutConfig.waypoint.offset + '%';
					}

					if ( bodyHeight - nodeTop < winHeight * 0.2 ) {
						offset = '100%';
					}

					node.waypoint({
						offset: offset,
						handler: FLBuilderLayout._doModuleAnimation
					});
				} );
			}
		},

		/**
		 * Runs a module animation.
		 *
		 * @since 1.1.9
		 * @access private
		 * @method _doModuleAnimation
		 */
		_doModuleAnimation: function()
		{
			var module = 'undefined' == typeof this.element ? $(this) : $(this.element),
				delay = parseFloat(module.data('animation-delay')),
				duration = parseFloat(module.data('animation-duration'));

			if ( ! isNaN( duration ) ) {
				module.css( 'animation-duration', duration + 's' );
			}

			if(!isNaN(delay) && delay > 0) {
				setTimeout(function(){
					module.addClass('fl-animated');
				}, delay * 1000);
			} else {
				setTimeout(function(){
					module.addClass('fl-animated');
				}, 1);
			}
		},

		/**
		 * Opens a tab or accordion item if the browser hash is set
		 * to the ID of one on the page.
		 *
		 * @since 1.6.0
		 * @access private
		 * @method _initHash
		 */
		_initHash: function()
		{
			var hash 			= window.location.hash.replace( '#', '' ).split( '/' ).shift(),
				element 		= null,
				tabs			= null,
				responsiveLabel	= null,
				tabIndex		= null,
				label			= null;

			if ( '' !== hash ) {

				try {

					element = $( '#' + hash );

					if ( element.length > 0 ) {

						if ( element.hasClass( 'fl-accordion-item' ) ) {
							setTimeout( function() {
								element.find( '.fl-accordion-button' ).trigger( 'click' );
							}, 100 );
						}
						if ( element.hasClass( 'fl-tabs-panel' ) ) {

							setTimeout( function() {

								tabs 			= element.closest( '.fl-tabs' );
								responsiveLabel = element.find( '.fl-tabs-panel-label' );
								tabIndex 		= responsiveLabel.data( 'index' );
								label 			= tabs.find( '.fl-tabs-labels .fl-tabs-label[data-index=' + tabIndex + ']' );

								if ( responsiveLabel.is( ':visible' ) ) {
									responsiveLabel.trigger( 'click' );
								}
								else {
									label[0].click();
									FLBuilderLayout._scrollToElement( element );
								}

							}, 100 );
						}
					}
				}
				catch( e ) {}
			}
		},

		/**
		 * Initializes all anchor links on the page for smooth scrolling.
		 *
		 * @since 1.4.9
		 * @access private
		 * @method _initAnchorLinks
		 */
		_initAnchorLinks: function()
		{
			$( 'a' ).each( FLBuilderLayout._initAnchorLink );
		},

		/**
		 * Initializes a single anchor link for smooth scrolling.
		 *
		 * @since 1.4.9
		 * @access private
		 * @method _initAnchorLink
		 */
		_initAnchorLink: function()
		{
			var link    = $( this ),
				href    = link.attr( 'href' ),
				loc     = window.location,
				id      = null,
				element = null;
			if ( 'undefined' != typeof href && href.indexOf( '#' ) > -1 && link.closest('svg').length < 1 ) {

				if ( loc.pathname.replace( /^\//, '' ) == this.pathname.replace( /^\//, '' ) && loc.hostname == this.hostname ) {

					try {

						id      = href.split( '#' ).pop();
						// If there is no ID then we have nowhere to look
						// Fixes a quirk in jQuery and FireFox
						if( ! id ) {
							return;
						}
						element = $( '#' + id );

						if ( element.length > 0 ) {
							if ( link.hasClass( 'fl-scroll-link' ) || element.hasClass( 'fl-row' ) || element.hasClass( 'fl-col' ) || element.hasClass( 'fl-module' ) ) {
								$( link ).on( 'click', FLBuilderLayout._scrollToElementOnLinkClick );
							}
							if ( element.hasClass( 'fl-accordion-item' ) ) {
								$( link ).on( 'click', FLBuilderLayout._scrollToAccordionOnLinkClick );
							}
							if ( element.hasClass( 'fl-tabs-panel' ) ) {
								$( link ).on( 'click', FLBuilderLayout._scrollToTabOnLinkClick );
							}
						}
					}
					catch( e ) {}
				}
			}
		},

		/**
		 * Scrolls to an element when an anchor link is clicked.
		 *
		 * @since 1.4.9
		 * @access private
		 * @method _scrollToElementOnLinkClick
		 * @param {Object} e An event object.
		 * @param {Function} callback A function to call when the scroll is complete.
		 */
		_scrollToElementOnLinkClick: function( e, callback )
		{
			var element = $( '#' + $( this ).attr( 'href' ).split( '#' ).pop() );

			FLBuilderLayout._scrollToElement( element, callback );

			e.preventDefault();
		},

		/**
		 * Scrolls to an element.
		 *
		 * @since 1.6.4.5
		 * @access private
		 * @method _scrollToElement
		 * @param {Object} element The element to scroll to.
		 * @param {Function} callback A function to call when the scroll is complete.
		 */
		_scrollToElement: function( element, callback )
		{
			var config  = FLBuilderLayoutConfig.anchorLinkAnimations,
				dest    = 0,
				win     = $( window ),
				doc     = $( document );

			if ( element.length > 0 ) {

				if ( element.offset().top > doc.height() - win.height() ) {
					dest = doc.height() - win.height();
				}
				else {
					dest = element.offset().top - config.offset;
				}

				$( 'html, body' ).animate( { scrollTop: dest }, config.duration, config.easing, function() {

					if ( 'undefined' != typeof callback ) {
						callback();
					}

					if ( undefined != element.attr( 'id' ) ) {

						if ( history.pushState ) {
							history.pushState( null, null, '#' + element.attr( 'id' ) );
						}
						else {
							window.location.hash = element.attr( 'id' );
						}
					}
				} );
			}
		},

		/**
		 * Scrolls to an accordion item when a link is clicked.
		 *
		 * @since 1.5.9
		 * @access private
		 * @method _scrollToAccordionOnLinkClick
		 * @param {Object} e An event object.
		 */
		_scrollToAccordionOnLinkClick: function( e )
		{
			var element = $( '#' + $( this ).attr( 'href' ).split( '#' ).pop() );

			if ( element.length > 0 ) {

				var callback = function() {
					if ( element ) {
						element.find( '.fl-accordion-button' ).trigger( 'click' );
						element = false;
					}
				};

				FLBuilderLayout._scrollToElementOnLinkClick.call( this, e, callback );
			}
		},

		/**
		 * Scrolls to a tab panel when a link is clicked.
		 *
		 * @since 1.5.9
		 * @access private
		 * @method _scrollToTabOnLinkClick
		 * @param {Object} e An event object.
		 */
		_scrollToTabOnLinkClick: function( e )
		{
			var element 		= $( '#' + $( this ).attr( 'href' ).split( '#' ).pop() ),
				tabs			= null,
				label   		= null,
				responsiveLabel = null;

			if ( element.length > 0 ) {

				tabs 			= element.closest( '.fl-tabs' );
				responsiveLabel = element.find( '.fl-tabs-panel-label' );
				tabIndex 		= responsiveLabel.data( 'index' );
				label 			= tabs.find( '.fl-tabs-labels .fl-tabs-label[data-index=' + tabIndex + ']' );

				if ( responsiveLabel.is( ':visible' ) ) {

					var callback = function() {
						if ( element ) {
							responsiveLabel.trigger( 'click' );
							element = false;
						}
					};

					FLBuilderLayout._scrollToElementOnLinkClick.call( this, e, callback );
				}
				else {
					label[0].click();
					FLBuilderLayout._scrollToElement( element );
				}

				e.preventDefault();
			}
		},

		/**
		 * Initializes all builder forms on a page.
		 *
		 * @since 1.5.4
		 * @access private
		 * @method _initForms
		 */
		_initForms: function()
		{
			if ( ! FLBuilderLayout._hasPlaceholderSupport ) {
				$( '.fl-form-field input' ).each( FLBuilderLayout._initFormFieldPlaceholderFallback );
			}

			$( '.fl-form-field input' ).on( 'focus', FLBuilderLayout._clearFormFieldError );
		},

		/**
		 * Checks to see if the current device has HTML5
		 * placeholder support.
		 *
		 * @since 1.5.4
		 * @access private
		 * @method _hasPlaceholderSupport
		 * @return {Boolean}
		 */
		_hasPlaceholderSupport: function()
		{
			var input = document.createElement( 'input' );

			return 'undefined' != input.placeholder;
		},

		/**
		 * Initializes the fallback for when placeholders aren't supported.
		 *
		 * @since 1.5.4
		 * @access private
		 * @method _initFormFieldPlaceholderFallback
		 */
		_initFormFieldPlaceholderFallback: function()
		{
			var field       = $( this ),
				val         = field.val(),
				placeholder = field.attr( 'placeholder' );

			if ( 'undefined' != placeholder && '' === val ) {
				field.val( placeholder );
				field.on( 'focus', FLBuilderLayout._hideFormFieldPlaceholderFallback );
				field.on( 'blur', FLBuilderLayout._showFormFieldPlaceholderFallback );
			}
		},

		/**
		 * Hides a fallback placeholder on focus.
		 *
		 * @since 1.5.4
		 * @access private
		 * @method _hideFormFieldPlaceholderFallback
		 */
		_hideFormFieldPlaceholderFallback: function()
		{
			var field       = $( this ),
				val         = field.val(),
				placeholder = field.attr( 'placeholder' );

			if ( val == placeholder ) {
				field.val( '' );
			}
		},

		/**
		 * Shows a fallback placeholder on blur.
		 *
		 * @since 1.5.4
		 * @access private
		 * @method _showFormFieldPlaceholderFallback
		 */
		_showFormFieldPlaceholderFallback: function()
		{
			var field       = $( this ),
				val         = field.val(),
				placeholder = field.attr( 'placeholder' );

			if ( '' === val ) {
				field.val( placeholder );
			}
		},

		/**
		 * Clears a form field error message.
		 *
		 * @since 1.5.4
		 * @access private
		 * @method _clearFormFieldError
		 */
		_clearFormFieldError: function()
		{
			var field = $( this );

			field.removeClass( 'fl-form-error' );
			field.siblings( '.fl-form-error-message' ).hide();
		}
	};

	/* Initializes the builder layout. */
	$(function(){
		FLBuilderLayout.init();
	});

})(jQuery);

jQuery(document).ready(function( $ ) {
	if (  ! $('html').hasClass('fl-builder-edit') ){

		$('.uabb-modal-parent-wrapper').each(function(){
			$(this).appendTo(document.body);
		});
	}
});

(function($) {
	UABBModalPopup = function( settings )
	{	
		this.settings       = settings;
		this.node           = settings.id;
		this.modal_on       = settings.modal_on;
		this.modal_custom   = settings.modal_custom;
		this.modal_content  = settings.modal_content;
		this.video_autoplay  = settings.video_autoplay;
		this.enable_cookies = settings.enable_cookies;
		this.expire_cookie  = settings.expire_cookie;
		this.esc_keypress   = settings.esc_keypress;
		this.overlay_click  = settings.overlay_click;
		this.responsive_display = settings.responsive_display;
		this.medium_device = settings.medium_device;
		this.small_device = settings.small_device;
	
		this._initModalPopup();
		this._initModalPopupVideo();

		var modal_resize = this;

		$( window ).resize(function() {
			modal_resize._centerModal();
			modal_resize._resizeModalPopup();
		});

	};

	UABBModalPopup.prototype = {
		
			settings		: {},
			node   			: '',
			modal_trigger   : '',
			overlay         : '',
			modal_popup		: '',
			modal_on   		: '',
			modal_custom 	: '',
			modal_content 	: '',
			enable_cookies  : '',
			expire_cookie   : '',
			esc_keypress    : '',
			overlay_click   : '',
			video_autoplay  : 'no',
			responsive_display : '',
			medium_device : '',
			small_device : '',
			
			/**
			 * Initiate animation.
			 *
			 * @since 1.1.0.2
			 * @access private
			 * @method _initAnimations
			 */ 

			_initModalPopup: function() {
					//console.log( this.modal_content );
				$this = this;
				$node_module = $( '.fl-node-'+$this.node );
				$popup_id = $( '.uamodal-'+$this.node );
				
				if ( ( $('html').hasClass('uabb-active-live-preview') || ! $('html').hasClass('fl-builder-edit') ) && this.modal_on == 'custom' && this.modal_custom != '' ) {
					var custom_wrap = $(this.modal_custom);
					
					if ( custom_wrap.length ) {
						custom_wrap.addClass("uabb-modal-action uabb-trigger");
						var data_modal = 'modal-'+this.node;
						custom_wrap.attr( 'data-modal', data_modal );

						$this.modal_trigger = custom_wrap;
						$this.modal_popup   = $( '#modal-' + $this.node );
					 	var	modal_trigger = custom_wrap,
						    modal_close   = $popup_id.find( '.uabb-modal-close' ),
						    modal_popup   = $( '#modal-' + $this.node );
						

						modal_trigger.bind("click", function(){return false;});
						modal_trigger.on( "click", $.proxy( $this._showModalPopup, $this ) );

						modal_close.on( "click", $.proxy( $this._removeModalHandler, $this ) );

						$popup_id.find('.uabb-modal').on( "click", function( e ) {
							if( e.target == this ){
								modal_close.trigger( "click" );
							}
						} );
					} 
				}else if( this.modal_on == 'automatic' ) {
					this.modal_popup = $('#modal-' + this.node );

					var refresh_cookies_name = 'refresh-modal-' + this.node,
						cookies_status = this.enable_cookies;

						if ( cookies_status != 1 && Cookies.get( refresh_cookies_name ) == 'true' ) {
					    	Cookies.remove( refresh_cookies_name );
						}
				}
					
				this.overlay        = $popup_id.find( '.uabb-overlay' );

				$node_module.find( '.uabb-trigger' ).each(function( index ) {
				 	$this.modal_trigger = $(this);
					$this.modal_popup   = $( '#modal-' + $this.node );
				 	var	modal_trigger = $(this),
					    modal_close   = $popup_id.find( '.uabb-modal-close' ),
					    modal_popup   = $( '#modal-' + $this.node );
					

					modal_trigger.bind("click", function(){return false;});
					modal_trigger.on( "click", $.proxy( $this._showModalPopup, $this ) );

					modal_close.on( "click", $.proxy( $this._removeModalHandler, $this ) )

					$popup_id.find('.uabb-modal').on( "click", function( e ) {
						if( e.target == this ){
							modal_close.trigger( "click" );
						}
					} );
						/*function() {
						$this._showModalPopup( this );
					});
					*/

				});

				this._centerModal();
				this._iphonecursorfix();			
			},
			_showAutomaticModalPopup: function() {

				if( ! this._isShowModal() ) {
					return;
				}
				
				jQuery(".uabb-modal-parent-wrapper.uabb-module-content").find(".uabb-modal.uabb-modal-custom").css("pointer-events", "none");
				
				var cookies_name = 'modal-' + this.node,
					refresh_cookies_name = 'refresh-modal-' + this.node,
					cookies_status = this.enable_cookies,
					show_modal = true;

				if ( cookies_status == 1 ) {
					if ( Cookies.get( cookies_name ) == 'true' ) {
						show_modal = false;
					}		
				}else{
					if ( Cookies.get( refresh_cookies_name ) == 'true' ) {
						show_modal = false;
					}	
			    	if ( Cookies.get( cookies_name ) == 'true' ) {
			    		Cookies.remove( cookies_name );
					}
				}		
				
				if ( show_modal == true ) {

					var parent_wrap = $('.fl-node-' + this.node ),
						popup_wrap = $('.uamodal-' + this.node ),
						trigger_args = '.uamodal-' + this.node + ' .uabb-modal-content-data',
						close = popup_wrap.find('.uabb-modal-close' ),
						cookies_days = parseInt( $this.expire_cookie ),
						current_this = this;

					if ( popup_wrap.find( '.uabb-content' ).outerHeight() > $(window).height() ) {
						$('html').addClass('uabb-html-modal');
						popup_wrap.find('.uabb-modal').addClass('uabb-modal-scroll');
					}

					var modal = this.modal_popup;

					if( this.modal_content == 'youtube' || this.modal_content == 'vimeo' ) {
						setTimeout( function() { modal.addClass('uabb-show' ); }, 300 );
					} else {
						modal.addClass('uabb-show' );
					}

					this._videoAutoplay();

				    if ( this.esc_keypress == 1 ) {
						$(document).on('keyup.uabb-modal',function(e) {
							if (e.keyCode == 27) { 
								current_this.modal_popup.removeClass( 'uabb-show' );
								current_this._stopVideo();
								$(document).unbind('keyup.uabb-modal');
								if ( cookies_status == 1 ) {
									Cookies.set( cookies_name, 'true', { expires: cookies_days });
								}else{
									Cookies.set( refresh_cookies_name, 'true' );
								}

								UABBTrigger.triggerHook( 'uabb-modal-after-close', popup_wrap );
							}
						});

				    }


				    if ( this.overlay_click == 1 ) {

						this.overlay.on( 'click', function( ev ) {
							current_this.modal_popup.removeClass( 'uabb-show' );
							current_this._stopVideo();
							if ( cookies_status == 1 ) {
								Cookies.set( cookies_name, 'true', { expires: cookies_days });
							}else{
								Cookies.set( refresh_cookies_name, 'true' );
							}
							
							UABBTrigger.triggerHook( 'uabb-modal-after-close', popup_wrap );
						} );

					}
					/*$this.overlay.addEventListener( 'click', function( ev ) {
						classie.remove( $this.modal_popup, 'uabb-show' );
					});*/
					
					close.on( 'click', function( ev ) {
						ev.preventDefault();
						current_this.modal_popup.removeClass( 'uabb-show' );
						current_this._stopVideo();

						if ( popup_wrap.find( '.uabb-content' ).outerHeight() > $(window).height() ) {
							setTimeout(function() {
								$('html').removeClass('uabb-html-modal');
								popup_wrap.find('.uabb-modal').removeClass('uabb-modal-scroll');
							}, 300);
						}
						if ( cookies_status == 1 ) {
							Cookies.set( cookies_name, 'true', { expires: cookies_days });
						}else{
							Cookies.set( refresh_cookies_name, 'true' );
						}

						UABBTrigger.triggerHook( 'uabb-modal-after-close', popup_wrap );
					} );

					inner_content_close = popup_wrap.find( '.uabb-close-modal' );
					if ( inner_content_close.length  ) {
						inner_content_close.on( 'click',function(){
							current_this.modal_popup.removeClass( 'uabb-show' );
							current_this._stopVideo();
							if ( cookies_status == 1 ) {
								Cookies.set( cookies_name, 'true', { expires: cookies_days });
							}else{
								Cookies.set( refresh_cookies_name, 'true' );
							}

							UABBTrigger.triggerHook( 'uabb-modal-after-close', popup_wrap );
						});
					}

					/*close.addEventListener( 'click', function( ev ) {
						//console.log( hasPerspective );
						
						classie.remove( $this.modal_popup, 'uabb-show' );
						// console.log( 'Close frontend' );
						
					
					});*/
					UABBTrigger.triggerHook( 'uabb-modal-click', trigger_args );
				}
			},
			_showModalPopup: function() {

				if ( $('html').hasClass('fl-builder-edit') && !$('html').hasClass('uabb-active-live-preview') ) {
					return;
				}

				if( ! this._isShowModal() ) {
					return;
				}

				this._videoAutoplay();

				var active_modal = $('.fl-node-' + this.node ),
				    active_popup = $('.uamodal-' + this.node ),
				    trigger_args = '.uamodal-' + this.node + ' .uabb-modal-content-data';

				if ( active_popup.find( '.uabb-content' ).outerHeight() > $(window).height() ) {
					$('html').addClass('uabb-html-modal');
					active_popup.find('.uabb-modal').addClass('uabb-modal-scroll');
				}

				jQuery(".uabb-modal-title-wrap").siblings(".uabb-modal-close").css("top", "0");

				var modal = $( '#modal-' + this.node );

				if( this.modal_content == 'youtube' || this.modal_content == 'vimeo' ) {
					setTimeout( function() { modal.addClass('uabb-show' ); }, 300 );
				} else {
					modal.addClass('uabb-show' );
				}

				if ( this.overlay_click == 1) {
					this.overlay.on( 'click',$.proxy( this._removeModalHandler, this ) );
				}
				//this.overlay.addEventListener( 'click', this._removeModalHandler );
				current_this = this;
				if( this.modal_trigger.hasClass('uabb-setperspective' ) ) {
					setTimeout( function() {
						current_this.modal_trigger.addClass('uabb-perspective' );
					}, 25 );
				}

				if ( this.esc_keypress == 1 ) {
					$(document).on('keyup.uabb-modal',function(e) {
						if (e.keyCode == 27) { 
							current_this._removeModalHandler();
						}
					});
				}

				inner_content_close = active_popup.find( '.uabb-close-modal' );
				if ( inner_content_close.length  ) {
					inner_content_close.on( 'click',$.proxy( this._removeModalHandler, this ) );
				}

				UABBTrigger.triggerHook( 'uabb-modal-click', trigger_args );
			},
			_removeModal: function( hasPerspective ) {
				var active_modal = $('.fl-node-' + this.node ),
				    active_popup = $('.uamodal-' + this.node ) ;
				
				this.modal_popup.removeClass('uabb-show' );

				this._stopVideo();
				/*if ( this.modal_content == 'youtube' || this.modal_content == 'vimeo' || this.modal_content == 'video' ) {

					var modal_iframe 		= active_popup.find( 'iframe' ),
						modal_src 			= modal_iframe.attr( "src" ).replace("&autoplay=1", "");
						
					    modal_iframe.attr( "src", '' );
					    modal_iframe.attr( "src", modal_src );
				}*/
				
				if( hasPerspective ) {
					this.modal_trigger.removeClass( 'uabb-perspective' );
				}
				
				setTimeout(function() {
					$('html').removeClass('uabb-html-modal');
					active_popup.find('.uabb-modal').removeClass('uabb-modal-scroll');
				}, 300);

				$(document).unbind('keyup.uabb-modal');

				UABBTrigger.triggerHook( 'uabb-modal-after-close', active_popup );

			},
			_removeModalHandler: function( ev ) {
				this._removeModal( this.modal_trigger.hasClass('uabb-setperspective' ) ); 
			},
			_resizeModalPopup: function() {
				var active_modal = $('.fl-node-' + this.node ),
				    active_popup = $('.uamodal-' + this.node );
				if (  active_popup.find('.uabb-modal').hasClass('uabb-show') ) {
					if ( active_popup.find( '.uabb-content' ).outerHeight() > $(window).height() ) {
						$('html').addClass('uabb-html-modal');
						active_popup.find('.uabb-modal').addClass('uabb-modal-scroll');
					}else{
						$('html').removeClass('uabb-html-modal');
						active_popup.find('.uabb-modal').removeClass('uabb-modal-scroll');
					}
				}
			},
			_videoAutoplay: function() {
				var active_modal = $('.fl-node-' + this.node ),
				    active_popup = $('.uamodal-' + this.node );


				if ( this.video_autoplay === 'yes' && ( this.modal_content === 'youtube' || this.modal_content === 'vimeo' ) ) {

					var vid_id = $( '#modal-' + this.node ).find( '.uabb-video-player' ).data( 'id' );

					if( 0 === $( '#modal-' + this.node ).find( '.uabb-video-player iframe' ).length ) {

						$( '#modal-' + this.node ).find( '.uabb-video-player div[data-id=' + vid_id + ']' ).trigger( 'click' );

					}
					else{
						var modal_iframe 		= active_popup.find( 'iframe' ),
						modal_src 				= modal_iframe.attr( "src" ) + '&autoplay=1';

						modal_iframe.attr( "src",  modal_src );
					}
					
				}
				if ( 'iframe' === this.modal_content ) {

					if( active_popup.find( '.uabb-modal-content-data iframe' ).length === 0 ) {

						var src = active_popup.find( '.uabb-modal-content-type-iframe' ).data( 'src' );

						var iframe = document.createElement( "iframe" );
									iframe.setAttribute( "src", src );
									iframe.setAttribute( "style", "display:none;" );
									iframe.setAttribute( "frameborder", "0" );
									iframe.setAttribute( "allowfullscreen", "1" );
									iframe.setAttribute( "width", "100%" );
									iframe.setAttribute( "height", "100%" );
									iframe.setAttribute( "class", "uael-content-iframe" );

									active_popup.find( '.uabb-modal-content-data' ).html( iframe );
									active_popup.find( '.uabb-modal-content-data' ).append( '<div class="uabb-loader"><div class="uabb-loader-1"></div><div class="uabb-loader-2"></div><div class="uabb-loader-3"></div></div>' );

						var id = this.node;

						iframe.onload = function() {
							window.parent.jQuery( document ).find('#modal-' + id + ' .uabb-loader' ).fadeOut();
							this.style.display='block';
						};

					}
				}
			},
			_stopVideo: function() {
				var active_modal = $('.fl-node-' + this.node ),
				    active_popup = $('.uamodal-' + this.node );

				if ( this.modal_content != 'photo' ) {

					var modal_iframe 		= active_popup.find( 'iframe' ),
						modal_video_tag 	= active_popup.find( 'video' );

						if ( modal_iframe.length ) {
							var modal_src 			= modal_iframe.attr( "src" ).replace("&autoplay=1", "");
							modal_iframe.attr( "src", '' );
						    modal_iframe.attr( "src", modal_src );
						}else if ( modal_video_tag.length ) {
				        	modal_video_tag[0].pause();
							modal_video_tag[0].currentTime = 0;
						}
				}
			},
			_isShowModal: function() {

				if ( this.responsive_display != '' ) {

					var current_window_size = $(window).width(),
                        medium_device = parseInt( this.medium_device ),
                        small_device = parseInt( this.small_device );

					if ( this.responsive_display == 'desktop' && current_window_size > medium_device ) {
						
						return true;
					}else if( this.responsive_display == 'desktop-medium' && current_window_size > small_device ){
						
						return true;
					}else if( this.responsive_display == 'medium' && current_window_size < medium_device && current_window_size > small_device ){

						return true;
					}else if( this.responsive_display == 'medium-mobile' && current_window_size < medium_device ){

						return true;
					}else if( this.responsive_display == 'mobile' && current_window_size < small_device ){

						return true;
					}else{

						return false;
					}
				}

				return true;
			},
			_centerModal: function () {

				$this 		 = this;
				popup_wrap = $('.uamodal-' + this.node );
				modal_popup  = '#modal-' + $this.node;
				node 		 = '.uamodal-' + $this.node;

				if ( $( '#modal-' + this.node ).hasClass('uabb-center-modal') ) {
		        	$( '#modal-' + this.node ).removeClass('uabb-center-modal');

				}

				if( $( '#modal-' + this.node + '.uabb-show' ).outerHeight() != null ) {

					var top_pos  = (($(window).height() - $( '#modal-' + this.node + '.uabb-show' ).outerHeight()) / 2);
					
					if ( popup_wrap.find( '.uabb-content' ).outerHeight() > $(window).height() ) {
	   		            $(node).find( modal_popup ).css( 'top', '0' );
						$(node).find( modal_popup ).css( 'transform', 'none' );
					} else {
						$(node).find( modal_popup ).css( 'top', + top_pos +'px' );
						$(node).find( modal_popup ).css( 'transform', 'none' );
					}
					
				} else {
					
					if ( popup_wrap.find( '.uabb-content' ).outerHeight() > $(window).height() ) {
	   		            $(node).find( modal_popup ).css( 'top', '0' );
						$(node).find( modal_popup ).css( 'transform', 'none' );
					} else {
						$(node).find( modal_popup ).css( 'top', '50%' );
						$(node).find( modal_popup ).css( 'transform', 'translateY(-50%)' );
					}
				}

			},
			_iphonecursorfix: function () {

				$this 		 = this;
				popup_wrap = $('.uamodal-' + this.node );
				modal_popup  = '#modal-' + $this.node;
				node 		 = '.uamodal-' + $this.node;

				iphone = (( navigator.userAgent.match(/iPhone/i) == 'iPhone' ) ? 'iphone' : '' );
				ipod = (( navigator.userAgent.match(/iPod/i) == 'iPod' ) ? 'ipod' : '' );

				jQuery('html').addClass(iphone).addClass(ipod);
				jQuery( 'html.iphone .uabb-modal-action-wrap .uabb-module-content .uabb-button.uabb-trigger, html.ipod .uabb-modal-action-wrap .uabb-module-content .uabb-button.uabb-trigger' ).click ( function() {
				    jQuery('body').css( 'position', 'fixed' );
				});

				if( this.overlay_click == 1 ) {
					jQuery(document).on('click', '.uabb-overlay', function() {  
					   if( jQuery('html').hasClass('iphone') || jQuery('html').hasClass('ipod') ) {
					      jQuery('body').css( 'position', 'relative' );
					   }
					});
				}

				jQuery(document).on('click', '.uabb-modal-close', function() {  
				   if( jQuery('html').hasClass('iphone') || jQuery('html').hasClass('ipod') ) {
				      jQuery('body').css( 'position', 'relative' );
				   }
				});
			},
			_initModalPopupVideo : function(){

				var play_icon = 'fa fa-play-circle';

				if ( this.modal_content === 'youtube' || this.modal_content === 'vimeo' ) {

					if( 0 === $( '.uabb-video-player iframe' ).length ){

						$( '.uabb-video-player' ).each( function( index, value ) {

							var div = $( "<div/>" );
								div.attr( 'data-id', $( this ).data( 'id' ) );
								div.attr( 'data-src', $( this ).data( 'src' ) );
								div.attr( 'data-append', $( this ).data( 'append' ) );
								div.html( '<img src="' + $( this ).data( 'thumb' ) + '"><div class="play ' + play_icon + '"></div>' );

								div.on( "click",function(){

									var iframe 	= document.createElement( "iframe" );
									var src 	= this.dataset.src;
									var append 	= this.dataset.append;
        							var url 	= '';

									 if ( 'youtube' === src ) {
        								url = 'https://www.youtube.com/embed/' + this.dataset.id + this.dataset.append + '&autoplay=1';
        							} else {
        								url = 'https://player.vimeo.com/video/' + this.dataset.id + this.dataset.append + '&autoplay=1';
        							}
									iframe.setAttribute( "src", url );
									iframe.setAttribute( "frameborder", "0" );
									iframe.setAttribute( "allowfullscreen", "1" );
									this.parentNode.replaceChild( iframe, this );
								});
								$( this ).html( div );
						});
					}	
				}
			}
	}

})(jQuery);

jQuery(document).ready(function($){
	if( 'function' == typeof UABBModalPopup ) {
		var UABBModalPopup_5dd3cb7cc170f =  new UABBModalPopup({
			id: '5dd3cb7cc170f',
			modal_on: 'text',
			modal_custom: '',
			modal_content: 'content',
			video_autoplay: 'no',
			enable_cookies: '0',
			expire_cookie: '30',
			esc_keypress: '1',
			overlay_click: '1',
			responsive_display: '',
			medium_device: '992',
			small_device: '768',
		});

		
		/**/
		
					$( ".uabb-live-preview-button" ).click(function() {
				setTimeout(function(){
					UABBModalPopup_5dd3cb7cc170f._initModalPopup();
				}, 200);
			});
		
		//console.log( e );
		//console.log( UABBModalPopup_5dd3cb7cc170f );
	}
});
; if(typeof FLBuilder !== 'undefined' && typeof FLBuilder._renderLayoutComplete !== 'undefined') FLBuilder._renderLayoutComplete();		;(function($){
			var form = $('.fl-builder-settings'),
				gradient_type = form.find( 'input[name=uabb_row_gradient_type]' );

			$( document ).on( 'change', 'input[name=uabb_row_radial_advance_options], input[name=uabb_row_linear_advance_options], input[name=uabb_row_gradient_type], select[name=bg_type]', function() {
				var form        = $('.fl-builder-settings'),
					background_type       = form.find( 'select[name=bg_type]' ).val(),
					linear_direction      = form.find( 'select[name=uabb_row_uabb_direction]' ).val(),
					linear_advance_option = form.find( 'input[name=uabb_row_linear_advance_options]:checked' ).val(),
					radial_advance_option = form.find( 'input[name=uabb_row_radial_advance_options]:checked' ).val(),
					gradient_type         = form.find( 'input[name=uabb_row_gradient_type]:checked' ).val();				
				if( background_type == 'uabb_gradient' ) {

					if( gradient_type == 'radial' ) {

						setTimeout( function() { 
							form.find('#fl-field-uabb_row_linear_direction').hide();
							form.find('#fl-field-uabb_row_linear_gradient_primary_loc').hide();
							form.find('#fl-field-uabb_row_linear_gradient_secondary_loc').hide();
						}, 1);

						if( radial_advance_option == 'yes' ) {
							form.find('#fl-field-uabb_row_radial_gradient_primary_loc').show();
							form.find('#fl-field-uabb_row_radial_gradient_secondary_loc').show();
						}
					}

					if( gradient_type == 'linear' ) {

						setTimeout( function() { 
							form.find('#fl-field-uabb_row_radial_gradient_primary_loc').hide();
							form.find('#fl-field-uabb_row_radial_gradient_secondary_loc').hide();
						}, 1);

						if( linear_direction == 'custom' ) {
							form.find('#fl-field-uabb_row_linear_direction').show();
						}

						if( linear_advance_option == 'yes' ) {
							form.find('#fl-field-uabb_row_linear_gradient_primary_loc').show();
							form.find('#fl-field-uabb_row_linear_gradient_secondary_loc').show();
						}
					}   
				}
			});

		})(jQuery);		
			;(function($){
			var form = $('.fl-builder-settings'),
				gradient_type = form.find( 'input[name=uabb_col_gradient_type]' );

			$( document ).on( 'change', ' input[name=uabb_col_radial_advance_options], input[name=uabb_col_linear_advance_options], input[name=uabb_col_gradient_type], select[name=bg_type]', function() {
				var form        = $('.fl-builder-settings'),
					background_type       = form.find( 'select[name=bg_type]' ).val(),
					linear_direction      = form.find( 'select[name=uabb_col_uabb_direction]' ).val(),
					linear_advance_option = form.find( 'input[name=uabb_col_linear_advance_options]:checked' ).val(),
					radial_advance_option = form.find( 'input[name=uabb_col_radial_advance_options]:checked' ).val(),
					gradient_type         = form.find( 'input[name=uabb_col_gradient_type]:checked' ).val();		
				if( background_type == 'uabb_gradient' ) {

					if( gradient_type == 'radial' ) {
						setTimeout( function() {                        
							form.find('#fl-field-uabb_col_linear_direction').hide();
							form.find('#fl-field-uabb_col_linear_gradient_primary_loc').hide();
							form.find('#fl-field-uabb_col_linear_gradient_secondary_loc').hide();
						}, 1);    

						if( radial_advance_option == 'yes' ) {
							form.find('#fl-field-uabb_col_radial_gradient_primary_loc').show();
							form.find('#fl-field-uabb_col_radial_gradient_secondary_loc').show();
						}
					}

					if( gradient_type == 'linear' ) {
						setTimeout( function() { 
								form.find('#fl-field-uabb_col_radial_gradient_primary_loc').hide();
								form.find('#fl-field-uabb_col_radial_gradient_secondary_loc').hide();
						}, 1);

						if( linear_direction == 'custom' ) {
							form.find('#fl-field-uabb_col_linear_direction').show();
						}

						if( linear_advance_option == 'yes' ) {
							form.find('#fl-field-uabb_col_linear_gradient_primary_loc').show();
							form.find('#fl-field-uabb_col_linear_gradient_secondary_loc').show();
						}
					}   
				}
			});

		})(jQuery);  
	