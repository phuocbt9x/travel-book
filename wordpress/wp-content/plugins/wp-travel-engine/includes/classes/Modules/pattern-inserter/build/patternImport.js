/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "../../../../node_modules/classnames/index.js":
/*!****************************************************!*\
  !*** ../../../../node_modules/classnames/index.js ***!
  \****************************************************/
/***/ ((module, exports) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = '';

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (arg) {
				classes = appendClass(classes, parseValue(arg));
			}
		}

		return classes;
	}

	function parseValue (arg) {
		if (typeof arg === 'string' || typeof arg === 'number') {
			return arg;
		}

		if (typeof arg !== 'object') {
			return '';
		}

		if (Array.isArray(arg)) {
			return classNames.apply(null, arg);
		}

		if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
			return arg.toString();
		}

		var classes = '';

		for (var key in arg) {
			if (hasOwn.call(arg, key) && arg[key]) {
				classes = appendClass(classes, key);
			}
		}

		return classes;
	}

	function appendClass (value, newClass) {
		if (!newClass) {
			return value;
		}
	
		if (value) {
			return value + ' ' + newClass;
		}
	
		return value + newClass;
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./src/components/FavButton/FavButton.jsx":
/*!************************************************!*\
  !*** ./src/components/FavButton/FavButton.jsx ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! components */ "./src/components/index.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




function FavButton({
  icon,
  handleClick
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    className: "cw-pattern-library__design-fav transform-scale",
    onClick: handleClick,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Favourite', 'cw-pattern-import')
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_1__.Icon, {
    icon: icon
  }));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (FavButton);

/***/ }),

/***/ "./src/components/NoticeModal/NoticeModal.jsx":
/*!****************************************************!*\
  !*** ./src/components/NoticeModal/NoticeModal.jsx ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);



function NoticeModal({
  closeModal
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Modal, {
    onRequestClose: closeModal
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('The selected pattern requires plugins, which unfortunately the current user does not have the permission to install and activate. Please inform the admin to provide the necessary permission or install and activate the required plugins.', 'cw-pattern-import')));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (NoticeModal);

/***/ }),

/***/ "./src/components/PatternList/PatternList.jsx":
/*!****************************************************!*\
  !*** ./src/components/PatternList/PatternList.jsx ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! classnames */ "../../../../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var utils_hooks__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! utils/hooks */ "./src/utils/hooks/hooks.js");
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! components */ "./src/components/index.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var utils__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! utils */ "./src/utils/index.js");








function PatternList({
  designs,
  selectedLayout
}) {
  const [favPattern, setFavPattern] = (0,utils_hooks__WEBPACK_IMPORTED_MODULE_4__.useLocalStorage)('cw-fav-patterns', [], true);
  const [designsList, setDesignsList] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)([]);
  const [isOpen, setOpen] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(false);
  const [requiredPlugins, setRequiredPlugins] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)([]);
  const [designToImport, setDesignToImport] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(null);
  const [isModalOpen, setIsPreviewOpen] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(false);
  const [iframeLink, setIframeLink] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(null);
  const [disabledButtons, setDisabledButtons] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)({});
  const {
    onClose: closePatternLibraryModal
  } = (0,utils_hooks__WEBPACK_IMPORTED_MODULE_4__.useModalContext)();
  function closeModal() {
    setOpen(false);
  }
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useEffect)(() => {
    if (designs.length !== 0) {
      const designsList = designs.map(({
        title,
        featured_media,
        id,
        permalink,
        ...data
      }) => {
        let favIcon = 'heart_outline';
        favPattern.find(pattern => {
          if (pattern.id === id) {
            return favIcon = 'heart_fill';
          }
        });
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
          key: id,
          className: "cw-pattern-library__design-item"
        }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.FavButton, {
          icon: favIcon,
          handleClick: () => toggleFav(id)
        }), featured_media !== undefined ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          className: "cw-pattern-library__design-item-img"
        }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
          src: `${featured_media}`,
          loading: "lazy",
          className: ""
        })) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          className: "cw-pattern-library__design-item-img no-featured-img"
        }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.Icon, {
          icon: "img"
        })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          className: "cw-pattern-library__design-item-info"
        }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.VisuallyHidden, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, title)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
          className: "cw-pattern-library__design-preview transform-scale",
          onClick: () => handlePreviewModal(permalink),
          "aria-label": (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Preview', 'cw-pattern-import')
        }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.Icon, {
          icon: "eye"
        }), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Preivew', 'cw-pattern-import')), data.meta.freevspro === '1' && cwAdmin.license_key === "valid" ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ImportButton, {
          id: id
        }) : data.meta.freevspro === '' ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ImportButton, {
          id: id
        }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
          className: "cw-pattern-library__design-btn cw-pattern-pro cw-pattern-library-btn-is-primary transform-scale",
          href: "https://wptravelengine.com/pattern-engine/?utm_source=free_plugin&utm_medium=pattern_importer&utm_campaign=upgrade_to_pro"
        }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Buy Pro', 'cw-pattern-import'), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.Icon, {
          icon: "crown"
        }))));
      });
      setDesignsList(designsList);
    }
  }, [designs, disabledButtons]);
  if (designs.length === 0) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
      className: "cw-pattern-library__help"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('No patterns found', 'cw-pattern-import'));
  }
  const designListClass = classnames__WEBPACK_IMPORTED_MODULE_3___default()('cw-pattern-library__design-list', selectedLayout && `cw-pattern-library__design-list-col${selectedLayout}`);
  function handleInsert(selectedId) {
    designs.map(design => {
      if (design.id === selectedId) {
        // Check if required plugin is installed and activated (actiePluginData is the localized variable that has the list of installed and activated plugins)
        setDisabledButtons(prevState => ({
          ...prevState,
          [selectedId]: true
        }));
        const filteredRequiredPlugins = design.meta.required_plugins.filter(requiredPlugin => !(0,utils__WEBPACK_IMPORTED_MODULE_7__.objectExistsInArray)(requiredPlugin, cwAdmin.activePlugin));
        setRequiredPlugins(filteredRequiredPlugins);
        if (filteredRequiredPlugins.length > 0) {
          setOpen(true);
          setDesignToImport(design);
          setDisabledButtons(prevState => ({
            ...prevState,
            [selectedId]: false
          }));
          return;
        }
        (0,utils__WEBPACK_IMPORTED_MODULE_7__.insertPattern)({
          design: design,
          closePatternLibraryModal: closePatternLibraryModal
        }).then(() => {
          setDisabledButtons(prevState => ({
            ...prevState,
            [selectedId]: false
          }));
        }).catch(() => {
          setDisabledButtons(prevState => ({
            ...prevState,
            [selectedId]: false
          }));
        });
      }
    });
  }
  function toggleFav(favId) {
    setFavPattern(prevPatterns => {
      const designIndex = prevPatterns.findIndex(design => design.id === favId);
      if (designIndex !== -1) {
        // pattern is already in the favorites list, remove it
        return prevPatterns.filter((_, index) => index !== designIndex);
      }
      // pattern in not in the favorites list, add it
      return [...prevPatterns, designs.find(design => design.id === favId)];
    });
  }
  function ImportButton({
    id
  }) {
    const isDisabled = disabledButtons[id] || false;
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
      className: "cw-pattern-library__design-btn cw-pattern-free transform-scale cw-pattern-library-btn-is-primary",
      onClick: () => handleInsert(id),
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Import', 'cw-pattern-import'),
      showTooltip: false,
      isBusy: isDisabled
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Import', 'cw-pattern-import'), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.Icon, {
      icon: "down_arrow"
    }));
  }
  function handlePreviewModal(permalink) {
    setIsPreviewOpen(true);
    setIframeLink(permalink);
  }
  function closePreviewModal() {
    setIsPreviewOpen(false);
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: designListClass
  }, designsList), isOpen && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.RequiredPluginsModal, {
    closeModal: closeModal,
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Required Plugins', 'cw-pattern-import'),
    plugins: requiredPlugins,
    design: designToImport
  }), isModalOpen && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.PreviewModal, {
    closeModal: closePreviewModal,
    patternLink: iframeLink
  }));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PatternList);

/***/ }),

/***/ "./src/components/PreviewModal/PreviewModal.jsx":
/*!******************************************************!*\
  !*** ./src/components/PreviewModal/PreviewModal.jsx ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! components */ "./src/components/index.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);




function PreviewModal({
  closeModal,
  patternLink
}) {
  const [iframeWidth, setIframeWidth] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)('100%');
  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(true);
  function handleWindowWidth(val) {
    if (val === 'desktop') {
      setIframeWidth('100%');
    } else if (val === 'tablet') {
      setIframeWidth('768px');
    } else if (val === 'mobile') {
      setIframeWidth('500px');
    }
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Modal, {
    onRequestClose: closeModal,
    className: "cw-pattern-library-preview",
    isFullScreen: true,
    shouldCloseOnClickOutside: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library-preview-header"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_2__.SegmentedControl, {
    name: "group-1",
    callback: handleWindowWidth,
    defaultIndex: 0,
    controlRef: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useRef)(),
    segments: [{
      label: 'Desktop',
      value: 'desktop',
      icon: 'desktop',
      ref: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useRef)()
    }, {
      label: 'Tablet',
      value: 'tablet',
      icon: 'tablet',
      ref: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useRef)()
    }, {
      label: 'Mobile',
      value: 'mobile',
      icon: 'mobile',
      ref: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useRef)()
    }]
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library-preview-wrap",
    style: {
      '--iframe-width': iframeWidth
    }
  }, isLoading ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Spinner, null) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("iframe", {
    src: patternLink,
    loading: "lazy",
    allowFullScreen: true,
    allowtransparency: "true",
    style: {
      border: 0,
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      transition: '0.3s'
    },
    referrerPolicy: "no-referrer-when-downgrade",
    className: "cw-pattern-library-preview-iframe",
    title: "Pattern preivew",
    onLoad: () => setIsLoading(false)
  }))));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PreviewModal);

/***/ }),

/***/ "./src/components/RequiredPluginsModal/RequiredPluginsModal.jsx":
/*!**********************************************************************!*\
  !*** ./src/components/RequiredPluginsModal/RequiredPluginsModal.jsx ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! utils */ "./src/utils/index.js");
/* harmony import */ var utils_hooks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! utils/hooks */ "./src/utils/hooks/hooks.js");






function RequiredPluginsModal({
  closeModal,
  title,
  plugins,
  design,
  ...props
}) {
  const [buttonState, setButtonState] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)('install');
  const [activateUrls, setActivateUrls] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)([]);
  const {
    onClose: closePatternLibraryModal
  } = (0,utils_hooks__WEBPACK_IMPORTED_MODULE_5__.useModalContext)();
  const pluginsList = plugins.map((plugin, index) => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
      key: index,
      className: "cw-pattern-library-plugins-item"
    }, plugin.name);
  });

  // Memoize the pluginsToInstall array to prevent unnecessary re-renders
  const pluginsToInstall = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useMemo)(() => {
    if (cwAdmin.userCan.installPlugins) {
      return plugins.filter(plugin => !(0,utils__WEBPACK_IMPORTED_MODULE_4__.objectExistsInArray)(plugin, cwAdmin.inactive));
    }
    return [];
  }, [plugins]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    if (cwAdmin.userCan.installPlugins && pluginsToInstall.length === 0) {
      const pluginsToActivate = cwAdmin.inactive.filter(plugin => (0,utils__WEBPACK_IMPORTED_MODULE_4__.objectExistsInArray)(plugin, plugins));
      const urls = pluginsToActivate.map(plugin => plugin.url).map(url => url.replace(/&amp;/g, '&'));
      setActivateUrls(prevUrls => [...prevUrls, ...urls]);
      setButtonState('activate');
    }
  }, [pluginsToInstall, plugins]);
  function installPlugins(plugins) {
    if (!cwAdmin.userCan.installPlugins) {
      alert((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('The selected pattern requires plugins, which unfortunately the current user does not have the permission to install and activate. Please inform the admin to provide the necessary permission or install and activate the required plugins.', 'cw-pattern-import'));
      return;
    }
    if (!wp) {
      return;
    }
    setButtonState('installing');
    // Map each plugin installation to a promise
    const installPromises = plugins.map(plugin => {
      return new Promise((resolve, reject) => {
        wp.updates.installPlugin({
          slug: plugin.slug,
          success: response => {
            setActivateUrls(prevUrls => [...prevUrls, response.activateUrl]);
            resolve();
          },
          // Resolve the promise on success
          error: error => console.error(error) // Reject the promise on error
        });
      });
    });

    // Wait for all promises to resolve
    Promise.all(installPromises).then(() => {
      console.log('All plugins installed successfully');
      setButtonState('activate');
    }).catch(error => {
      console.error('Error installing plugins:', error);
    });
  }
  async function activatePlugins() {
    setButtonState('activating');
    for (const url of activateUrls) {
      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            _wpnonce: new URL(url).searchParams.get('_wpnonce'),
            action: 'activate',
            plugin: new URL(url).searchParams.get('plugin')
          }).toString()
        });
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        console.log(`Plugin activated successfully: ${url}`);
      } catch (error) {
        console.error(`Error activating plugin: ${url}`, error);
      }
    }
    setButtonState('import');
  }
  function handleDesignImport(design) {
    (0,utils__WEBPACK_IMPORTED_MODULE_4__.insertPattern)({
      design: design,
      reload: true,
      closeModal: closeModal,
      closePatternLibraryModal: closePatternLibraryModal
    });
    setButtonState('importing');
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Modal, {
    onRequestClose: closeModal,
    title: title,
    className: "cw-pattern-library-plugins",
    size: "medium",
    isFullScreen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "cw-pattern-library-plugins-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('The following plugins need to be installed and activated in order to use this pattern.', 'cw-pattern-import')), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: "cw-pattern-library-plugins-list"
  }, pluginsList), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library-plugins__action"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: closeModal,
    variant: "secondary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Cancel', 'cw-pattern-import')), buttonState === 'install' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: () => installPlugins(pluginsToInstall),
    className: "cw-pattern-library-btn-is-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Install', 'cw-pattern-import')), buttonState === 'installing' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    isBusy: true,
    disabled: true,
    className: "cw-pattern-library-btn-is-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Installing...', 'cw-pattern-import')), buttonState === 'activate' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: activatePlugins,
    className: "cw-pattern-library-btn-is-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Activate', 'cw-pattern-import')), buttonState === 'activating' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    isBusy: true,
    disabled: true,
    className: "cw-pattern-library-btn-is-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Activating...', 'cw-pattern-import')), buttonState === 'import' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: () => handleDesignImport(design),
    className: "cw-pattern-library-btn-is-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Import', 'cw-pattern-import')), buttonState === 'importing' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    isBusy: true,
    disabled: true,
    className: "cw-pattern-library-btn-is-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Importing...', 'cw-pattern-import'))));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (RequiredPluginsModal);

/***/ }),

/***/ "./src/components/SegmentedControl/SegmentedControl.jsx":
/*!**************************************************************!*\
  !*** ./src/components/SegmentedControl/SegmentedControl.jsx ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! components */ "./src/components/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! classnames */ "../../../../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_3__);




function SegmentedControl({
  name,
  segments,
  callback,
  defaultIndex = 0,
  controlRef
}) {
  const [activeIndex, setActiveIndex] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(defaultIndex);
  const componentReady = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useRef)();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    componentReady.current = true;
  });
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    const activeSegmentRef = segments[activeIndex].ref;
    const {
      offsetWidth,
      offsetLeft
    } = activeSegmentRef.current;
    const {
      style
    } = controlRef.current;
    style.setProperty('--highlight-width', `${offsetWidth}px`);
    style.setProperty('--highlight-x-pos', `${offsetLeft}px`);
  }, [activeIndex, callback, segments, controlRef]);
  function onInputChange(value, index) {
    setActiveIndex(index);
    callback(value, index);
  }
  const controlsClass = classnames__WEBPACK_IMPORTED_MODULE_3___default()('controls', {
    ready: componentReady.current === true,
    idle: componentReady.current === false
  });
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "controls-container",
    ref: controlRef
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: controlsClass
  }, segments.map((item, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: item.value,
    className: classnames__WEBPACK_IMPORTED_MODULE_3___default()('segment', {
      active: i === activeIndex,
      inactive: i !== activeIndex
    }),
    ref: item.ref
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "radio",
    value: item.value,
    id: item.label,
    name: name,
    onChange: () => onInputChange(item.value, i),
    checked: i === activeIndex
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: item.label
  }, item.icon ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    icon: item.icon
  }) : item.label)))));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SegmentedControl);

/***/ }),

/***/ "./src/components/Sidebar/Category/Category.jsx":
/*!******************************************************!*\
  !*** ./src/components/Sidebar/Category/Category.jsx ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "../../../../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);



function Category({
  handleCategory,
  categories,
  designCount,
  selectedCategory
}) {
  const flatCategories = categories.flatMap(Object.values);
  const uniqueCategories = Array.from(new Map(flatCategories.map(item => [item.slug, item])).values());
  const order = ['banner', 'trips', 'destination', 'activities', 'testimonials', 'logo', 'ctas', 'blog', 'about', 'team', 'services', 'map'];
  const orderedUniqueCategories = uniqueCategories.sort((a, b) => {
    const indexA = order.indexOf(a.slug);
    const indexB = order.indexOf(b.slug);
    if (indexA === -1 && indexB === -1) {
      return a.slug.localeCompare(b.slug);
    }
    if (indexA === -1) {
      return 1;
    }
    if (indexB === -1) {
      return -1;
    }
    return indexA - indexB;
  });
  const allCatBtn = classnames__WEBPACK_IMPORTED_MODULE_2___default()('cw-pattern-library__category-btn transform-scale', {
    'is-active': selectedCategory === ''
  });
  const categoryButtons = uniqueCategories.map(({
    name,
    count
  }) => {
    const categoryBtn = classnames__WEBPACK_IMPORTED_MODULE_2___default()('cw-pattern-library__category-btn transform-scale', {
      'is-active': selectedCategory === name
    });
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
      key: name
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
      className: categoryBtn,
      style: {
        '--scale-x': '0.99',
        '--scale-y': '0.99'
      },
      onClick: () => handleCategory(name)
    }, name));
  });
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__category"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: "cw-pattern-library__category-list"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    className: allCatBtn,
    style: {
      '--scale-x': '0.99',
      '--scale-y': '0.99'
    },
    onClick: () => handleCategory('')
  }, "All", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, designCount))), categoryButtons));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Category);

/***/ }),

/***/ "./src/components/Sidebar/Search/Search.jsx":
/*!**************************************************!*\
  !*** ./src/components/Sidebar/Search/Search.jsx ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);



function Search({
  handleSearch,
  searchQuery
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SearchControl, {
    value: searchQuery,
    onChange: handleSearch,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Search for …', 'cw-pattern-import'),
    className: "cw-pattern-library__search"
  });
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Search);

/***/ }),

/***/ "./src/components/TopBar/TopBar.jsx":
/*!******************************************!*\
  !*** ./src/components/TopBar/TopBar.jsx ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! components */ "./src/components/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! classnames */ "../../../../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var utils_hooks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! utils/hooks */ "./src/utils/hooks/hooks.js");






function TopBar({
  handleLayoutChange,
  planOptions,
  plan,
  handlePlanChange,
  selectedLayout
}) {
  const {
    sync,
    handleSync
  } = (0,utils_hooks__WEBPACK_IMPORTED_MODULE_5__.useModalContext)();
  const col2Class = classnames__WEBPACK_IMPORTED_MODULE_4___default()('cw-layout-btn transform-scale', {
    'is-active': selectedLayout === 2
  });
  const col3Class = classnames__WEBPACK_IMPORTED_MODULE_4___default()('cw-layout-btn transform-scale', {
    'is-active': selectedLayout === 3
  });
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, cwAdmin.freeProFilter && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
    value: plan,
    options: planOptions,
    onChange: value => handlePlanChange(value),
    className: "cw-pattern-library__select",
    __nextHasNoMarginBottom: true
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__btn-group"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: () => handleSync(true),
    className: "cw-sync-btn transform-scale",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Sync Library', 'cw-pattern-import')
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.Icon, {
    icon: "sync"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cw-divider"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: () => handleLayoutChange(3),
    className: col3Class,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('3 Column Preview', 'cw-pattern-import')
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.Icon, {
    icon: "col3"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: () => handleLayoutChange(2),
    className: col2Class,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('2 Column Preview', 'cw-pattern-import')
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.Icon, {
    icon: "col2"
  }))));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TopBar);

/***/ }),

/***/ "./src/components/icon.jsx":
/*!*********************************!*\
  !*** ./src/components/icon.jsx ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "../../../../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);


const icons = {
  logo: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "236",
    height: "32",
    viewBox: "0 0 236 32",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M63.02 22.8L58.95 7.39999H61.964L64.736 19.654L67.992 7.39999H71.094L74.262 19.654L77.034 7.39999H80.07L75.89 22.8H72.546L69.488 11.382L66.342 22.8H63.02ZM81.6708 22.8V7.39999L87.3688 7.39999C88.6008 7.39999 89.6202 7.60532 90.4268 8.01599C91.2335 8.42665 91.8348 8.99132 92.2308 9.70999C92.6268 10.4287 92.8248 11.2353 92.8248 12.13C92.8248 12.9807 92.6342 13.7653 92.2528 14.484C91.8715 15.188 91.2775 15.76 90.4708 16.2C89.6642 16.6253 88.6302 16.838 87.3688 16.838H84.4868V22.8H81.6708ZM84.4868 14.55H87.1928C88.1755 14.55 88.8795 14.3373 89.3048 13.912C89.7448 13.472 89.9648 12.878 89.9648 12.13C89.9648 11.3673 89.7448 10.7733 89.3048 10.348C88.8795 9.90799 88.1755 9.68799 87.1928 9.68799H84.4868V14.55Z",
    fill: "#3F494B"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M102.982 22.8V9.66599H98.4943V7.39999H110.308V9.66599L105.798 9.66599V22.8H102.982ZM110.814 22.8V11.888H113.322L113.586 13.934C113.982 13.23 114.517 12.6727 115.192 12.262C115.881 11.8367 116.688 11.624 117.612 11.624V14.594H116.82C116.204 14.594 115.654 14.6893 115.17 14.88C114.686 15.0707 114.305 15.4007 114.026 15.87C113.762 16.3393 113.63 16.992 113.63 17.828V22.8H110.814ZM122.859 23.064C121.92 23.064 121.15 22.9173 120.549 22.624C119.947 22.316 119.5 21.9127 119.207 21.414C118.913 20.9153 118.767 20.3653 118.767 19.764C118.767 18.752 119.163 17.9307 119.955 17.3C120.747 16.6693 121.935 16.354 123.519 16.354H126.291V16.09C126.291 15.342 126.078 14.792 125.653 14.44C125.227 14.088 124.699 13.912 124.069 13.912C123.497 13.912 122.998 14.0513 122.573 14.33C122.147 14.594 121.883 14.99 121.781 15.518H119.031C119.104 14.726 119.368 14.0367 119.823 13.45C120.292 12.8633 120.893 12.416 121.627 12.108C122.36 11.7853 123.181 11.624 124.091 11.624C125.645 11.624 126.87 12.0127 127.765 12.79C128.659 13.5673 129.107 14.6673 129.107 16.09V22.8H126.709L126.445 21.04C126.122 21.6267 125.667 22.1107 125.081 22.492C124.509 22.8733 123.768 23.064 122.859 23.064ZM123.497 20.864C124.303 20.864 124.927 20.6 125.367 20.072C125.821 19.544 126.107 18.8913 126.225 18.114H123.827C123.079 18.114 122.543 18.2533 122.221 18.532C121.898 18.796 121.737 19.126 121.737 19.522C121.737 19.9473 121.898 20.2773 122.221 20.512C122.543 20.7467 122.969 20.864 123.497 20.864ZM133.98 22.8L129.976 11.888H132.924L135.696 20.138L138.468 11.888H141.416L137.39 22.8H133.98ZM147.517 23.064C146.417 23.064 145.442 22.8293 144.591 22.36C143.74 21.8907 143.073 21.2307 142.589 20.38C142.105 19.5293 141.863 18.5467 141.863 17.432C141.863 16.3027 142.098 15.298 142.567 14.418C143.051 13.538 143.711 12.856 144.547 12.372C145.398 11.8733 146.395 11.624 147.539 11.624C148.61 11.624 149.556 11.8587 150.377 12.328C151.198 12.7973 151.836 13.4427 152.291 14.264C152.76 15.0707 152.995 15.9727 152.995 16.97C152.995 17.1313 152.988 17.3 152.973 17.476C152.973 17.652 152.966 17.8353 152.951 18.026H144.657C144.716 18.8767 145.009 19.544 145.537 20.028C146.08 20.512 146.732 20.754 147.495 20.754C148.067 20.754 148.544 20.6293 148.925 20.38C149.321 20.116 149.614 19.7787 149.805 19.368H152.665C152.46 20.0573 152.115 20.688 151.631 21.26C151.162 21.8173 150.575 22.2573 149.871 22.58C149.182 22.9027 148.397 23.064 147.517 23.064ZM147.539 13.912C146.85 13.912 146.241 14.11 145.713 14.506C145.185 14.8873 144.848 15.474 144.701 16.266H150.135C150.091 15.5473 149.827 14.9753 149.343 14.55C148.859 14.1247 148.258 13.912 147.539 13.912ZM154.923 22.8V6.95999H157.739V22.8H154.923ZM165.045 22.8V7.39999H175.099V9.66599L167.861 9.66599V13.89L174.439 13.89V16.09H167.861V20.534H175.099V22.8H165.045ZM177.258 22.8V11.888H179.744L179.964 13.736C180.301 13.0907 180.785 12.5773 181.416 12.196C182.061 11.8147 182.816 11.624 183.682 11.624C185.031 11.624 186.08 12.0493 186.828 12.9C187.576 13.7507 187.95 14.9973 187.95 16.64V22.8H185.134V16.904C185.134 15.9653 184.943 15.2467 184.562 14.748C184.18 14.2493 183.586 14 182.78 14C181.988 14 181.335 14.2787 180.822 14.836C180.323 15.3933 180.074 16.1707 180.074 17.168V22.8H177.258ZM194.822 19.544C194.294 19.544 193.803 19.4853 193.348 19.368L192.534 20.182C192.784 20.314 193.121 20.424 193.546 20.512C193.972 20.6 194.661 20.688 195.614 20.776C197.066 20.908 198.122 21.2527 198.782 21.81C199.442 22.3673 199.772 23.1373 199.772 24.12C199.772 24.7653 199.596 25.374 199.244 25.946C198.892 26.5327 198.35 27.002 197.616 27.354C196.883 27.7207 195.944 27.904 194.8 27.904C193.246 27.904 191.992 27.6107 191.038 27.024C190.085 26.452 189.608 25.5867 189.608 24.428C189.608 23.4453 190.085 22.5947 191.038 21.876C190.745 21.744 190.488 21.6047 190.268 21.458C190.063 21.3113 189.88 21.1573 189.718 20.996V20.49L191.632 18.466C190.782 17.718 190.356 16.7573 190.356 15.584C190.356 14.8507 190.532 14.1833 190.884 13.582C191.251 12.9807 191.764 12.504 192.424 12.152C193.084 11.8 193.884 11.624 194.822 11.624C195.438 11.624 196.01 11.712 196.538 11.888H200.674V13.604L198.804 13.736C199.098 14.2933 199.244 14.9093 199.244 15.584C199.244 16.3173 199.068 16.9847 198.716 17.586C198.364 18.1873 197.851 18.664 197.176 19.016C196.516 19.368 195.732 19.544 194.822 19.544ZM194.822 17.388C195.394 17.388 195.864 17.234 196.23 16.926C196.612 16.618 196.802 16.178 196.802 15.606C196.802 15.034 196.612 14.594 196.23 14.286C195.864 13.978 195.394 13.824 194.822 13.824C194.221 13.824 193.737 13.978 193.37 14.286C193.004 14.594 192.82 15.034 192.82 15.606C192.82 16.178 193.004 16.618 193.37 16.926C193.737 17.234 194.221 17.388 194.822 17.388ZM192.182 24.142C192.182 24.6847 192.432 25.088 192.93 25.352C193.444 25.6307 194.067 25.77 194.8 25.77C195.504 25.77 196.076 25.6233 196.516 25.33C196.956 25.0513 197.176 24.67 197.176 24.186C197.176 23.79 197.03 23.46 196.736 23.196C196.458 22.932 195.893 22.7707 195.042 22.712C194.441 22.668 193.884 22.602 193.37 22.514C192.945 22.7487 192.637 23.0053 192.446 23.284C192.27 23.5627 192.182 23.8487 192.182 24.142ZM203.806 10.194C203.292 10.194 202.867 10.04 202.53 9.73199C202.207 9.42399 202.046 9.03532 202.046 8.56599C202.046 8.09665 202.207 7.71532 202.53 7.42199C202.867 7.11399 203.292 6.95999 203.806 6.95999C204.319 6.95999 204.737 7.11399 205.06 7.42199C205.397 7.71532 205.566 8.09665 205.566 8.56599C205.566 9.03532 205.397 9.42399 205.06 9.73199C204.737 10.04 204.319 10.194 203.806 10.194ZM202.398 22.8V11.888L205.214 11.888V22.8H202.398ZM207.713 22.8V11.888H210.199L210.419 13.736C210.756 13.0907 211.24 12.5773 211.871 12.196C212.516 11.8147 213.272 11.624 214.137 11.624C215.486 11.624 216.535 12.0493 217.283 12.9C218.031 13.7507 218.405 14.9973 218.405 16.64V22.8L215.589 22.8V16.904C215.589 15.9653 215.398 15.2467 215.017 14.748C214.636 14.2493 214.042 14 213.235 14C212.443 14 211.79 14.2787 211.277 14.836C210.778 15.3933 210.529 16.1707 210.529 17.168V22.8H207.713ZM225.96 23.064C224.86 23.064 223.884 22.8293 223.034 22.36C222.183 21.8907 221.516 21.2307 221.032 20.38C220.548 19.5293 220.306 18.5467 220.306 17.432C220.306 16.3027 220.54 15.298 221.01 14.418C221.494 13.538 222.154 12.856 222.99 12.372C223.84 11.8733 224.838 11.624 225.982 11.624C227.052 11.624 227.998 11.8587 228.82 12.328C229.641 12.7973 230.279 13.4427 230.734 14.264C231.203 15.0707 231.438 15.9727 231.438 16.97C231.438 17.1313 231.43 17.3 231.416 17.476C231.416 17.652 231.408 17.8353 231.394 18.026H223.1C223.158 18.8767 223.452 19.544 223.98 20.028C224.522 20.512 225.175 20.754 225.938 20.754C226.51 20.754 226.986 20.6293 227.368 20.38C227.764 20.116 228.057 19.7787 228.248 19.368H231.108C230.902 20.0573 230.558 20.688 230.074 21.26C229.604 21.8173 229.018 22.2573 228.314 22.58C227.624 22.9027 226.84 23.064 225.96 23.064ZM225.982 13.912C225.292 13.912 224.684 14.11 224.156 14.506C223.628 14.8873 223.29 15.474 223.144 16.266H228.578C228.534 15.5473 228.27 14.9753 227.786 14.55C227.302 14.1247 226.7 13.912 225.982 13.912Z",
    fill: "url(#paint0_linear_79544_989)"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    clipPath: "url(#clip0_79544_989)"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M49.2668 16.2806C49.9401 15.2745 48.9377 14.4265 48.9377 14.4265C48.9377 14.4265 47.7677 13.8324 47.0975 14.8385C46.4242 15.8447 44.7276 18.3885 44.7276 18.3885L37.8067 18.5497L36.6457 20.2873L42.5224 21.6876L39.8863 24.8643C40.7061 25.1838 41.4033 25.7719 42.1932 26.166L44.6917 23.1296L48.2495 28.0022L49.4104 26.2646L46.894 19.8305C46.897 19.8305 48.5966 17.2868 49.2668 16.2806Z",
    fill: "#3F494B"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M24.5749 14.8505C26.6654 14.8505 28.3601 13.1596 28.3601 11.0737C28.3601 8.98782 26.6654 7.29688 24.5749 7.29688C22.4845 7.29688 20.7898 8.98782 20.7898 11.0737C20.7898 13.1596 22.4845 14.8505 24.5749 14.8505Z",
    fill: "url(#paint1_linear_79544_989)"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M45.5354 19.2484C44.907 18.8543 44.0752 19.0424 43.6802 19.6723C39.8323 25.769 35.8137 29.101 32.0585 29.304C27.3039 29.5667 24.1741 24.9479 21.4422 21.8638C19.994 20.2277 18.6774 18.4811 17.5284 16.624C16.6188 15.1581 15.6134 13.4055 15.6134 11.632C15.6104 6.69976 19.6319 2.68707 24.575 2.68707C29.5152 2.68707 33.5367 6.69976 33.5367 11.632C33.5367 14.5848 29.976 19.3141 26.9179 22.7654C26.4362 23.3088 26.49 24.1358 27.0257 24.6285C27.0346 24.6344 27.0406 24.6434 27.0496 24.6494C27.5942 25.151 28.4499 25.1032 28.9407 24.5508C32.1004 21.0069 36.2327 15.6119 36.2327 11.629C36.2297 5.2159 31.0023 0 24.575 0C18.1478 0 12.9174 5.2159 12.9174 11.632C12.9174 15.379 16.5769 20.3769 19.6499 23.9149L19.6409 23.906C20.4548 24.6733 21.1549 25.6018 21.9239 26.4169C23.4171 27.9933 24.9431 29.6742 26.8611 30.7311C26.8641 30.7341 26.8701 30.7341 26.882 30.743C28.1657 31.4417 29.7845 32 31.6815 32C31.8401 32 32.0017 31.997 32.1663 31.9881C36.9089 31.7522 41.5498 28.0888 45.9573 21.1024C46.3553 20.4755 46.1638 19.6455 45.5354 19.2484Z",
    fill: "#3F494B"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M21.6786 28.9967C21.2866 28.6265 20.7121 28.5339 20.2154 28.7429C19.3178 29.1191 18.2675 29.3699 17.0916 29.3042C13.3364 29.1012 9.31783 25.7692 5.48482 19.6935L2.4986 14.7582C2.1156 14.1223 1.28676 13.9193 0.652412 14.3014C0.0150733 14.6866 -0.191389 15.5106 0.194605 16.1466L3.19279 21.1027C7.6003 28.0891 12.2412 31.7524 16.9838 31.9883C17.1484 31.9973 17.31 32.0003 17.4686 32.0003C18.9018 32.0003 20.1795 31.6808 21.2717 31.221C22.1723 30.8418 22.3937 29.6715 21.6846 28.9997L21.6786 28.9967Z",
    fill: "url(#paint2_linear_79544_989)"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("defs", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("linearGradient", {
    id: "paint0_linear_79544_989",
    x1: "10.9454",
    y1: "-3.68183",
    x2: "27.1675",
    y2: "83.9744",
    gradientUnits: "userSpaceOnUse"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    offset: "1",
    stopColor: "#00A89F"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("linearGradient", {
    id: "paint1_linear_79544_989",
    x1: "18.7252",
    y1: "6.12949",
    x2: "30.4703",
    y2: "16.7303",
    gradientUnits: "userSpaceOnUse"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    offset: "1",
    stopColor: "#00A89F"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("linearGradient", {
    id: "paint2_linear_79544_989",
    x1: "-6.02864",
    y1: "11.3435",
    x2: "21.7788",
    y2: "42.2838",
    gradientUnits: "userSpaceOnUse"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    offset: "1",
    stopColor: "#00A89F"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("clipPath", {
    id: "clip0_79544_989"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    width: "49.5",
    height: "32",
    fill: "white"
  })))),
  col2: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M11 4.44133C11 3.64533 10.3547 3 9.55867 3H4.44133C3.64533 3 3 3.64533 3 4.44133V9.55867C3 10.3547 3.64533 11 4.44133 11H9.55867C10.3547 11 11 10.3547 11 9.55867V4.44133Z",
    fill: "currentColor"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M21 4.44133C21 3.64533 20.3547 3 19.5587 3H14.4413C13.6453 3 13 3.64533 13 4.44133V9.55867C13 10.3547 13.6453 11 14.4413 11H19.5587C20.3547 11 21 10.3547 21 9.55867V4.44133Z",
    fill: "currentColor"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M11 14.4413C11 13.6453 10.3547 13 9.55867 13H4.44133C3.64533 13 3 13.6453 3 14.4413V19.5587C3 20.3547 3.64533 21 4.44133 21H9.55867C10.3547 21 11 20.3547 11 19.5587V14.4413Z",
    fill: "currentColor"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M21 14.4413C21 13.6453 20.3547 13 19.5587 13H14.4413C13.6453 13 13 13.6453 13 14.4413V19.5587C13 20.3547 13.6453 21 14.4413 21H19.5587C20.3547 21 21 20.3547 21 19.5587V14.4413Z",
    fill: "currentColor"
  })),
  col3: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M20.1819 9.54552H16.9091C16.091 9.54552 16.091 9.54552 16.091 10.3636V13.6364C16.091 14.4545 16.091 14.4545 16.9091 14.4545H20.1819C21 14.4545 21 14.4545 21 13.6364V10.3636C21 9.54552 21 9.54552 20.1819 9.54552ZM13.6364 3H10.3636C9.54552 3 9.54552 3 9.54552 3.8181V7.09086C9.54552 7.90896 9.54552 7.90896 10.3636 7.90896H13.6364C14.4545 7.90896 14.4545 7.90896 14.4545 7.09086V3.8181C14.4545 3 14.4545 3 13.6364 3ZM20.1819 16.0909H16.9091C16.091 16.0909 16.091 16.0909 16.091 16.909V20.1817C16.091 20.9998 16.091 20.9998 16.9091 20.9998H20.1819C21 21 21 21 21 20.1819V16.9091C21 16.0909 21 16.0909 20.1819 16.0909ZM13.6364 9.54552H10.3636C9.54552 9.54552 9.54552 9.54552 9.54552 10.3636V13.6364C9.54552 14.4545 9.54552 14.4545 10.3636 14.4545H13.6364C14.4545 14.4545 14.4545 14.4545 14.4545 13.6364V10.3636C14.4545 9.54552 14.4545 9.54552 13.6364 9.54552ZM7.09086 3H3.8181C3 3 3 3 3 3.8181V7.09086C3 7.90896 3 7.90896 3.8181 7.90896H7.09086C7.90896 7.90896 7.90896 7.90896 7.90896 7.09086V3.8181C7.90914 3 7.90914 3 7.09086 3ZM13.6364 16.0909H10.3636C9.54552 16.0909 9.54552 16.0909 9.54552 16.909V20.1817C9.54552 20.9998 9.54552 20.9998 10.3636 20.9998H13.6364C14.4545 20.9998 14.4545 20.9998 14.4545 20.1817V16.9091C14.4545 16.0909 14.4545 16.0909 13.6364 16.0909ZM7.09086 9.54552H3.8181C3 9.54552 3 9.54552 3 10.3636V13.6364C3 14.4545 3 14.4545 3.8181 14.4545H7.09086C7.90896 14.4545 7.90896 14.4545 7.90896 13.6364V10.3636C7.90914 9.54552 7.90914 9.54552 7.09086 9.54552ZM7.09086 16.0909H3.8181C3 16.0909 3 16.0909 3 16.9091V20.1819C3 21 3 21 3.8181 21H7.09086C7.90896 21 7.90896 21 7.90896 20.1819V16.9091C7.90914 16.0909 7.90914 16.0909 7.09086 16.0909ZM20.1819 3H16.9091C16.091 3 16.091 3 16.091 3.8181V7.09086C16.091 7.90896 16.091 7.90896 16.9091 7.90896H20.1819C21 7.90896 21 7.90896 21 7.09086V3.8181C21 3 21 3 20.1819 3Z",
    fill: "currentColor"
  })),
  sync: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    opacity: "0.6"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M2 14C2 14 2.12132 14.8492 5.63604 18.364C9.15076 21.8787 14.8492 21.8787 18.364 18.364C19.6092 17.1187 20.4133 15.5993 20.7762 14M2 14V20M2 14H8M22 10C22 10 21.8787 9.15076 18.364 5.63604C14.8492 2.12132 9.15076 2.12132 5.63604 5.63604C4.39076 6.88131 3.58669 8.40072 3.22383 10M22 10V4M22 10H16",
    stroke: "currentColor",
    strokeWidth: "2",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  }))),
  crown: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "16",
    height: "14",
    viewBox: "0 0 16 14",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M2.16667 10.3333L0.5 1.16666L5.08333 5.33333L8 0.333328L10.9167 5.33333L15.5 1.16666L13.8333 10.3333H2.16667ZM13.8333 12.8333C13.8333 13.3333 13.5 13.6667 13 13.6667H3C2.5 13.6667 2.16667 13.3333 2.16667 12.8333V12H13.8333V12.8333Z",
    fill: "white"
  })),
  img: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    clipPath: "url(#clip0_79546_11888)"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M9.00299 8.25C9.00299 8.84674 8.76594 9.41903 8.34398 9.84099C7.92202 10.2629 7.34973 10.5 6.75299 10.5C6.15625 10.5 5.58396 10.2629 5.162 9.84099C4.74004 9.41903 4.50299 8.84674 4.50299 8.25C4.50299 7.65326 4.74004 7.08097 5.162 6.65901C5.58396 6.23705 6.15625 6 6.75299 6C7.34973 6 7.92202 6.23705 8.34398 6.65901C8.76594 7.08097 9.00299 7.65326 9.00299 8.25Z",
    fill: "#0CB19F"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M3.00299 1.5C2.20734 1.5 1.44428 1.81607 0.88167 2.37868C0.319061 2.94129 0.00299072 3.70435 0.00299072 4.5V19.5C0.00299072 20.2956 0.319061 21.0587 0.88167 21.6213C1.44428 22.1839 2.20734 22.5 3.00299 22.5H21.003C21.7986 22.5 22.5617 22.1839 23.1243 21.6213C23.6869 21.0587 24.003 20.2956 24.003 19.5V4.5C24.003 3.70435 23.6869 2.94129 23.1243 2.37868C22.5617 1.81607 21.7986 1.5 21.003 1.5H3.00299ZM21.003 3C21.4008 3 21.7823 3.15804 22.0637 3.43934C22.345 3.72064 22.503 4.10218 22.503 4.5V14.25L16.8375 11.3295C16.6968 11.259 16.5376 11.2346 16.3822 11.2596C16.2269 11.2847 16.0834 11.3579 15.972 11.469L10.407 17.034L6.41699 14.376C6.27293 14.2801 6.10013 14.237 5.9279 14.2539C5.75566 14.2709 5.59459 14.3468 5.47199 14.469L1.50299 18V4.5C1.50299 4.10218 1.66103 3.72064 1.94233 3.43934C2.22364 3.15804 2.60517 3 3.00299 3H21.003Z",
    fill: "#0CB19F"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("defs", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("clipPath", {
    id: "clip0_79546_11888"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    width: "24",
    height: "24",
    fill: "white"
  })))),
  down_arrow: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "16",
    height: "16",
    viewBox: "0 0 16 16",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M8 2C8.26522 2 8.51957 2.10536 8.70711 2.29289C8.89464 2.48043 9 2.73478 9 3V10.92L11.6267 8.29333C11.8162 8.11669 12.067 8.02053 12.326 8.0251C12.5851 8.02967 12.8323 8.13462 13.0155 8.31784C13.1987 8.50105 13.3037 8.74824 13.3082 9.0073C13.3128 9.26637 13.2166 9.5171 13.04 9.70667L8.70667 14.04C8.51917 14.2273 8.265 14.3325 8 14.3325C7.735 14.3325 7.48083 14.2273 7.29333 14.04L2.96 9.70667C2.86175 9.61512 2.78295 9.50472 2.72829 9.38205C2.67364 9.25939 2.64425 9.12697 2.64188 8.9927C2.63951 8.85843 2.66421 8.72505 2.7145 8.60054C2.7648 8.47602 2.83966 8.36291 2.93462 8.26795C3.02958 8.17299 3.14269 8.09813 3.2672 8.04784C3.39172 7.99754 3.52509 7.97284 3.65936 7.97521C3.79364 7.97758 3.92605 8.00697 4.04872 8.06163C4.17138 8.11628 4.28179 8.19508 4.37333 8.29333L7 10.92V3C7 2.73478 7.10536 2.48043 7.29289 2.29289C7.48043 2.10536 7.73478 2 8 2Z",
    fill: "white"
  })),
  heart_fill: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "20",
    height: "20",
    viewBox: "0 0 20 20",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("mask", {
    id: "mask0_79550_12144",
    style: {
      maskType: 'alpha'
    },
    maskUnits: "userSpaceOnUse",
    x: "0",
    y: "0",
    width: "20",
    height: "20"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    width: "20",
    height: "20",
    fill: "#D9D9D9"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    mask: "url(#mask0_79550_12144)"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M10.0003 18.6457L8.79199 17.5623C7.38921 16.2984 6.22949 15.2082 5.31283 14.2915C4.39616 13.3748 3.66699 12.5519 3.12533 11.8228C2.58366 11.0936 2.20519 10.4234 1.98991 9.81234C1.77463 9.20123 1.66699 8.57623 1.66699 7.93734C1.66699 6.63178 2.10449 5.5415 2.97949 4.6665C3.85449 3.7915 4.94477 3.354 6.25033 3.354C6.97255 3.354 7.66005 3.50678 8.31283 3.81234C8.9656 4.11789 9.5281 4.54845 10.0003 5.104C10.4725 4.54845 11.035 4.11789 11.6878 3.81234C12.3406 3.50678 13.0281 3.354 13.7503 3.354C15.0559 3.354 16.1462 3.7915 17.0212 4.6665C17.8962 5.5415 18.3337 6.63178 18.3337 7.93734C18.3337 8.57623 18.226 9.20123 18.0107 9.81234C17.7955 10.4234 17.417 11.0936 16.8753 11.8228C16.3337 12.5519 15.6045 13.3748 14.6878 14.2915C13.7712 15.2082 12.6114 16.2984 11.2087 17.5623L10.0003 18.6457Z",
    fill: "currentColor"
  }))),
  heart_outline: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "20",
    height: "20",
    viewBox: "0 0 20 20",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("mask", {
    id: "mask0_79550_12139",
    style: {
      maskType: 'alpha'
    },
    maskUnits: "userSpaceOnUse",
    x: "0",
    y: "0",
    width: "20",
    height: "20"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    width: "20",
    height: "20",
    fill: "#D9D9D9"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    mask: "url(#mask0_79550_12139)"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M10.0003 18.6457L8.79199 17.5623C7.38921 16.2984 6.22949 15.2082 5.31283 14.2915C4.39616 13.3748 3.66699 12.5519 3.12533 11.8228C2.58366 11.0936 2.20519 10.4234 1.98991 9.81234C1.77463 9.20123 1.66699 8.57623 1.66699 7.93734C1.66699 6.63178 2.10449 5.5415 2.97949 4.6665C3.85449 3.7915 4.94477 3.354 6.25033 3.354C6.97255 3.354 7.66005 3.50678 8.31283 3.81234C8.9656 4.11789 9.5281 4.54845 10.0003 5.104C10.4725 4.54845 11.035 4.11789 11.6878 3.81234C12.3406 3.50678 13.0281 3.354 13.7503 3.354C15.0559 3.354 16.1462 3.7915 17.0212 4.6665C17.8962 5.5415 18.3337 6.63178 18.3337 7.93734C18.3337 8.57623 18.226 9.20123 18.0107 9.81234C17.7955 10.4234 17.417 11.0936 16.8753 11.8228C16.3337 12.5519 15.6045 13.3748 14.6878 14.2915C13.7712 15.2082 12.6114 16.2984 11.2087 17.5623L10.0003 18.6457ZM10.0003 16.3957C11.3337 15.2012 12.4309 14.1769 13.292 13.3228C14.1531 12.4686 14.8337 11.7255 15.3337 11.0936C15.8337 10.4616 16.1809 9.89914 16.3753 9.40609C16.5698 8.91303 16.667 8.42345 16.667 7.93734C16.667 7.104 16.3892 6.40956 15.8337 5.854C15.2781 5.29845 14.5837 5.02067 13.7503 5.02067C13.0975 5.02067 12.4934 5.2047 11.9378 5.57275C11.3823 5.94081 11.0003 6.40956 10.792 6.979H9.20866C9.00033 6.40956 8.61838 5.94081 8.06283 5.57275C7.50727 5.2047 6.9031 5.02067 6.25033 5.02067C5.41699 5.02067 4.72255 5.29845 4.16699 5.854C3.61144 6.40956 3.33366 7.104 3.33366 7.93734C3.33366 8.42345 3.43088 8.91303 3.62533 9.40609C3.81977 9.89914 4.16699 10.4616 4.66699 11.0936C5.16699 11.7255 5.84755 12.4686 6.70866 13.3228C7.56977 14.1769 8.66699 15.2012 10.0003 16.3957Z",
    fill: "currentColor"
  }))),
  error: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "66",
    height: "66",
    viewBox: "0 0 66 66",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    x: "9",
    y: "9",
    width: "48",
    height: "48",
    rx: "24",
    fill: "#D92D20",
    fillOpacity: "0.2"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M33.4761 28.6762V33.4762M33.4761 38.2762H33.4881M45.4761 33.4762C45.4761 40.1036 40.1035 45.4762 33.4761 45.4762C26.8487 45.4762 21.4761 40.1036 21.4761 33.4762C21.4761 26.8488 26.8487 21.4762 33.4761 21.4762C40.1035 21.4762 45.4761 26.8488 45.4761 33.4762Z",
    stroke: "#D92D20",
    strokeWidth: "2",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    x: "4.85714",
    y: "4.85714",
    width: "56.2857",
    height: "56.2857",
    rx: "28.1429",
    stroke: "#D92D20",
    strokeOpacity: "0.1",
    strokeWidth: "8.28571"
  })),
  desktop: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "21",
    height: "20",
    viewBox: "0 0 21 20",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M7.16666 17.5H13.8333M10.5 14.1667V17.5M6.16666 14.1667H14.8333C16.2335 14.1667 16.9335 14.1667 17.4683 13.8942C17.9387 13.6545 18.3212 13.272 18.5608 12.8016C18.8333 12.2669 18.8333 11.5668 18.8333 10.1667V6.5C18.8333 5.09987 18.8333 4.3998 18.5608 3.86502C18.3212 3.39462 17.9387 3.01217 17.4683 2.77248C16.9335 2.5 16.2335 2.5 14.8333 2.5H6.16666C4.76652 2.5 4.06646 2.5 3.53168 2.77248C3.06127 3.01217 2.67882 3.39462 2.43914 3.86502C2.16666 4.3998 2.16666 5.09987 2.16666 6.5V10.1667C2.16666 11.5668 2.16666 12.2669 2.43914 12.8016C2.67882 13.272 3.06127 13.6545 3.53168 13.8942C4.06646 14.1667 4.76652 14.1667 6.16666 14.1667Z",
    stroke: "currentColor",
    strokeWidth: "1.67",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  })),
  tablet: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "21",
    height: "20",
    viewBox: "0 0 21 20",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M10.5 14.5834H10.5083M6.50001 18.3334H14.5C15.4334 18.3334 15.9001 18.3334 16.2567 18.1517C16.5703 17.9919 16.8252 17.7369 16.985 17.4233C17.1667 17.0668 17.1667 16.6001 17.1667 15.6667V4.33335C17.1667 3.39993 17.1667 2.93322 16.985 2.5767C16.8252 2.2631 16.5703 2.00813 16.2567 1.84834C15.9001 1.66669 15.4334 1.66669 14.5 1.66669H6.50001C5.56659 1.66669 5.09988 1.66669 4.74336 1.84834C4.42976 2.00813 4.17479 2.2631 4.015 2.5767C3.83334 2.93322 3.83334 3.39993 3.83334 4.33335V15.6667C3.83334 16.6001 3.83334 17.0668 4.015 17.4233C4.17479 17.7369 4.42976 17.9919 4.74336 18.1517C5.09988 18.3334 5.56659 18.3334 6.50001 18.3334ZM10.9167 14.5834C10.9167 14.8135 10.7301 15 10.5 15C10.2699 15 10.0833 14.8135 10.0833 14.5834C10.0833 14.3532 10.2699 14.1667 10.5 14.1667C10.7301 14.1667 10.9167 14.3532 10.9167 14.5834Z",
    stroke: "currentColor",
    strokeWidth: "1.67",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  })),
  mobile: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "21",
    height: "20",
    viewBox: "0 0 21 20",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M10.5 14.4H10.5071M7.78571 18H13.2143C14.0144 18 14.4144 18 14.72 17.8256C14.9888 17.6722 15.2073 17.4274 15.3443 17.1264C15.5 16.7841 15.5 16.3361 15.5 15.44V4.56C15.5 3.66392 15.5 3.21587 15.3443 2.87362C15.2073 2.57256 14.9888 2.32779 14.72 2.17439C14.4144 2 14.0144 2 13.2143 2H7.78571C6.98564 2 6.5856 2 6.28001 2.17439C6.01121 2.32779 5.79267 2.57256 5.6557 2.87362C5.5 3.21587 5.5 3.66392 5.5 4.56V15.44C5.5 16.3361 5.5 16.7841 5.6557 17.1264C5.79267 17.4274 6.01121 17.6722 6.28001 17.8256C6.5856 18 6.98564 18 7.78571 18ZM10.8571 14.4C10.8571 14.6209 10.6972 14.8 10.5 14.8C10.3028 14.8 10.1429 14.6209 10.1429 14.4C10.1429 14.1791 10.3028 14 10.5 14C10.6972 14 10.8571 14.1791 10.8571 14.4Z",
    stroke: "currentColor",
    strokeWidth: "1.67",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  })),
  back: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "20",
    height: "20",
    viewBox: "0 0 20 20",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M15.8337 9.99996H4.16699M4.16699 9.99996L10.0003 15.8333M4.16699 9.99996L10.0003 4.16663",
    stroke: "currentColor",
    strokeWidth: "1.67",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  })),
  eye: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "48",
    height: "48",
    viewBox: "0 0 48 48",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M30 24C30 25.5913 29.3679 27.1174 28.2426 28.2426C27.1174 29.3679 25.5913 30 24 30C22.4087 30 20.8826 29.3679 19.7574 28.2426C18.6321 27.1174 18 25.5913 18 24C18 22.4087 18.6321 20.8826 19.7574 19.7574C20.8826 18.6321 22.4087 18 24 18C25.5913 18 27.1174 18.6321 28.2426 19.7574C29.3679 20.8826 30 22.4087 30 24Z",
    stroke: "currentColor",
    strokeOpacity: "1",
    strokeWidth: "3",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M4 24C7.2 15.806 14.672 10 24 10C33.328 10 40.8 15.806 44 24C40.8 32.194 33.328 38 24 38C14.672 38 7.2 32.194 4 24Z",
    stroke: "currentColor",
    strokeOpacity: "1",
    strokeWidth: "3",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  })),
  'wp-travel-engine-white': (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "24",
    height: "15",
    viewBox: "0 0 24 15",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    clipPath: "url(#clip0_79906_35)"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M23.8869 7.63155C24.2133 7.15991 23.7273 6.76245 23.7273 6.76245C23.7273 6.76245 23.1601 6.48395 22.8351 6.95558C22.5087 7.42722 21.6861 8.6196 21.6861 8.6196L18.3305 8.69518L17.7676 9.50969L20.6169 10.1661L19.3388 11.6551C19.7363 11.8049 20.0743 12.0806 20.4573 12.2653L21.6687 10.842L23.3936 13.126L23.9565 12.3115L22.7364 9.29557C22.7379 9.29557 23.5619 8.10318 23.8869 7.63155Z",
    fill: "white"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M11.9152 6.96118C12.9287 6.96118 13.7504 6.16855 13.7504 5.1908C13.7504 4.21304 12.9287 3.42041 11.9152 3.42041C10.9016 3.42041 10.08 4.21304 10.08 5.1908C10.08 6.16855 10.9016 6.96118 11.9152 6.96118Z",
    fill: "white"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M22.2373 9.0881C21.9326 8.90337 21.5293 8.99154 21.3378 9.28683C19.4721 12.1446 17.5237 13.7065 15.703 13.8017C13.3978 13.9248 11.8803 11.7598 10.5557 10.3141C9.85354 9.54714 9.21521 8.72843 8.65811 7.85793C8.21708 7.17077 7.72962 6.34925 7.72962 5.51794C7.72817 3.20594 9.678 1.32499 12.0747 1.32499C14.4699 1.32499 16.4197 3.20594 16.4197 5.51794C16.4197 6.90206 14.6933 9.11889 13.2106 10.7367C12.977 10.9914 13.0032 11.3791 13.2628 11.61C13.2672 11.6128 13.2701 11.617 13.2744 11.6198C13.5385 11.8549 13.9534 11.8326 14.1913 11.5736C15.7233 9.91242 17.7268 7.38349 17.7268 5.51654C17.7254 2.51038 15.1909 0.0654297 12.0747 0.0654297C8.95842 0.0654297 6.42249 2.51038 6.42249 5.51794C6.42249 7.27433 8.19677 9.61712 9.68671 11.2755L9.68235 11.2713C10.077 11.631 10.4164 12.0663 10.7893 12.4483C11.5132 13.1873 12.2531 13.9752 13.183 14.4706C13.1845 14.472 13.1874 14.472 13.1932 14.4762C13.8156 14.8037 14.6004 15.0654 15.5202 15.0654C15.5971 15.0654 15.6755 15.064 15.7553 15.0598C18.0547 14.9493 20.3048 13.2321 22.4418 9.9572C22.6348 9.6633 22.5419 9.27424 22.2373 9.0881Z",
    fill: "white"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M10.5108 13.5922C10.3208 13.4187 10.0422 13.3753 9.80142 13.4733C9.36619 13.6496 8.85697 13.7672 8.28682 13.7364C6.46611 13.6412 4.51773 12.0793 2.65931 9.23133L1.21144 6.91793C1.02574 6.61983 0.623883 6.52466 0.316321 6.7038C0.00730826 6.88434 -0.0927945 7.27061 0.0943541 7.5687L1.54802 9.8919C3.685 13.1668 5.93513 14.884 8.23459 14.9945C8.31438 14.9987 8.39273 15.0001 8.46962 15.0001C9.16453 15.0001 9.78401 14.8504 10.3135 14.6349C10.7502 14.4571 10.8576 13.9085 10.5137 13.5936L10.5108 13.5922Z",
    fill: "white"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("defs", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("clipPath", {
    id: "clip0_79906_35"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    width: "24",
    height: "15",
    fill: "currentColor"
  })))),
  'wp-travel-engine-color': (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "49",
    height: "32",
    viewBox: "0 0 49 32",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    clipPath: "url(#clip0_79906_46)"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M48.7691 16.2806C49.4356 15.2745 48.4433 14.4265 48.4433 14.4265C48.4433 14.4265 47.2852 13.8324 46.6217 14.8385C45.9552 15.8447 44.2758 18.3885 44.2758 18.3885L37.4248 18.5497L36.2755 20.2873L42.0928 21.6876L39.4833 24.8643C40.2949 25.1838 40.9851 25.7719 41.767 26.166L44.2403 23.1296L47.7621 28.0022L48.9113 26.2646L46.4203 19.8305C46.4232 19.8305 48.1056 17.2868 48.7691 16.2806Z",
    fill: "#3F494B"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M24.3267 14.8505C26.3961 14.8505 28.0736 13.1596 28.0736 11.0737C28.0736 8.98782 26.3961 7.29688 24.3267 7.29688C22.2574 7.29688 20.5798 8.98782 20.5798 11.0737C20.5798 13.1596 22.2574 14.8505 24.3267 14.8505Z",
    fill: "url(#paint0_linear_79906_46)"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M45.0755 19.2484C44.4535 18.8543 43.6301 19.0424 43.2391 19.6723C39.43 25.769 35.452 29.101 31.7348 29.304C27.0282 29.5667 23.9299 24.9479 21.2257 21.8638C19.7921 20.2277 18.4888 18.4811 17.3514 16.624C16.451 15.1581 15.4557 13.4055 15.4557 11.632C15.4528 6.69976 19.4337 2.68707 24.3268 2.68707C29.2171 2.68707 33.198 6.69976 33.198 11.632C33.198 14.5848 29.6732 19.3141 26.6461 22.7654C26.1692 23.3088 26.2225 24.1358 26.7527 24.6285C26.7616 24.6344 26.7675 24.6434 26.7764 24.6494C27.3155 25.151 28.1626 25.1032 28.6484 24.5508C31.7762 21.0069 35.8667 15.6119 35.8667 11.629C35.8638 5.2159 30.6892 0 24.3268 0C17.9645 0 12.787 5.2159 12.787 11.632C12.787 15.379 16.4095 20.3769 19.4514 23.9149L19.4426 23.906C20.2482 24.6733 20.9413 25.6018 21.7025 26.4169C23.1806 27.9933 24.6912 29.6742 26.5898 30.7311C26.5928 30.7341 26.5987 30.7341 26.6105 30.743C27.8812 31.4417 29.4837 32 31.3615 32C31.5185 32 31.6785 31.997 31.8414 31.9881C36.5361 31.7522 41.1301 28.0888 45.4931 21.1024C45.8871 20.4755 45.6975 19.6455 45.0755 19.2484Z",
    fill: "#3F494B"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M21.4596 28.9967C21.0716 28.6265 20.5029 28.5339 20.0112 28.7429C19.1226 29.1191 18.083 29.3699 16.9189 29.3042C13.2016 29.1012 9.22371 25.7692 5.42942 19.6935L2.47336 14.7582C2.09423 14.1223 1.27376 13.9193 0.645822 14.3014C0.014921 14.6866 -0.189455 15.5106 0.19264 16.1466L3.16054 21.1027C7.52353 28.0891 12.1176 31.7524 16.8123 31.9883C16.9752 31.9973 17.1352 32.0003 17.2921 32.0003C18.7109 32.0003 19.9757 31.6808 21.0568 31.221C21.9484 30.8418 22.1675 29.6715 21.4656 28.9997L21.4596 28.9967Z",
    fill: "url(#paint1_linear_79906_46)"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("defs", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("linearGradient", {
    id: "paint0_linear_79906_46",
    x1: "18.5361",
    y1: "6.12949",
    x2: "30.2685",
    y2: "16.6118",
    gradientUnits: "userSpaceOnUse"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    offset: "1",
    stopColor: "#00A89F"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("linearGradient", {
    id: "paint1_linear_79906_46",
    x1: "-5.96774",
    y1: "11.3435",
    x2: "21.8683",
    y2: "42.0028",
    gradientUnits: "userSpaceOnUse"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    stopColor: "#1FC0A1"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("stop", {
    offset: "1",
    stopColor: "#00A89F"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("clipPath", {
    id: "clip0_79906_46"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    width: "49",
    height: "32",
    fill: "white"
  }))))
};
function Icon({
  icon,
  className,
  size
}) {
  const svgClass = classnames__WEBPACK_IMPORTED_MODULE_1___default()('cw-icon', className && `cw-icon__${className}`);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: svgClass,
    style: {
      '--icon-width': size?.width,
      '--icon-height': size?.height
    }
  }, icons[icon]);
}
;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Icon);

/***/ }),

/***/ "./src/components/index.js":
/*!*********************************!*\
  !*** ./src/components/index.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Category: () => (/* reexport safe */ _Sidebar_Category_Category_jsx__WEBPACK_IMPORTED_MODULE_1__["default"]),
/* harmony export */   FavButton: () => (/* reexport safe */ _FavButton_FavButton_jsx__WEBPACK_IMPORTED_MODULE_5__["default"]),
/* harmony export */   Icon: () => (/* reexport safe */ _icon_jsx__WEBPACK_IMPORTED_MODULE_0__["default"]),
/* harmony export */   NoticeModal: () => (/* reexport safe */ _NoticeModal_NoticeModal_jsx__WEBPACK_IMPORTED_MODULE_7__["default"]),
/* harmony export */   PatternList: () => (/* reexport safe */ _PatternList_PatternList_jsx__WEBPACK_IMPORTED_MODULE_4__["default"]),
/* harmony export */   PreviewModal: () => (/* reexport safe */ _PreviewModal_PreviewModal_jsx__WEBPACK_IMPORTED_MODULE_8__["default"]),
/* harmony export */   RequiredPluginsModal: () => (/* reexport safe */ _RequiredPluginsModal_RequiredPluginsModal_jsx__WEBPACK_IMPORTED_MODULE_6__["default"]),
/* harmony export */   Search: () => (/* reexport safe */ _Sidebar_Search_Search_jsx__WEBPACK_IMPORTED_MODULE_2__["default"]),
/* harmony export */   SegmentedControl: () => (/* reexport safe */ _SegmentedControl_SegmentedControl_jsx__WEBPACK_IMPORTED_MODULE_9__["default"]),
/* harmony export */   TopBar: () => (/* reexport safe */ _TopBar_TopBar_jsx__WEBPACK_IMPORTED_MODULE_3__["default"])
/* harmony export */ });
/* harmony import */ var _icon_jsx__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./icon.jsx */ "./src/components/icon.jsx");
/* harmony import */ var _Sidebar_Category_Category_jsx__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Sidebar/Category/Category.jsx */ "./src/components/Sidebar/Category/Category.jsx");
/* harmony import */ var _Sidebar_Search_Search_jsx__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Sidebar/Search/Search.jsx */ "./src/components/Sidebar/Search/Search.jsx");
/* harmony import */ var _TopBar_TopBar_jsx__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./TopBar/TopBar.jsx */ "./src/components/TopBar/TopBar.jsx");
/* harmony import */ var _PatternList_PatternList_jsx__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./PatternList/PatternList.jsx */ "./src/components/PatternList/PatternList.jsx");
/* harmony import */ var _FavButton_FavButton_jsx__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./FavButton/FavButton.jsx */ "./src/components/FavButton/FavButton.jsx");
/* harmony import */ var _RequiredPluginsModal_RequiredPluginsModal_jsx__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./RequiredPluginsModal/RequiredPluginsModal.jsx */ "./src/components/RequiredPluginsModal/RequiredPluginsModal.jsx");
/* harmony import */ var _NoticeModal_NoticeModal_jsx__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./NoticeModal/NoticeModal.jsx */ "./src/components/NoticeModal/NoticeModal.jsx");
/* harmony import */ var _PreviewModal_PreviewModal_jsx__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./PreviewModal/PreviewModal.jsx */ "./src/components/PreviewModal/PreviewModal.jsx");
/* harmony import */ var _SegmentedControl_SegmentedControl_jsx__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./SegmentedControl/SegmentedControl.jsx */ "./src/components/SegmentedControl/SegmentedControl.jsx");











/***/ }),

/***/ "./src/pattern-library/PatternLibrary.jsx":
/*!************************************************!*\
  !*** ./src/pattern-library/PatternLibrary.jsx ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! components */ "./src/components/index.js");
/* harmony import */ var utils_hooks__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! utils/hooks */ "./src/utils/hooks/hooks.js");





const planOptions = [{
  label: 'All',
  value: ''
}, {
  label: 'Free',
  value: 'free'
}, {
  label: 'Pro',
  value: 'pro'
}];
function PatternLibrary({
  tab,
  designs,
  categories
}) {
  const [searchQuery, setSearchQuery] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)('');
  const [filters, setFilters] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)({
    patterns: '',
    page: ''
  });
  const [plans, setPlans] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)({
    patterns: '',
    page: ''
  });
  const [layout, setLayout] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(3);
  const debouncedSearchQuery = (0,utils_hooks__WEBPACK_IMPORTED_MODULE_4__.useDebounce)(searchQuery, 500);
  const handleSearch = value => {
    setSearchQuery(value);
  };
  const handleLayoutChange = value => {
    setLayout(value);
  };
  const handlePlanChange = (value, filterType) => {
    setPlans(prevPlans => ({
      ...prevPlans,
      [filterType]: value
    }));
  };
  const handleFilterChange = (value, filterType) => {
    setFilters(prevFilters => ({
      ...prevFilters,
      [filterType]: value
    }));
  };
  const filteredDesigns = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useMemo)(() => {
    if (!designs) return [];
    let filteredDesigns = designs;
    if (debouncedSearchQuery) {
      filteredDesigns = filteredDesigns.filter(design => {
        const queryLowerCase = searchQuery.toLocaleLowerCase();
        const titleMatch = design.title.toLocaleLowerCase().indexOf(queryLowerCase) !== -1;
        const tagMatch = design.tag ? Object.values(design.tag).some(tag => tag.name.toLocaleLowerCase().indexOf(queryLowerCase) !== -1) : '';
        return titleMatch || tagMatch;
      });
    }
    const filter = filters[tab];
    if (filter) {
      filteredDesigns = filteredDesigns.filter(filteredDesign => {
        const matchCategory = Object.values(filteredDesign.cw_categories ? filteredDesign.cw_categories : []).some(category => category.name.toLocaleLowerCase() === filter.toLocaleLowerCase());
        const matchTag = filteredDesign.tag ? Object.values(filteredDesign.tag).some(tag => tag.name.toLocaleLowerCase() === filter.toLocaleLowerCase()) : '';
        return matchCategory || matchTag;
      });
    }
    const plan = plans[tab];
    if (plan) {
      filteredDesigns = filteredDesigns.filter(design => {
        const desingPlan = design.meta.freevspro === '1' ? 'pro' : 'free';
        const matchPlan = plan === desingPlan.toLocaleLowerCase();
        return matchPlan;
      });
    }
    return filteredDesigns;
  }, [designs, filters, debouncedSearchQuery, tab, plans, searchQuery]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__content"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("aside", {
    className: "cw-pattern-library__sidebar"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__sidebar-sticky"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.Search, {
    handleSearch: handleSearch,
    searchQuery: searchQuery
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.Category, {
    categories: categories,
    handleCategory: value => handleFilterChange(value, tab),
    designCount: designs.length,
    selectedCategory: filters[tab]
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__main"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__topbar"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.TopBar, {
    plan: plans[tab],
    planOptions: planOptions,
    handleLayoutChange: handleLayoutChange,
    handlePlanChange: value => handlePlanChange(value, tab),
    selectedLayout: layout
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__design"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__design-wrap"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.PatternList, {
    designs: filteredDesigns,
    selectedLayout: layout
  })))));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PatternLibrary);

/***/ }),

/***/ "./src/pattern-library/PatternModal.jsx":
/*!**********************************************!*\
  !*** ./src/pattern-library/PatternModal.jsx ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! components */ "./src/components/index.js");
/* harmony import */ var _PatternLibrary__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./PatternLibrary */ "./src/pattern-library/PatternLibrary.jsx");
/* harmony import */ var utils__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! utils */ "./src/utils/index.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var utils_hooks__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! utils/hooks */ "./src/utils/hooks/hooks.js");








function PatternModal({
  onClose,
  fullScreen,
  className
}) {
  const [sync, setSync] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(false);
  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(false);
  const [isError, setIsError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(false);
  const [patterns, setPatterns] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)([]);
  const [favPattern, setFavPattern] = (0,utils_hooks__WEBPACK_IMPORTED_MODULE_7__.useLocalStorage)('cw-fav-patterns', [], false);

  // Fetch patterns
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useEffect)(() => {
    const controller = new AbortController();
    setIsLoading(true);
    if (sync) {
      setPatterns([]);
    }
    (0,utils__WEBPACK_IMPORTED_MODULE_5__.fetchDesigns)(sync, controller).then(response => {
      if (response === 'error') {
        setIsError(true);
      } else {
        setPatterns(response);
      }
    }).finally(() => {
      setSync(false);
      setIsLoading(false);
    });
    return () => controller.abort();
  }, [sync]);

  // wrap handleSync function with useCallback to avoid unnecessary re-renders
  const handleSync = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useCallback)(value => {
    setSync(value);
  }, []);
  const modalContextValue = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useMemo)(() => ({
    sync,
    handleSync,
    onClose
  }), [sync, handleSync, onClose]);
  function filterCategories(designs) {
    return designs.map(pattern => pattern.cw_categories ? pattern.cw_categories : []);
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Modal, {
    onRequestClose: onClose,
    isFullScreen: fullScreen,
    className: className,
    title: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.Icon, {
      icon: "wp-travel-engine-color",
      className: "logo"
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.VisuallyHidden, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('CW Pattern Library', 'cw-pattern-import')))
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cw-pattern-library__wrap"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TabPanel, {
    className: "cw-pattern-library__tabs",
    activeClass: "patterns",
    tabs: [{
      name: 'patterns',
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Patterns', 'cw-pattern-import'),
      className: 'cw-tab-btn patterns transform-scale'
    }, {
      name: 'page',
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Page', 'cw-pattern-import'),
      className: 'cw-tab-btn page transform-scale'
    }, {
      name: 'saved',
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Favorites', 'cw-pattern-import'),
      className: 'cw-tab-btn saved transform-scale'
    }]
  }, tab => {
    if (isError) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "cw-pattern-library__center"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_3__.Icon, {
        icon: "error"
      }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Something went wrong please try again', 'cw-pattern-import')));
    }
    if (isLoading || sync) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "cw-pattern-library__center"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Spinner, null));
    }
    let designs;
    if (tab.name === 'patterns') {
      designs = patterns.filter(pattern => pattern.meta.fullpage === '');
    } else if (tab.name === 'page') {
      designs = patterns.filter(design => design.meta.fullpage === '1');
    } else {
      designs = favPattern;
    }
    const categories = filterCategories(designs);
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(utils_hooks__WEBPACK_IMPORTED_MODULE_7__.ModalContext.Provider, {
      value: modalContextValue
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_PatternLibrary__WEBPACK_IMPORTED_MODULE_4__["default"], {
      tab: tab.name,
      designs: designs,
      categories: categories
    }));
  })));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PatternModal);

/***/ }),

/***/ "./src/pattern-library/ToolbarWrap.jsx":
/*!*********************************************!*\
  !*** ./src/pattern-library/ToolbarWrap.jsx ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _PatternModal__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./PatternModal */ "./src/pattern-library/PatternModal.jsx");
/* harmony import */ var components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! components */ "./src/components/index.js");






function ToolbarWrap() {
  const [isModalOpen, setIsModalOpen] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToolbarButton, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Open CW Pattern Library', 'cw-pattern-import'),
    onClick: () => {
      setIsModalOpen(true);
    },
    className: "cw-pattern-toolbar-btn"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(components__WEBPACK_IMPORTED_MODULE_5__.Icon, {
    icon: "wp-travel-engine-white"
  }), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Pattern Engine', 'cw-pattern-import')), isModalOpen && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_PatternModal__WEBPACK_IMPORTED_MODULE_4__["default"], {
    onClose: () => setIsModalOpen(false),
    fullScreen: true,
    className: "cw-pattern-library"
  }));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ToolbarWrap);

/***/ }),

/***/ "./src/pattern-library/toolbar.js":
/*!****************************************!*\
  !*** ./src/pattern-library/toolbar.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _ToolbarWrap_jsx__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ToolbarWrap.jsx */ "./src/pattern-library/ToolbarWrap.jsx");
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/dom-ready */ "@wordpress/dom-ready");
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_5__);






function PatternToolbar() {
  const toolbarWrap = document.createElement('div');
  toolbarWrap.classList.add('cw-toolbar-wrapper');
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createRoot)(toolbarWrap).render((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ToolbarWrap_jsx__WEBPACK_IMPORTED_MODULE_4__["default"], null));
  (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.subscribe)(() => {
    const toolbar = document.querySelector('.edit-post-header-toolbar');
    if (!toolbar) {
      return;
    }
    if (!toolbar.querySelector('.cw-toolbar-wrapper')) {
      toolbar.appendChild(toolbarWrap);
    }
  });
}
_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_5___default()(PatternToolbar);

/***/ }),

/***/ "./src/style/index.scss":
/*!******************************!*\
  !*** ./src/style/index.scss ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/utils/fetch.js":
/*!****************************!*\
  !*** ./src/utils/fetch.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   fetchDesigns: () => (/* binding */ fetchDesigns)
/* harmony export */ });
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1__);


let patterns = null;
async function fetchDesigns(sync, controller) {
  if (!patterns || sync) {
    try {
      const res = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
        path: (0,_wordpress_url__WEBPACK_IMPORTED_MODULE_0__.addQueryArgs)(`/block-patterns/v1/patterns`, {
          sync: sync
        }),
        signal: controller?.signal
      });
      patterns = await res;
    } catch (error) {
      let message;
      if (error.name === 'AbortError') {
        message = 'Request has been aborted';
        console.log(message);
      } else {
        console.error(error);
        message = 'An error occurred while fetching designs';
      }
      console.error(`ERROR: ${message}`);
      return 'error';
    }
  }
  return patterns;
}

/***/ }),

/***/ "./src/utils/getPlugins.jsx":
/*!**********************************!*\
  !*** ./src/utils/getPlugins.jsx ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   GetAllPlugins: () => (/* binding */ GetAllPlugins),
/* harmony export */   getPlugins: () => (/* binding */ getPlugins)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);

function GetAllPlugins() {
  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.useSelect)(select => select('core').getPlugins(), []);
}
function getPlugins(status) {
  const plugins = GetAllPlugins()?.filter(plugin => plugin.status === status).map(plugin => {
    return {
      name: plugin.name,
      slug: plugin.plugin.split('/')[0]
    };
  });
  return plugins ? plugins : [];
}

/***/ }),

/***/ "./src/utils/hooks/hooks.js":
/*!**********************************!*\
  !*** ./src/utils/hooks/hooks.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ModalContext: () => (/* reexport safe */ _useModalContext_jsx__WEBPACK_IMPORTED_MODULE_2__.ModalContext),
/* harmony export */   useDebounce: () => (/* reexport safe */ _usedebounce_jsx__WEBPACK_IMPORTED_MODULE_1__["default"]),
/* harmony export */   useLocalStorage: () => (/* reexport safe */ _useLocalStorage_jsx__WEBPACK_IMPORTED_MODULE_0__["default"]),
/* harmony export */   useModalContext: () => (/* reexport safe */ _useModalContext_jsx__WEBPACK_IMPORTED_MODULE_2__.useModalContext)
/* harmony export */ });
/* harmony import */ var _useLocalStorage_jsx__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./useLocalStorage.jsx */ "./src/utils/hooks/useLocalStorage.jsx");
/* harmony import */ var _usedebounce_jsx__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./usedebounce.jsx */ "./src/utils/hooks/usedebounce.jsx");
/* harmony import */ var _useModalContext_jsx__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./useModalContext.jsx */ "./src/utils/hooks/useModalContext.jsx");




/***/ }),

/***/ "./src/utils/hooks/useLocalStorage.jsx":
/*!*********************************************!*\
  !*** ./src/utils/hooks/useLocalStorage.jsx ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

function useLocalStorage(key, defaultValue, dispatch) {
  const [value, setValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(() => {
    let currentValue;
    try {
      currentValue = JSON.parse(localStorage.getItem(key) || String(defaultValue));
    } catch (error) {
      currentValue = defaultValue;
    }
    return currentValue;
  });
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const handleStorageChange = e => {
      if (e.storageArea === localStorage && e.key === key) {
        try {
          const newValue = JSON.parse(e.newValue);
          setValue(newValue);
        } catch (error) {
          setValue(defaultValue);
        }
      }
    };
    window.addEventListener('storage', handleStorageChange);
    return () => {
      window.removeEventListener('storage', handleStorageChange);
    };
  }, [key, defaultValue]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    // dispatch a storage event from the same tab if dispatch is true
    if (dispatch) {
      const currentValue = localStorage.getItem(key);
      if (currentValue !== JSON.stringify(value)) {
        localStorage.setItem(key, JSON.stringify(value));

        // manually dispatch a storage event to trigger the storage event listener
        const storageEvent = new StorageEvent('storage', {
          key,
          newValue: JSON.stringify(value),
          storageArea: localStorage
        });
        window.dispatchEvent(storageEvent);
      }
    } else {
      localStorage.setItem(key, JSON.stringify(value));
    }
  }, [value, key]);
  return [value, setValue, dispatch];
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useLocalStorage);

/***/ }),

/***/ "./src/utils/hooks/useModalContext.jsx":
/*!*********************************************!*\
  !*** ./src/utils/hooks/useModalContext.jsx ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ModalContext: () => (/* binding */ ModalContext),
/* harmony export */   useModalContext: () => (/* binding */ useModalContext)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const ModalContext = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createContext)(undefined);
function useModalContext() {
  const modal = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useContext)(ModalContext);
  if (modal === undefined) {
    throw new Error('useModalContext must be used with a ModalContext');
  }
  return modal;
}

/***/ }),

/***/ "./src/utils/hooks/usedebounce.jsx":
/*!*****************************************!*\
  !*** ./src/utils/hooks/usedebounce.jsx ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

function useDebounce(value, delay) {
  const [debouncedValue, setDebouncedValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(value);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const handler = setTimeout(() => {
      setDebouncedValue(value);
    }, delay);
    return () => {
      clearTimeout(handler);
    };
  }, [value, delay]);
  return debouncedValue;
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDebounce);

/***/ }),

/***/ "./src/utils/index.js":
/*!****************************!*\
  !*** ./src/utils/index.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   fetchDesigns: () => (/* reexport safe */ _fetch__WEBPACK_IMPORTED_MODULE_0__.fetchDesigns),
/* harmony export */   getAllPlugins: () => (/* reexport safe */ _getPlugins__WEBPACK_IMPORTED_MODULE_2__.GetAllPlugins),
/* harmony export */   getPlugins: () => (/* reexport safe */ _getPlugins__WEBPACK_IMPORTED_MODULE_2__.getPlugins),
/* harmony export */   insertPattern: () => (/* reexport safe */ _insertPattern__WEBPACK_IMPORTED_MODULE_1__.insertPattern),
/* harmony export */   objectExistsInArray: () => (/* reexport safe */ _objectExistsInArray__WEBPACK_IMPORTED_MODULE_3__.objectExistsInArray)
/* harmony export */ });
/* harmony import */ var _fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./fetch */ "./src/utils/fetch.js");
/* harmony import */ var _insertPattern__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./insertPattern */ "./src/utils/insertPattern.js");
/* harmony import */ var _getPlugins__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./getPlugins */ "./src/utils/getPlugins.jsx");
/* harmony import */ var _objectExistsInArray__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./objectExistsInArray */ "./src/utils/objectExistsInArray.js");






/***/ }),

/***/ "./src/utils/insertPattern.js":
/*!************************************!*\
  !*** ./src/utils/insertPattern.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   insertPattern: () => (/* binding */ insertPattern)
/* harmony export */ });
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);


async function insertPattern({
  design,
  reload = false,
  closeModal = undefined,
  closePatternLibraryModal
}) {
  const controller = typeof AbortController === 'undefined' ? undefined : new AbortController();
  try {
    const url = `https://fsedemo.com/pattern-engine/wp-json/block-pattern/v1/patterns/${design.id}`;
    const res = await fetch(url, {
      method: 'GET',
      signal: controller.signal,
      headers: {
        'Content-Type': 'application/json'
      }
    });
    if (!res.ok) {
      throw new Error('Network response was not ok');
    }
    const pattern = await res.json();
    try {
      const parsedBlocks = JSON.parse(pattern.content);
      (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.dispatch)('core/block-editor').insertBlocks((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.parse)(parsedBlocks));
      if (cwAdmin.userCan.editPost && reload) {
        try {
          await (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.dispatch)('core/editor').savePost();
          if (typeof closeModal === 'function') {
            closeModal();
          }
          if (typeof closePatternLibraryModal === 'function') {
            closePatternLibraryModal();
          }
          window.location.reload();
        } catch (error) {
          console.error('Error saving post:', error);
        }
      }
      if (typeof closeModal === 'function') {
        closeModal();
      }
      if (typeof closePatternLibraryModal === 'function') {
        closePatternLibraryModal();
      }
    } catch (error) {
      console.error('Error parsing or inserting blocks:', error);
    }
  } catch (error) {
    if (error.name == 'AbortError') {
      console.error('Content fetch aborted for URL:', error.message);
    } else {
      console.error('Error fetching content from URL:', error.message);
    }
  } finally {
    controller?.abort();
  }
}

/***/ }),

/***/ "./src/utils/objectExistsInArray.js":
/*!******************************************!*\
  !*** ./src/utils/objectExistsInArray.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   objectExistsInArray: () => (/* binding */ objectExistsInArray)
/* harmony export */ });
function objectExistsInArray(obj, array) {
  return array.some(el => el.slug === obj.slug);
}

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/dom-ready":
/*!**********************************!*\
  !*** external ["wp","domReady"] ***!
  \**********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["domReady"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["url"];

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

"use strict";
module.exports = window["React"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
(() => {
"use strict";
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _pattern_library_toolbar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./pattern-library/toolbar */ "./src/pattern-library/toolbar.js");
/* harmony import */ var _style_index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style/index.scss */ "./src/style/index.scss");


})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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O1dBQ0E7V0FDQSxpQ0FBaUMsV0FBVztXQUM1QztXQUNBOzs7OztXQ1BBO1dBQ0E7V0FDQTtXQUNBO1dBQ0EseUNBQXlDLHdDQUF3QztXQUNqRjtXQUNBO1dBQ0E7Ozs7O1dDUEE7Ozs7O1dDQUE7V0FDQTtXQUNBO1dBQ0EsdURBQXVELGlCQUFpQjtXQUN4RTtXQUNBLGdEQUFnRCxhQUFhO1dBQzdEOzs7Ozs7Ozs7Ozs7OztBQ05tQyIsInNvdXJjZXMiOlsid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0Ly4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9jbGFzc25hbWVzL2luZGV4LmpzIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0Ly4vc3JjL2NvbXBvbmVudHMvRmF2QnV0dG9uL0ZhdkJ1dHRvbi5qc3giLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvY29tcG9uZW50cy9Ob3RpY2VNb2RhbC9Ob3RpY2VNb2RhbC5qc3giLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvY29tcG9uZW50cy9QYXR0ZXJuTGlzdC9QYXR0ZXJuTGlzdC5qc3giLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvY29tcG9uZW50cy9QcmV2aWV3TW9kYWwvUHJldmlld01vZGFsLmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy9jb21wb25lbnRzL1JlcXVpcmVkUGx1Z2luc01vZGFsL1JlcXVpcmVkUGx1Z2luc01vZGFsLmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy9jb21wb25lbnRzL1NlZ21lbnRlZENvbnRyb2wvU2VnbWVudGVkQ29udHJvbC5qc3giLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvY29tcG9uZW50cy9TaWRlYmFyL0NhdGVnb3J5L0NhdGVnb3J5LmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy9jb21wb25lbnRzL1NpZGViYXIvU2VhcmNoL1NlYXJjaC5qc3giLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvY29tcG9uZW50cy9Ub3BCYXIvVG9wQmFyLmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy9jb21wb25lbnRzL2ljb24uanN4Iiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0Ly4vc3JjL2NvbXBvbmVudHMvaW5kZXguanMiLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvcGF0dGVybi1saWJyYXJ5L1BhdHRlcm5MaWJyYXJ5LmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy9wYXR0ZXJuLWxpYnJhcnkvUGF0dGVybk1vZGFsLmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy9wYXR0ZXJuLWxpYnJhcnkvVG9vbGJhcldyYXAuanN4Iiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0Ly4vc3JjL3BhdHRlcm4tbGlicmFyeS90b29sYmFyLmpzIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0Ly4vc3JjL3N0eWxlL2luZGV4LnNjc3MiLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvdXRpbHMvZmV0Y2guanMiLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvdXRpbHMvZ2V0UGx1Z2lucy5qc3giLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvdXRpbHMvaG9va3MvaG9va3MuanMiLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvdXRpbHMvaG9va3MvdXNlTG9jYWxTdG9yYWdlLmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy91dGlscy9ob29rcy91c2VNb2RhbENvbnRleHQuanN4Iiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0Ly4vc3JjL3V0aWxzL2hvb2tzL3VzZWRlYm91bmNlLmpzeCIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy91dGlscy9pbmRleC5qcyIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC8uL3NyYy91dGlscy9pbnNlcnRQYXR0ZXJuLmpzIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0Ly4vc3JjL3V0aWxzL29iamVjdEV4aXN0c0luQXJyYXkuanMiLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvZXh0ZXJuYWwgd2luZG93IFtcIndwXCIsXCJhcGlGZXRjaFwiXSIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC9leHRlcm5hbCB3aW5kb3cgW1wid3BcIixcImJsb2Nrc1wiXSIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC9leHRlcm5hbCB3aW5kb3cgW1wid3BcIixcImNvbXBvbmVudHNcIl0iLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvZXh0ZXJuYWwgd2luZG93IFtcIndwXCIsXCJkYXRhXCJdIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0L2V4dGVybmFsIHdpbmRvdyBbXCJ3cFwiLFwiZG9tUmVhZHlcIl0iLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvZXh0ZXJuYWwgd2luZG93IFtcIndwXCIsXCJlbGVtZW50XCJdIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0L2V4dGVybmFsIHdpbmRvdyBbXCJ3cFwiLFwiaTE4blwiXSIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC9leHRlcm5hbCB3aW5kb3cgW1wid3BcIixcInVybFwiXSIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC9leHRlcm5hbCB3aW5kb3cgXCJSZWFjdFwiIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0L3dlYnBhY2svYm9vdHN0cmFwIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0L3dlYnBhY2svcnVudGltZS9jb21wYXQgZ2V0IGRlZmF1bHQgZXhwb3J0Iiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0L3dlYnBhY2svcnVudGltZS9kZWZpbmUgcHJvcGVydHkgZ2V0dGVycyIsIndlYnBhY2s6Ly9jdy1wYXR0ZXJuLWltcG9ydC93ZWJwYWNrL3J1bnRpbWUvaGFzT3duUHJvcGVydHkgc2hvcnRoYW5kIiwid2VicGFjazovL2N3LXBhdHRlcm4taW1wb3J0L3dlYnBhY2svcnVudGltZS9tYWtlIG5hbWVzcGFjZSBvYmplY3QiLCJ3ZWJwYWNrOi8vY3ctcGF0dGVybi1pbXBvcnQvLi9zcmMvaW5kZXguanMiXSwic291cmNlc0NvbnRlbnQiOlsiLyohXG5cdENvcHlyaWdodCAoYykgMjAxOCBKZWQgV2F0c29uLlxuXHRMaWNlbnNlZCB1bmRlciB0aGUgTUlUIExpY2Vuc2UgKE1JVCksIHNlZVxuXHRodHRwOi8vamVkd2F0c29uLmdpdGh1Yi5pby9jbGFzc25hbWVzXG4qL1xuLyogZ2xvYmFsIGRlZmluZSAqL1xuXG4oZnVuY3Rpb24gKCkge1xuXHQndXNlIHN0cmljdCc7XG5cblx0dmFyIGhhc093biA9IHt9Lmhhc093blByb3BlcnR5O1xuXG5cdGZ1bmN0aW9uIGNsYXNzTmFtZXMgKCkge1xuXHRcdHZhciBjbGFzc2VzID0gJyc7XG5cblx0XHRmb3IgKHZhciBpID0gMDsgaSA8IGFyZ3VtZW50cy5sZW5ndGg7IGkrKykge1xuXHRcdFx0dmFyIGFyZyA9IGFyZ3VtZW50c1tpXTtcblx0XHRcdGlmIChhcmcpIHtcblx0XHRcdFx0Y2xhc3NlcyA9IGFwcGVuZENsYXNzKGNsYXNzZXMsIHBhcnNlVmFsdWUoYXJnKSk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0cmV0dXJuIGNsYXNzZXM7XG5cdH1cblxuXHRmdW5jdGlvbiBwYXJzZVZhbHVlIChhcmcpIHtcblx0XHRpZiAodHlwZW9mIGFyZyA9PT0gJ3N0cmluZycgfHwgdHlwZW9mIGFyZyA9PT0gJ251bWJlcicpIHtcblx0XHRcdHJldHVybiBhcmc7XG5cdFx0fVxuXG5cdFx0aWYgKHR5cGVvZiBhcmcgIT09ICdvYmplY3QnKSB7XG5cdFx0XHRyZXR1cm4gJyc7XG5cdFx0fVxuXG5cdFx0aWYgKEFycmF5LmlzQXJyYXkoYXJnKSkge1xuXHRcdFx0cmV0dXJuIGNsYXNzTmFtZXMuYXBwbHkobnVsbCwgYXJnKTtcblx0XHR9XG5cblx0XHRpZiAoYXJnLnRvU3RyaW5nICE9PSBPYmplY3QucHJvdG90eXBlLnRvU3RyaW5nICYmICFhcmcudG9TdHJpbmcudG9TdHJpbmcoKS5pbmNsdWRlcygnW25hdGl2ZSBjb2RlXScpKSB7XG5cdFx0XHRyZXR1cm4gYXJnLnRvU3RyaW5nKCk7XG5cdFx0fVxuXG5cdFx0dmFyIGNsYXNzZXMgPSAnJztcblxuXHRcdGZvciAodmFyIGtleSBpbiBhcmcpIHtcblx0XHRcdGlmIChoYXNPd24uY2FsbChhcmcsIGtleSkgJiYgYXJnW2tleV0pIHtcblx0XHRcdFx0Y2xhc3NlcyA9IGFwcGVuZENsYXNzKGNsYXNzZXMsIGtleSk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0cmV0dXJuIGNsYXNzZXM7XG5cdH1cblxuXHRmdW5jdGlvbiBhcHBlbmRDbGFzcyAodmFsdWUsIG5ld0NsYXNzKSB7XG5cdFx0aWYgKCFuZXdDbGFzcykge1xuXHRcdFx0cmV0dXJuIHZhbHVlO1xuXHRcdH1cblx0XG5cdFx0aWYgKHZhbHVlKSB7XG5cdFx0XHRyZXR1cm4gdmFsdWUgKyAnICcgKyBuZXdDbGFzcztcblx0XHR9XG5cdFxuXHRcdHJldHVybiB2YWx1ZSArIG5ld0NsYXNzO1xuXHR9XG5cblx0aWYgKHR5cGVvZiBtb2R1bGUgIT09ICd1bmRlZmluZWQnICYmIG1vZHVsZS5leHBvcnRzKSB7XG5cdFx0Y2xhc3NOYW1lcy5kZWZhdWx0ID0gY2xhc3NOYW1lcztcblx0XHRtb2R1bGUuZXhwb3J0cyA9IGNsYXNzTmFtZXM7XG5cdH0gZWxzZSBpZiAodHlwZW9mIGRlZmluZSA9PT0gJ2Z1bmN0aW9uJyAmJiB0eXBlb2YgZGVmaW5lLmFtZCA9PT0gJ29iamVjdCcgJiYgZGVmaW5lLmFtZCkge1xuXHRcdC8vIHJlZ2lzdGVyIGFzICdjbGFzc25hbWVzJywgY29uc2lzdGVudCB3aXRoIG5wbSBwYWNrYWdlIG5hbWVcblx0XHRkZWZpbmUoJ2NsYXNzbmFtZXMnLCBbXSwgZnVuY3Rpb24gKCkge1xuXHRcdFx0cmV0dXJuIGNsYXNzTmFtZXM7XG5cdFx0fSk7XG5cdH0gZWxzZSB7XG5cdFx0d2luZG93LmNsYXNzTmFtZXMgPSBjbGFzc05hbWVzO1xuXHR9XG59KCkpO1xuIiwiaW1wb3J0IHsgSWNvbiB9IGZyb20gJ2NvbXBvbmVudHMnO1xuaW1wb3J0IHsgQnV0dG9uIH0gZnJvbSAnQHdvcmRwcmVzcy9jb21wb25lbnRzJztcbmltcG9ydCB7IF9fIH0gZnJvbSAnQHdvcmRwcmVzcy9pMThuJztcblxuZnVuY3Rpb24gRmF2QnV0dG9uKCB7IGljb24sIGhhbmRsZUNsaWNrIH0gKSB7XG5cdHJldHVybiAoXG5cdFx0PEJ1dHRvblxuXHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X19kZXNpZ24tZmF2IHRyYW5zZm9ybS1zY2FsZVwiXG5cdFx0XHRvbkNsaWNrPXsgaGFuZGxlQ2xpY2sgfVxuXHRcdFx0bGFiZWw9eyBfXyggJ0Zhdm91cml0ZScsICdjdy1wYXR0ZXJuLWltcG9ydCcgKSB9XG5cdFx0PlxuXHRcdFx0PEljb24gaWNvbj17IGljb24gfSAvPlxuXHRcdDwvQnV0dG9uPlxuXHQpO1xufVxuXG5leHBvcnQgZGVmYXVsdCBGYXZCdXR0b247XG4iLCJpbXBvcnQgeyBNb2RhbCB9IGZyb20gJ0B3b3JkcHJlc3MvY29tcG9uZW50cyc7XG5pbXBvcnQgeyBfXyB9IGZyb20gJ0B3b3JkcHJlc3MvaTE4bic7XG5cbmZ1bmN0aW9uIE5vdGljZU1vZGFsKCB7IGNsb3NlTW9kYWwgfSApIHtcblx0cmV0dXJuIChcblx0XHQ8TW9kYWwgb25SZXF1ZXN0Q2xvc2U9eyBjbG9zZU1vZGFsIH0+XG5cdFx0XHQ8cD5cblx0XHRcdFx0eyBfXyhcblx0XHRcdFx0XHQnVGhlIHNlbGVjdGVkIHBhdHRlcm4gcmVxdWlyZXMgcGx1Z2lucywgd2hpY2ggdW5mb3J0dW5hdGVseSB0aGUgY3VycmVudCB1c2VyIGRvZXMgbm90IGhhdmUgdGhlIHBlcm1pc3Npb24gdG8gaW5zdGFsbCBhbmQgYWN0aXZhdGUuIFBsZWFzZSBpbmZvcm0gdGhlIGFkbWluIHRvIHByb3ZpZGUgdGhlIG5lY2Vzc2FyeSBwZXJtaXNzaW9uIG9yIGluc3RhbGwgYW5kIGFjdGl2YXRlIHRoZSByZXF1aXJlZCBwbHVnaW5zLicsXG5cdFx0XHRcdFx0J2N3LXBhdHRlcm4taW1wb3J0J1xuXHRcdFx0XHQpIH1cblx0XHRcdDwvcD5cblx0XHQ8L01vZGFsPlxuXHQpO1xufVxuXG5leHBvcnQgZGVmYXVsdCBOb3RpY2VNb2RhbDtcbiIsImltcG9ydCB7IEJ1dHRvbiwgVmlzdWFsbHlIaWRkZW4gfSBmcm9tICdAd29yZHByZXNzL2NvbXBvbmVudHMnO1xuaW1wb3J0IHsgX18gfSBmcm9tICdAd29yZHByZXNzL2kxOG4nO1xuaW1wb3J0IGNsYXNzTmFtZXMgZnJvbSAnY2xhc3NuYW1lcyc7XG5pbXBvcnQgeyB1c2VMb2NhbFN0b3JhZ2UsIHVzZU1vZGFsQ29udGV4dCB9IGZyb20gJ3V0aWxzL2hvb2tzJztcbmltcG9ydCB7IFJlcXVpcmVkUGx1Z2luc01vZGFsLCBGYXZCdXR0b24sIEljb24sIFByZXZpZXdNb2RhbCB9IGZyb20gJ2NvbXBvbmVudHMnO1xuaW1wb3J0IHsgdXNlU3RhdGUsIHVzZUVmZmVjdCB9IGZyb20gJ0B3b3JkcHJlc3MvZWxlbWVudCc7XG5pbXBvcnQgeyBpbnNlcnRQYXR0ZXJuLCBvYmplY3RFeGlzdHNJbkFycmF5IH0gZnJvbSAndXRpbHMnO1xuXG5mdW5jdGlvbiBQYXR0ZXJuTGlzdCh7IGRlc2lnbnMsIHNlbGVjdGVkTGF5b3V0IH0pIHtcblx0Y29uc3QgW2ZhdlBhdHRlcm4sIHNldEZhdlBhdHRlcm5dID0gdXNlTG9jYWxTdG9yYWdlKFxuXHRcdCdjdy1mYXYtcGF0dGVybnMnLFxuXHRcdFtdLFxuXHRcdHRydWVcblx0KTtcblx0Y29uc3QgW2Rlc2lnbnNMaXN0LCBzZXREZXNpZ25zTGlzdF0gPSB1c2VTdGF0ZShbXSk7XG5cdGNvbnN0IFtpc09wZW4sIHNldE9wZW5dID0gdXNlU3RhdGUoZmFsc2UpO1xuXHRjb25zdCBbcmVxdWlyZWRQbHVnaW5zLCBzZXRSZXF1aXJlZFBsdWdpbnNdID0gdXNlU3RhdGUoW10pO1xuXHRjb25zdCBbZGVzaWduVG9JbXBvcnQsIHNldERlc2lnblRvSW1wb3J0XSA9IHVzZVN0YXRlKG51bGwpO1xuXHRjb25zdCBbaXNNb2RhbE9wZW4sIHNldElzUHJldmlld09wZW5dID0gdXNlU3RhdGUoZmFsc2UpO1xuXHRjb25zdCBbaWZyYW1lTGluaywgc2V0SWZyYW1lTGlua10gPSB1c2VTdGF0ZShudWxsKTtcblxuXHRjb25zdCBbZGlzYWJsZWRCdXR0b25zLCBzZXREaXNhYmxlZEJ1dHRvbnNdID0gdXNlU3RhdGUoe30pO1xuXG5cdGNvbnN0IHsgb25DbG9zZTogY2xvc2VQYXR0ZXJuTGlicmFyeU1vZGFsIH0gPSB1c2VNb2RhbENvbnRleHQoKTtcblxuXHRmdW5jdGlvbiBjbG9zZU1vZGFsKCkge1xuXHRcdHNldE9wZW4oZmFsc2UpO1xuXHR9XG5cblx0dXNlRWZmZWN0KCgpID0+IHtcblx0XHRpZiAoZGVzaWducy5sZW5ndGggIT09IDApIHtcblx0XHRcdGNvbnN0IGRlc2lnbnNMaXN0ID0gZGVzaWducy5tYXAoXG5cdFx0XHRcdCh7IHRpdGxlLCBmZWF0dXJlZF9tZWRpYSwgaWQsIHBlcm1hbGluaywgLi4uZGF0YSB9KSA9PiB7XG5cdFx0XHRcdFx0bGV0IGZhdkljb24gPSAnaGVhcnRfb3V0bGluZSc7XG5cdFx0XHRcdFx0ZmF2UGF0dGVybi5maW5kKChwYXR0ZXJuKSA9PiB7XG5cdFx0XHRcdFx0XHRpZiAocGF0dGVybi5pZCA9PT0gaWQpIHtcblx0XHRcdFx0XHRcdFx0cmV0dXJuIChmYXZJY29uID0gJ2hlYXJ0X2ZpbGwnKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9KTtcblxuXHRcdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0XHQ8bGlcblx0XHRcdFx0XHRcdFx0a2V5PXtpZH1cblx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X19kZXNpZ24taXRlbVwiXG5cdFx0XHRcdFx0XHQ+XG5cdFx0XHRcdFx0XHRcdDxGYXZCdXR0b25cblx0XHRcdFx0XHRcdFx0XHRpY29uPXtmYXZJY29ufVxuXHRcdFx0XHRcdFx0XHRcdGhhbmRsZUNsaWNrPXsoKSA9PiB0b2dnbGVGYXYoaWQpfVxuXHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHR7ZmVhdHVyZWRfbWVkaWEgIT09IHVuZGVmaW5lZCA/IChcblx0XHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9fZGVzaWduLWl0ZW0taW1nXCI+XG5cdFx0XHRcdFx0XHRcdFx0XHQ8aW1nXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHNyYz17YCR7ZmVhdHVyZWRfbWVkaWF9YH1cblx0XHRcdFx0XHRcdFx0XHRcdFx0bG9hZGluZz1cImxhenlcIlxuXHRcdFx0XHRcdFx0XHRcdFx0XHRjbGFzc05hbWU9Jydcblx0XHRcdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0XHRcdCkgOiAoXG5cdFx0XHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX2Rlc2lnbi1pdGVtLWltZyBuby1mZWF0dXJlZC1pbWdcIj5cblx0XHRcdFx0XHRcdFx0XHRcdDxJY29uIGljb249XCJpbWdcIiAvPlxuXHRcdFx0XHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHRcdFx0XHQpfVxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9fZGVzaWduLWl0ZW0taW5mb1wiPlxuXHRcdFx0XHRcdFx0XHRcdDxWaXN1YWxseUhpZGRlbj5cblx0XHRcdFx0XHRcdFx0XHRcdDxsYWJlbD57dGl0bGV9PC9sYWJlbD5cblx0XHRcdFx0XHRcdFx0XHQ8L1Zpc3VhbGx5SGlkZGVuPlxuXHRcdFx0XHRcdFx0XHRcdDxidXR0b25cblx0XHRcdFx0XHRcdFx0XHRcdGNsYXNzTmFtZT0nY3ctcGF0dGVybi1saWJyYXJ5X19kZXNpZ24tcHJldmlldyB0cmFuc2Zvcm0tc2NhbGUnXG5cdFx0XHRcdFx0XHRcdFx0XHRvbkNsaWNrPXsoKSA9PiBoYW5kbGVQcmV2aWV3TW9kYWwocGVybWFsaW5rKX1cblx0XHRcdFx0XHRcdFx0XHRcdGFyaWEtbGFiZWw9e19fKCdQcmV2aWV3JywgJ2N3LXBhdHRlcm4taW1wb3J0Jyl9XG5cdFx0XHRcdFx0XHRcdFx0PlxuXHRcdFx0XHRcdFx0XHRcdFx0PEljb24gaWNvbj1cImV5ZVwiIC8+XG5cdFx0XHRcdFx0XHRcdFx0XHR7X18oJ1ByZWl2ZXcnLCAnY3ctcGF0dGVybi1pbXBvcnQnKX1cblx0XHRcdFx0XHRcdFx0XHQ8L2J1dHRvbj5cblx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRkYXRhLm1ldGEuZnJlZXZzcHJvID09PSAnMScgJiYgY3dBZG1pbi5saWNlbnNlX2tleSA9PT0gXCJ2YWxpZFwiID8gKFxuXHRcdFx0XHRcdFx0XHRcdFx0XHQ8SW1wb3J0QnV0dG9uIGlkPXtpZH0gLz5cblx0XHRcdFx0XHRcdFx0XHRcdCkgOiBkYXRhLm1ldGEuZnJlZXZzcHJvID09PSAnJyA/IChcblx0XHRcdFx0XHRcdFx0XHRcdFx0PEltcG9ydEJ1dHRvbiBpZD17aWR9IC8+XG5cdFx0XHRcdFx0XHRcdFx0XHQpIDogKFxuXHRcdFx0XHRcdFx0XHRcdFx0XHQ8YSBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX2Rlc2lnbi1idG4gY3ctcGF0dGVybi1wcm8gY3ctcGF0dGVybi1saWJyYXJ5LWJ0bi1pcy1wcmltYXJ5IHRyYW5zZm9ybS1zY2FsZVwiIGhyZWY9XCJodHRwczovL3dwdHJhdmVsZW5naW5lLmNvbS9wYXR0ZXJuLWVuZ2luZS8/dXRtX3NvdXJjZT1mcmVlX3BsdWdpbiZ1dG1fbWVkaXVtPXBhdHRlcm5faW1wb3J0ZXImdXRtX2NhbXBhaWduPXVwZ3JhZGVfdG9fcHJvXCI+XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0e19fKCdCdXkgUHJvJywgJ2N3LXBhdHRlcm4taW1wb3J0Jyl9XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0PEljb24gaWNvbj1cImNyb3duXCIgLz5cblx0XHRcdFx0XHRcdFx0XHRcdFx0PC9hPlxuXHRcdFx0XHRcdFx0XHRcdFx0KVxuXHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0XHQ8L2xpPlxuXHRcdFx0XHRcdCk7XG5cdFx0XHRcdH1cblx0XHRcdCk7XG5cdFx0XHRzZXREZXNpZ25zTGlzdChkZXNpZ25zTGlzdCk7XG5cdFx0fVxuXHR9LCBbZGVzaWducywgZGlzYWJsZWRCdXR0b25zXSk7XG5cblx0aWYgKGRlc2lnbnMubGVuZ3RoID09PSAwKSB7XG5cdFx0cmV0dXJuIChcblx0XHRcdDxwIGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9faGVscFwiPlxuXHRcdFx0XHR7X18oJ05vIHBhdHRlcm5zIGZvdW5kJywgJ2N3LXBhdHRlcm4taW1wb3J0Jyl9XG5cdFx0XHQ8L3A+XG5cdFx0KTtcblx0fVxuXG5cdGNvbnN0IGRlc2lnbkxpc3RDbGFzcyA9IGNsYXNzTmFtZXMoXG5cdFx0J2N3LXBhdHRlcm4tbGlicmFyeV9fZGVzaWduLWxpc3QnLFxuXHRcdHNlbGVjdGVkTGF5b3V0ICYmXG5cdFx0YGN3LXBhdHRlcm4tbGlicmFyeV9fZGVzaWduLWxpc3QtY29sJHtzZWxlY3RlZExheW91dH1gXG5cdCk7XG5cblx0ZnVuY3Rpb24gaGFuZGxlSW5zZXJ0KHNlbGVjdGVkSWQpIHtcblx0XHRkZXNpZ25zLm1hcCgoZGVzaWduKSA9PiB7XG5cdFx0XHRpZiAoZGVzaWduLmlkID09PSBzZWxlY3RlZElkKSB7XG5cdFx0XHRcdC8vIENoZWNrIGlmIHJlcXVpcmVkIHBsdWdpbiBpcyBpbnN0YWxsZWQgYW5kIGFjdGl2YXRlZCAoYWN0aWVQbHVnaW5EYXRhIGlzIHRoZSBsb2NhbGl6ZWQgdmFyaWFibGUgdGhhdCBoYXMgdGhlIGxpc3Qgb2YgaW5zdGFsbGVkIGFuZCBhY3RpdmF0ZWQgcGx1Z2lucylcblx0XHRcdFx0c2V0RGlzYWJsZWRCdXR0b25zKHByZXZTdGF0ZSA9PiAoe1xuXHRcdFx0XHRcdC4uLnByZXZTdGF0ZSxcblx0XHRcdFx0XHRbc2VsZWN0ZWRJZF06IHRydWUsIFxuXHRcdFx0XHR9KSk7XG5cdFx0XHRcdGNvbnN0IGZpbHRlcmVkUmVxdWlyZWRQbHVnaW5zID1cblx0XHRcdFx0XHRkZXNpZ24ubWV0YS5yZXF1aXJlZF9wbHVnaW5zLmZpbHRlcihcblx0XHRcdFx0XHRcdChyZXF1aXJlZFBsdWdpbikgPT5cblx0XHRcdFx0XHRcdFx0IW9iamVjdEV4aXN0c0luQXJyYXkoXG5cdFx0XHRcdFx0XHRcdFx0cmVxdWlyZWRQbHVnaW4sXG5cdFx0XHRcdFx0XHRcdFx0Y3dBZG1pbi5hY3RpdmVQbHVnaW5cblx0XHRcdFx0XHRcdFx0KVxuXHRcdFx0XHRcdCk7XG5cblx0XHRcdFx0c2V0UmVxdWlyZWRQbHVnaW5zKGZpbHRlcmVkUmVxdWlyZWRQbHVnaW5zKTtcblxuXHRcdFx0XHRpZiAoZmlsdGVyZWRSZXF1aXJlZFBsdWdpbnMubGVuZ3RoID4gMCkge1xuXHRcdFx0XHRcdHNldE9wZW4odHJ1ZSk7XG5cdFx0XHRcdFx0c2V0RGVzaWduVG9JbXBvcnQoZGVzaWduKTtcblx0XHRcdFx0XHRzZXREaXNhYmxlZEJ1dHRvbnMocHJldlN0YXRlID0+ICh7XG5cdFx0XHRcdFx0XHQuLi5wcmV2U3RhdGUsXG5cdFx0XHRcdFx0XHRbc2VsZWN0ZWRJZF06IGZhbHNlLCBcblx0XHRcdFx0XHR9KSk7XG5cdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGluc2VydFBhdHRlcm4oeyBkZXNpZ246IGRlc2lnbiwgY2xvc2VQYXR0ZXJuTGlicmFyeU1vZGFsOiBjbG9zZVBhdHRlcm5MaWJyYXJ5TW9kYWwgfSlcblx0XHRcdFx0LnRoZW4oKCkgPT4ge1xuXHRcdFx0XHRcdHNldERpc2FibGVkQnV0dG9ucyhwcmV2U3RhdGUgPT4gKHtcblx0XHRcdFx0XHRcdC4uLnByZXZTdGF0ZSxcblx0XHRcdFx0XHRcdFtzZWxlY3RlZElkXTogZmFsc2UsIFxuXHRcdFx0XHRcdH0pKTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmNhdGNoKCgpID0+IHtcblx0XHRcdFx0XHRzZXREaXNhYmxlZEJ1dHRvbnMocHJldlN0YXRlID0+ICh7XG5cdFx0XHRcdFx0XHQuLi5wcmV2U3RhdGUsXG5cdFx0XHRcdFx0XHRbc2VsZWN0ZWRJZF06IGZhbHNlLCBcblx0XHRcdFx0XHR9KSk7XG5cdFx0XHRcdH0pO1xuXHRcdFx0fVxuXHRcdH0pO1xuXHR9XG5cblx0ZnVuY3Rpb24gdG9nZ2xlRmF2KGZhdklkKSB7XG5cdFx0c2V0RmF2UGF0dGVybigocHJldlBhdHRlcm5zKSA9PiB7XG5cdFx0XHRjb25zdCBkZXNpZ25JbmRleCA9IHByZXZQYXR0ZXJucy5maW5kSW5kZXgoXG5cdFx0XHRcdChkZXNpZ24pID0+IGRlc2lnbi5pZCA9PT0gZmF2SWRcblx0XHRcdCk7XG5cdFx0XHRpZiAoZGVzaWduSW5kZXggIT09IC0xKSB7XG5cdFx0XHRcdC8vIHBhdHRlcm4gaXMgYWxyZWFkeSBpbiB0aGUgZmF2b3JpdGVzIGxpc3QsIHJlbW92ZSBpdFxuXHRcdFx0XHRyZXR1cm4gcHJldlBhdHRlcm5zLmZpbHRlcihcblx0XHRcdFx0XHQoXywgaW5kZXgpID0+IGluZGV4ICE9PSBkZXNpZ25JbmRleFxuXHRcdFx0XHQpO1xuXHRcdFx0fVxuXHRcdFx0Ly8gcGF0dGVybiBpbiBub3QgaW4gdGhlIGZhdm9yaXRlcyBsaXN0LCBhZGQgaXRcblx0XHRcdHJldHVybiBbXG5cdFx0XHRcdC4uLnByZXZQYXR0ZXJucyxcblx0XHRcdFx0ZGVzaWducy5maW5kKChkZXNpZ24pID0+IGRlc2lnbi5pZCA9PT0gZmF2SWQpLFxuXHRcdFx0XTtcblx0XHR9KTtcblx0fVxuXG5cdGZ1bmN0aW9uIEltcG9ydEJ1dHRvbih7IGlkIH0pIHtcblxuXHRcdGNvbnN0IGlzRGlzYWJsZWQgPSBkaXNhYmxlZEJ1dHRvbnNbaWRdIHx8IGZhbHNlO1xuXG5cdFx0cmV0dXJuIChcblx0XHRcdDxCdXR0b25cblx0XHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X19kZXNpZ24tYnRuIGN3LXBhdHRlcm4tZnJlZSB0cmFuc2Zvcm0tc2NhbGUgY3ctcGF0dGVybi1saWJyYXJ5LWJ0bi1pcy1wcmltYXJ5XCJcblx0XHRcdFx0b25DbGljaz17KCkgPT4gaGFuZGxlSW5zZXJ0KGlkKX1cblx0XHRcdFx0bGFiZWw9e19fKCdJbXBvcnQnLCAnY3ctcGF0dGVybi1pbXBvcnQnKX1cblx0XHRcdFx0c2hvd1Rvb2x0aXA9e2ZhbHNlfVxuXHRcdFx0XHRpc0J1c3k9e2lzRGlzYWJsZWR9XG5cdFx0XHQ+XG5cdFx0XHRcdHtfXygnSW1wb3J0JywgJ2N3LXBhdHRlcm4taW1wb3J0Jyl9XG5cdFx0XHRcdDxJY29uIGljb249XCJkb3duX2Fycm93XCIgLz5cblx0XHRcdDwvQnV0dG9uPlxuXHRcdCk7XG5cdH1cblxuXHRmdW5jdGlvbiBoYW5kbGVQcmV2aWV3TW9kYWwocGVybWFsaW5rKSB7XG5cdFx0c2V0SXNQcmV2aWV3T3Blbih0cnVlKTtcblx0XHRzZXRJZnJhbWVMaW5rKHBlcm1hbGluayk7XG5cdH1cblxuXHRmdW5jdGlvbiBjbG9zZVByZXZpZXdNb2RhbCgpIHtcblx0XHRzZXRJc1ByZXZpZXdPcGVuKGZhbHNlKTtcblx0fVxuXG5cblx0cmV0dXJuIChcblx0XHQ8PlxuXHRcdFx0PHVsIGNsYXNzTmFtZT17ZGVzaWduTGlzdENsYXNzfT57ZGVzaWduc0xpc3R9PC91bD5cblx0XHRcdHtpc09wZW4gJiYgKFxuXHRcdFx0XHQ8UmVxdWlyZWRQbHVnaW5zTW9kYWxcblx0XHRcdFx0XHRjbG9zZU1vZGFsPXtjbG9zZU1vZGFsfVxuXHRcdFx0XHRcdHRpdGxlPXtfXyhcblx0XHRcdFx0XHRcdCdSZXF1aXJlZCBQbHVnaW5zJyxcblx0XHRcdFx0XHRcdCdjdy1wYXR0ZXJuLWltcG9ydCdcblx0XHRcdFx0XHQpfVxuXHRcdFx0XHRcdHBsdWdpbnM9e3JlcXVpcmVkUGx1Z2luc31cblx0XHRcdFx0XHRkZXNpZ249e2Rlc2lnblRvSW1wb3J0fVxuXHRcdFx0XHQvPlxuXHRcdFx0KX1cblx0XHRcdHtpc01vZGFsT3BlbiAmJiAoXG5cdFx0XHRcdDxQcmV2aWV3TW9kYWxcblx0XHRcdFx0XHRjbG9zZU1vZGFsPXtjbG9zZVByZXZpZXdNb2RhbH1cblx0XHRcdFx0XHRwYXR0ZXJuTGluaz17aWZyYW1lTGlua31cblx0XHRcdFx0Lz5cblx0XHRcdCl9XG5cdFx0PC8+XG5cdCk7XG59XG5cbmV4cG9ydCBkZWZhdWx0IFBhdHRlcm5MaXN0O1xuIiwiaW1wb3J0IHsgTW9kYWwsIFNwaW5uZXIgfSBmcm9tICdAd29yZHByZXNzL2NvbXBvbmVudHMnO1xuaW1wb3J0IHsgU2VnbWVudGVkQ29udHJvbCB9IGZyb20gJ2NvbXBvbmVudHMnO1xuaW1wb3J0IHsgdXNlUmVmLCB1c2VTdGF0ZSB9IGZyb20gJ0B3b3JkcHJlc3MvZWxlbWVudCc7XG5cbmZ1bmN0aW9uIFByZXZpZXdNb2RhbCggeyBjbG9zZU1vZGFsLCBwYXR0ZXJuTGluayB9ICkge1xuXHRjb25zdCBbIGlmcmFtZVdpZHRoLCBzZXRJZnJhbWVXaWR0aCBdID0gdXNlU3RhdGUoICcxMDAlJyApO1xuXHRjb25zdCBbIGlzTG9hZGluZywgc2V0SXNMb2FkaW5nIF0gPSB1c2VTdGF0ZSggdHJ1ZSApO1xuXG5cdGZ1bmN0aW9uIGhhbmRsZVdpbmRvd1dpZHRoKCB2YWwgKSB7XG5cdFx0aWYgKCB2YWwgPT09ICdkZXNrdG9wJyApIHtcblx0XHRcdHNldElmcmFtZVdpZHRoKCAnMTAwJScgKTtcblx0XHR9IGVsc2UgaWYgKCB2YWwgPT09ICd0YWJsZXQnICkge1xuXHRcdFx0c2V0SWZyYW1lV2lkdGgoICc3NjhweCcgKTtcblx0XHR9IGVsc2UgaWYgKCB2YWwgPT09ICdtb2JpbGUnICkge1xuXHRcdFx0c2V0SWZyYW1lV2lkdGgoICc1MDBweCcgKTtcblx0XHR9XG5cdH1cblxuXHRyZXR1cm4gKFxuXHRcdDxNb2RhbFxuXHRcdFx0b25SZXF1ZXN0Q2xvc2U9eyBjbG9zZU1vZGFsIH1cblx0XHRcdGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeS1wcmV2aWV3XCJcblx0XHRcdGlzRnVsbFNjcmVlbj17IHRydWUgfVxuXHRcdFx0c2hvdWxkQ2xvc2VPbkNsaWNrT3V0c2lkZT17IGZhbHNlIH1cblx0XHQ+XG5cdFx0XHQ8PlxuXHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeS1wcmV2aWV3LWhlYWRlclwiPlxuXHRcdFx0XHRcdDxTZWdtZW50ZWRDb250cm9sXG5cdFx0XHRcdFx0XHRuYW1lPVwiZ3JvdXAtMVwiXG5cdFx0XHRcdFx0XHRjYWxsYmFjaz17IGhhbmRsZVdpbmRvd1dpZHRoIH1cblx0XHRcdFx0XHRcdGRlZmF1bHRJbmRleD17IDAgfVxuXHRcdFx0XHRcdFx0Y29udHJvbFJlZj17IHVzZVJlZigpIH1cblx0XHRcdFx0XHRcdHNlZ21lbnRzPXsgW1xuXHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0bGFiZWw6ICdEZXNrdG9wJyxcblx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogJ2Rlc2t0b3AnLFxuXHRcdFx0XHRcdFx0XHRcdGljb246ICdkZXNrdG9wJyxcblx0XHRcdFx0XHRcdFx0XHRyZWY6IHVzZVJlZigpLFxuXHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0bGFiZWw6ICdUYWJsZXQnLFxuXHRcdFx0XHRcdFx0XHRcdHZhbHVlOiAndGFibGV0Jyxcblx0XHRcdFx0XHRcdFx0XHRpY29uOiAndGFibGV0Jyxcblx0XHRcdFx0XHRcdFx0XHRyZWY6IHVzZVJlZigpLFxuXHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0bGFiZWw6ICdNb2JpbGUnLFxuXHRcdFx0XHRcdFx0XHRcdHZhbHVlOiAnbW9iaWxlJyxcblx0XHRcdFx0XHRcdFx0XHRpY29uOiAnbW9iaWxlJyxcblx0XHRcdFx0XHRcdFx0XHRyZWY6IHVzZVJlZigpLFxuXHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XSB9XG5cdFx0XHRcdFx0Lz5cblx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdDxkaXZcblx0XHRcdFx0XHRjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnktcHJldmlldy13cmFwXCJcblx0XHRcdFx0XHRzdHlsZT17IHsgJy0taWZyYW1lLXdpZHRoJzogaWZyYW1lV2lkdGggfSB9XG5cdFx0XHRcdD5cblx0XHRcdFx0XHR7IGlzTG9hZGluZyA/IDxTcGlubmVyIC8+IDogPD48Lz4gfVxuXHRcdFx0XHRcdDxpZnJhbWVcblx0XHRcdFx0XHRcdHNyYz17IHBhdHRlcm5MaW5rIH1cblx0XHRcdFx0XHRcdGxvYWRpbmc9XCJsYXp5XCJcblx0XHRcdFx0XHRcdGFsbG93RnVsbFNjcmVlblxuXHRcdFx0XHRcdFx0YWxsb3d0cmFuc3BhcmVuY3k9XCJ0cnVlXCJcblx0XHRcdFx0XHRcdHN0eWxlPXsge1xuXHRcdFx0XHRcdFx0XHRib3JkZXI6IDAsXG5cdFx0XHRcdFx0XHRcdHBvc2l0aW9uOiAnYWJzb2x1dGUnLFxuXHRcdFx0XHRcdFx0XHR0b3A6IDAsXG5cdFx0XHRcdFx0XHRcdGxlZnQ6IDAsXG5cdFx0XHRcdFx0XHRcdHdpZHRoOiAnMTAwJScsXG5cdFx0XHRcdFx0XHRcdGhlaWdodDogJzEwMCUnLFxuXHRcdFx0XHRcdFx0XHR0cmFuc2l0aW9uOiAnMC4zcycsXG5cdFx0XHRcdFx0XHR9IH1cblx0XHRcdFx0XHRcdHJlZmVycmVyUG9saWN5PVwibm8tcmVmZXJyZXItd2hlbi1kb3duZ3JhZGVcIlxuXHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5LXByZXZpZXctaWZyYW1lXCJcblx0XHRcdFx0XHRcdHRpdGxlPVwiUGF0dGVybiBwcmVpdmV3XCJcblx0XHRcdFx0XHRcdG9uTG9hZD17ICgpID0+IHNldElzTG9hZGluZyggZmFsc2UgKSB9XG5cdFx0XHRcdFx0PjwvaWZyYW1lPlxuXHRcdFx0XHQ8L2Rpdj5cblx0XHRcdDwvPlxuXHRcdDwvTW9kYWw+XG5cdCk7XG59XG5cbmV4cG9ydCBkZWZhdWx0IFByZXZpZXdNb2RhbDtcbiIsImltcG9ydCB7IE1vZGFsLCBCdXR0b24gfSBmcm9tICdAd29yZHByZXNzL2NvbXBvbmVudHMnO1xuaW1wb3J0IHsgX18gfSBmcm9tICdAd29yZHByZXNzL2kxOG4nO1xuaW1wb3J0IHsgdXNlU3RhdGUsIHVzZUVmZmVjdCwgdXNlTWVtbyB9IGZyb20gJ0B3b3JkcHJlc3MvZWxlbWVudCc7XG5pbXBvcnQgeyBpbnNlcnRQYXR0ZXJuLCBvYmplY3RFeGlzdHNJbkFycmF5IH0gZnJvbSAndXRpbHMnO1xuaW1wb3J0IHsgdXNlTW9kYWxDb250ZXh0IH0gZnJvbSAndXRpbHMvaG9va3MnO1xuXG5mdW5jdGlvbiBSZXF1aXJlZFBsdWdpbnNNb2RhbCh7XG5cdGNsb3NlTW9kYWwsXG5cdHRpdGxlLFxuXHRwbHVnaW5zLFxuXHRkZXNpZ24sXG5cdC4uLnByb3BzXG59KSB7XG5cdGNvbnN0IFtidXR0b25TdGF0ZSwgc2V0QnV0dG9uU3RhdGVdID0gdXNlU3RhdGUoJ2luc3RhbGwnKTtcblx0Y29uc3QgW2FjdGl2YXRlVXJscywgc2V0QWN0aXZhdGVVcmxzXSA9IHVzZVN0YXRlKFtdKTtcblxuXHRjb25zdCB7IG9uQ2xvc2U6IGNsb3NlUGF0dGVybkxpYnJhcnlNb2RhbCB9ID0gdXNlTW9kYWxDb250ZXh0KCk7XG5cblx0Y29uc3QgcGx1Z2luc0xpc3QgPSBwbHVnaW5zLm1hcCgocGx1Z2luLCBpbmRleCkgPT4ge1xuXHRcdHJldHVybiAoXG5cdFx0XHQ8bGkga2V5PXtpbmRleH0gY2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5LXBsdWdpbnMtaXRlbVwiPlxuXHRcdFx0XHR7cGx1Z2luLm5hbWV9XG5cdFx0XHQ8L2xpPlxuXHRcdCk7XG5cdH0pO1xuXG5cdC8vIE1lbW9pemUgdGhlIHBsdWdpbnNUb0luc3RhbGwgYXJyYXkgdG8gcHJldmVudCB1bm5lY2Vzc2FyeSByZS1yZW5kZXJzXG5cdGNvbnN0IHBsdWdpbnNUb0luc3RhbGwgPSB1c2VNZW1vKCgpID0+IHtcblx0XHRpZiAoY3dBZG1pbi51c2VyQ2FuLmluc3RhbGxQbHVnaW5zKSB7XG5cdFx0XHRyZXR1cm4gcGx1Z2lucy5maWx0ZXIoXG5cdFx0XHRcdChwbHVnaW4pID0+ICFvYmplY3RFeGlzdHNJbkFycmF5KHBsdWdpbiwgY3dBZG1pbi5pbmFjdGl2ZSlcblx0XHRcdCk7XG5cdFx0fVxuXHRcdHJldHVybiBbXTtcblx0fSwgW3BsdWdpbnNdKTtcblxuXHR1c2VFZmZlY3QoKCkgPT4ge1xuXHRcdGlmIChjd0FkbWluLnVzZXJDYW4uaW5zdGFsbFBsdWdpbnMgJiYgcGx1Z2luc1RvSW5zdGFsbC5sZW5ndGggPT09IDApIHtcblx0XHRcdGNvbnN0IHBsdWdpbnNUb0FjdGl2YXRlID0gY3dBZG1pbi5pbmFjdGl2ZS5maWx0ZXIoKHBsdWdpbikgPT5cblx0XHRcdFx0b2JqZWN0RXhpc3RzSW5BcnJheShwbHVnaW4sIHBsdWdpbnMpXG5cdFx0XHQpO1xuXG5cdFx0XHRjb25zdCB1cmxzID0gcGx1Z2luc1RvQWN0aXZhdGVcblx0XHRcdFx0Lm1hcCgocGx1Z2luKSA9PiBwbHVnaW4udXJsKVxuXHRcdFx0XHQubWFwKCh1cmwpID0+IHVybC5yZXBsYWNlKC8mYW1wOy9nLCAnJicpKTtcblx0XHRcdHNldEFjdGl2YXRlVXJscygocHJldlVybHMpID0+IFsuLi5wcmV2VXJscywgLi4udXJsc10pO1xuXG5cdFx0XHRzZXRCdXR0b25TdGF0ZSgnYWN0aXZhdGUnKTtcblx0XHR9XG5cdH0sIFtwbHVnaW5zVG9JbnN0YWxsLCBwbHVnaW5zXSk7XG5cblx0ZnVuY3Rpb24gaW5zdGFsbFBsdWdpbnMocGx1Z2lucykge1xuXHRcdGlmICghY3dBZG1pbi51c2VyQ2FuLmluc3RhbGxQbHVnaW5zKSB7XG5cdFx0XHRhbGVydChcblx0XHRcdFx0X18oXG5cdFx0XHRcdFx0J1RoZSBzZWxlY3RlZCBwYXR0ZXJuIHJlcXVpcmVzIHBsdWdpbnMsIHdoaWNoIHVuZm9ydHVuYXRlbHkgdGhlIGN1cnJlbnQgdXNlciBkb2VzIG5vdCBoYXZlIHRoZSBwZXJtaXNzaW9uIHRvIGluc3RhbGwgYW5kIGFjdGl2YXRlLiBQbGVhc2UgaW5mb3JtIHRoZSBhZG1pbiB0byBwcm92aWRlIHRoZSBuZWNlc3NhcnkgcGVybWlzc2lvbiBvciBpbnN0YWxsIGFuZCBhY3RpdmF0ZSB0aGUgcmVxdWlyZWQgcGx1Z2lucy4nLFxuXHRcdFx0XHRcdCdjdy1wYXR0ZXJuLWltcG9ydCdcblx0XHRcdFx0KVxuXHRcdFx0KTtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0aWYgKCF3cCkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblxuXHRcdHNldEJ1dHRvblN0YXRlKCdpbnN0YWxsaW5nJyk7XG5cdFx0Ly8gTWFwIGVhY2ggcGx1Z2luIGluc3RhbGxhdGlvbiB0byBhIHByb21pc2Vcblx0XHRjb25zdCBpbnN0YWxsUHJvbWlzZXMgPSBwbHVnaW5zLm1hcCgocGx1Z2luKSA9PiB7XG5cdFx0XHRyZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xuXHRcdFx0XHR3cC51cGRhdGVzLmluc3RhbGxQbHVnaW4oe1xuXHRcdFx0XHRcdHNsdWc6IHBsdWdpbi5zbHVnLFxuXHRcdFx0XHRcdHN1Y2Nlc3M6IChyZXNwb25zZSkgPT4ge1xuXHRcdFx0XHRcdFx0c2V0QWN0aXZhdGVVcmxzKChwcmV2VXJscykgPT4gW1xuXHRcdFx0XHRcdFx0XHQuLi5wcmV2VXJscyxcblx0XHRcdFx0XHRcdFx0cmVzcG9uc2UuYWN0aXZhdGVVcmwsXG5cdFx0XHRcdFx0XHRdKTtcblx0XHRcdFx0XHRcdHJlc29sdmUoKTtcblx0XHRcdFx0XHR9LCAvLyBSZXNvbHZlIHRoZSBwcm9taXNlIG9uIHN1Y2Nlc3Ncblx0XHRcdFx0XHRlcnJvcjogKGVycm9yKSA9PiBjb25zb2xlLmVycm9yKGVycm9yKSwgLy8gUmVqZWN0IHRoZSBwcm9taXNlIG9uIGVycm9yXG5cdFx0XHRcdH0pO1xuXHRcdFx0fSk7XG5cdFx0fSk7XG5cblx0XHQvLyBXYWl0IGZvciBhbGwgcHJvbWlzZXMgdG8gcmVzb2x2ZVxuXHRcdFByb21pc2UuYWxsKGluc3RhbGxQcm9taXNlcylcblx0XHRcdC50aGVuKCgpID0+IHtcblx0XHRcdFx0Y29uc29sZS5sb2coJ0FsbCBwbHVnaW5zIGluc3RhbGxlZCBzdWNjZXNzZnVsbHknKTtcblx0XHRcdFx0c2V0QnV0dG9uU3RhdGUoJ2FjdGl2YXRlJyk7XG5cdFx0XHR9KVxuXHRcdFx0LmNhdGNoKChlcnJvcikgPT4ge1xuXHRcdFx0XHRjb25zb2xlLmVycm9yKCdFcnJvciBpbnN0YWxsaW5nIHBsdWdpbnM6JywgZXJyb3IpO1xuXHRcdFx0fSk7XG5cdH1cblxuXHRhc3luYyBmdW5jdGlvbiBhY3RpdmF0ZVBsdWdpbnMoKSB7XG5cdFx0c2V0QnV0dG9uU3RhdGUoJ2FjdGl2YXRpbmcnKTtcblxuXHRcdGZvciAoY29uc3QgdXJsIG9mIGFjdGl2YXRlVXJscykge1xuXHRcdFx0dHJ5IHtcblx0XHRcdFx0Y29uc3QgcmVzcG9uc2UgPSBhd2FpdCBmZXRjaCh1cmwsIHtcblx0XHRcdFx0XHRtZXRob2Q6ICdQT1NUJyxcblx0XHRcdFx0XHRoZWFkZXJzOiB7XG5cdFx0XHRcdFx0XHQnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcsXG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRib2R5OiBuZXcgVVJMU2VhcmNoUGFyYW1zKHtcblx0XHRcdFx0XHRcdF93cG5vbmNlOiBuZXcgVVJMKHVybCkuc2VhcmNoUGFyYW1zLmdldCgnX3dwbm9uY2UnKSxcblx0XHRcdFx0XHRcdGFjdGlvbjogJ2FjdGl2YXRlJyxcblx0XHRcdFx0XHRcdHBsdWdpbjogbmV3IFVSTCh1cmwpLnNlYXJjaFBhcmFtcy5nZXQoJ3BsdWdpbicpLFxuXHRcdFx0XHRcdH0pLnRvU3RyaW5nKCksXG5cdFx0XHRcdH0pO1xuXG5cdFx0XHRcdGlmICghcmVzcG9uc2Uub2spIHtcblx0XHRcdFx0XHR0aHJvdyBuZXcgRXJyb3IoXG5cdFx0XHRcdFx0XHRgSFRUUCBlcnJvciEgc3RhdHVzOiAke3Jlc3BvbnNlLnN0YXR1c31gXG5cdFx0XHRcdFx0KTtcblx0XHRcdFx0fVxuXHRcdFx0XHRjb25zb2xlLmxvZyhgUGx1Z2luIGFjdGl2YXRlZCBzdWNjZXNzZnVsbHk6ICR7dXJsfWApO1xuXHRcdFx0fSBjYXRjaCAoZXJyb3IpIHtcblx0XHRcdFx0Y29uc29sZS5lcnJvcihgRXJyb3IgYWN0aXZhdGluZyBwbHVnaW46ICR7dXJsfWAsIGVycm9yKTtcblx0XHRcdH1cblx0XHR9XG5cblx0XHRzZXRCdXR0b25TdGF0ZSgnaW1wb3J0Jyk7XG5cdH1cblxuXHRmdW5jdGlvbiBoYW5kbGVEZXNpZ25JbXBvcnQoZGVzaWduKSB7XG5cdFx0aW5zZXJ0UGF0dGVybih7IGRlc2lnbjogZGVzaWduLCByZWxvYWQ6IHRydWUsIGNsb3NlTW9kYWw6IGNsb3NlTW9kYWwsIGNsb3NlUGF0dGVybkxpYnJhcnlNb2RhbDogY2xvc2VQYXR0ZXJuTGlicmFyeU1vZGFsIH0pO1xuXHRcdHNldEJ1dHRvblN0YXRlKCdpbXBvcnRpbmcnKTtcblx0fVxuXG5cdHJldHVybiAoXG5cdFx0PE1vZGFsXG5cdFx0XHRvblJlcXVlc3RDbG9zZT17Y2xvc2VNb2RhbH1cblx0XHRcdHRpdGxlPXt0aXRsZX1cblx0XHRcdGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeS1wbHVnaW5zXCJcblx0XHRcdHNpemU9XCJtZWRpdW1cIlxuXHRcdFx0aXNGdWxsU2NyZWVuPXtmYWxzZX1cblx0XHQ+XG5cdFx0XHQ8cCBjbGFzc05hbWU9J2N3LXBhdHRlcm4tbGlicmFyeS1wbHVnaW5zLW1lc3NhZ2UnPlxuXHRcdFx0XHR7X18oXG5cdFx0XHRcdFx0J1RoZSBmb2xsb3dpbmcgcGx1Z2lucyBuZWVkIHRvIGJlIGluc3RhbGxlZCBhbmQgYWN0aXZhdGVkIGluIG9yZGVyIHRvIHVzZSB0aGlzIHBhdHRlcm4uJyxcblx0XHRcdFx0XHQnY3ctcGF0dGVybi1pbXBvcnQnXG5cdFx0XHRcdCl9XG5cdFx0XHQ8L3A+XG5cdFx0XHQ8dWwgY2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5LXBsdWdpbnMtbGlzdFwiPntwbHVnaW5zTGlzdH08L3VsPlxuXHRcdFx0PGRpdiBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnktcGx1Z2luc19fYWN0aW9uXCI+XG5cdFx0XHRcdDxCdXR0b24gb25DbGljaz17Y2xvc2VNb2RhbH0gdmFyaWFudD1cInNlY29uZGFyeVwiPlxuXHRcdFx0XHRcdHtfXygnQ2FuY2VsJywgJ2N3LXBhdHRlcm4taW1wb3J0Jyl9XG5cdFx0XHRcdDwvQnV0dG9uPlxuXHRcdFx0XHR7YnV0dG9uU3RhdGUgPT09ICdpbnN0YWxsJyAmJiAoXG5cdFx0XHRcdFx0PEJ1dHRvblxuXHRcdFx0XHRcdFx0b25DbGljaz17KCkgPT4gaW5zdGFsbFBsdWdpbnMocGx1Z2luc1RvSW5zdGFsbCl9XG5cdFx0XHRcdFx0XHRjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnktYnRuLWlzLXByaW1hcnlcIlxuXHRcdFx0XHRcdD5cblx0XHRcdFx0XHRcdHtfXygnSW5zdGFsbCcsICdjdy1wYXR0ZXJuLWltcG9ydCcpfVxuXHRcdFx0XHRcdDwvQnV0dG9uPlxuXHRcdFx0XHQpfVxuXG5cdFx0XHRcdHtidXR0b25TdGF0ZSA9PT0gJ2luc3RhbGxpbmcnICYmIChcblx0XHRcdFx0XHQ8QnV0dG9uXG5cdFx0XHRcdFx0XHRpc0J1c3k9e3RydWV9XG5cdFx0XHRcdFx0XHRkaXNhYmxlZD17dHJ1ZX1cblx0XHRcdFx0XHRcdGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeS1idG4taXMtcHJpbWFyeVwiXG5cdFx0XHRcdFx0PlxuXHRcdFx0XHRcdFx0e19fKCdJbnN0YWxsaW5nLi4uJywgJ2N3LXBhdHRlcm4taW1wb3J0Jyl9XG5cdFx0XHRcdFx0PC9CdXR0b24+XG5cdFx0XHRcdCl9XG5cblx0XHRcdFx0e2J1dHRvblN0YXRlID09PSAnYWN0aXZhdGUnICYmIChcblx0XHRcdFx0XHQ8QnV0dG9uXG5cdFx0XHRcdFx0XHRvbkNsaWNrPXthY3RpdmF0ZVBsdWdpbnN9XG5cdFx0XHRcdFx0XHRjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnktYnRuLWlzLXByaW1hcnlcIlxuXHRcdFx0XHRcdD5cblx0XHRcdFx0XHRcdHtfXygnQWN0aXZhdGUnLCAnY3ctcGF0dGVybi1pbXBvcnQnKX1cblx0XHRcdFx0XHQ8L0J1dHRvbj5cblx0XHRcdFx0KX1cblxuXHRcdFx0XHR7YnV0dG9uU3RhdGUgPT09ICdhY3RpdmF0aW5nJyAmJiAoXG5cdFx0XHRcdFx0PEJ1dHRvblxuXHRcdFx0XHRcdFx0aXNCdXN5PXt0cnVlfVxuXHRcdFx0XHRcdFx0ZGlzYWJsZWQ9e3RydWV9XG5cdFx0XHRcdFx0XHRjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnktYnRuLWlzLXByaW1hcnlcIlxuXHRcdFx0XHRcdD5cblx0XHRcdFx0XHRcdHtfXygnQWN0aXZhdGluZy4uLicsICdjdy1wYXR0ZXJuLWltcG9ydCcpfVxuXHRcdFx0XHRcdDwvQnV0dG9uPlxuXHRcdFx0XHQpfVxuXG5cdFx0XHRcdHtidXR0b25TdGF0ZSA9PT0gJ2ltcG9ydCcgJiYgKFxuXHRcdFx0XHRcdDxCdXR0b25cblx0XHRcdFx0XHRcdG9uQ2xpY2s9eygpID0+IGhhbmRsZURlc2lnbkltcG9ydChkZXNpZ24pfVxuXHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5LWJ0bi1pcy1wcmltYXJ5XCJcblx0XHRcdFx0XHQ+XG5cdFx0XHRcdFx0XHR7X18oJ0ltcG9ydCcsICdjdy1wYXR0ZXJuLWltcG9ydCcpfVxuXHRcdFx0XHRcdDwvQnV0dG9uPlxuXHRcdFx0XHQpfVxuXG5cdFx0XHRcdHtidXR0b25TdGF0ZSA9PT0gJ2ltcG9ydGluZycgJiYgKFxuXHRcdFx0XHRcdDxCdXR0b25cblx0XHRcdFx0XHRcdGlzQnVzeT17dHJ1ZX1cblx0XHRcdFx0XHRcdGRpc2FibGVkPXt0cnVlfVxuXHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5LWJ0bi1pcy1wcmltYXJ5XCJcblx0XHRcdFx0XHQ+XG5cdFx0XHRcdFx0XHR7X18oJ0ltcG9ydGluZy4uLicsICdjdy1wYXR0ZXJuLWltcG9ydCcpfVxuXHRcdFx0XHRcdDwvQnV0dG9uPlxuXHRcdFx0XHQpfVxuXHRcdFx0PC9kaXY+XG5cdFx0PC9Nb2RhbD5cblx0KTtcbn1cblxuZXhwb3J0IGRlZmF1bHQgUmVxdWlyZWRQbHVnaW5zTW9kYWw7XG4iLCJpbXBvcnQgeyB1c2VSZWYsIHVzZVN0YXRlLCB1c2VFZmZlY3QgfSBmcm9tICdAd29yZHByZXNzL2VsZW1lbnQnO1xuaW1wb3J0IHsgSWNvbiB9IGZyb20gJ2NvbXBvbmVudHMnO1xuaW1wb3J0IGNsYXNzTmFtZXMgZnJvbSAnY2xhc3NuYW1lcyc7XG5cbmZ1bmN0aW9uIFNlZ21lbnRlZENvbnRyb2woIHtcblx0bmFtZSxcblx0c2VnbWVudHMsXG5cdGNhbGxiYWNrLFxuXHRkZWZhdWx0SW5kZXggPSAwLFxuXHRjb250cm9sUmVmLFxufSApIHtcblx0Y29uc3QgWyBhY3RpdmVJbmRleCwgc2V0QWN0aXZlSW5kZXggXSA9IHVzZVN0YXRlKCBkZWZhdWx0SW5kZXggKTtcblx0Y29uc3QgY29tcG9uZW50UmVhZHkgPSB1c2VSZWYoKTtcblxuXHR1c2VFZmZlY3QoICgpID0+IHtcblx0XHRjb21wb25lbnRSZWFkeS5jdXJyZW50ID0gdHJ1ZTtcblx0fSApO1xuXG5cdHVzZUVmZmVjdCggKCkgPT4ge1xuXHRcdGNvbnN0IGFjdGl2ZVNlZ21lbnRSZWYgPSBzZWdtZW50c1sgYWN0aXZlSW5kZXggXS5yZWY7XG5cblx0XHRjb25zdCB7IG9mZnNldFdpZHRoLCBvZmZzZXRMZWZ0IH0gPSBhY3RpdmVTZWdtZW50UmVmLmN1cnJlbnQ7XG5cdFx0Y29uc3QgeyBzdHlsZSB9ID0gY29udHJvbFJlZi5jdXJyZW50O1xuXG5cdFx0c3R5bGUuc2V0UHJvcGVydHkoICctLWhpZ2hsaWdodC13aWR0aCcsIGAkeyBvZmZzZXRXaWR0aCB9cHhgICk7XG5cdFx0c3R5bGUuc2V0UHJvcGVydHkoICctLWhpZ2hsaWdodC14LXBvcycsIGAkeyBvZmZzZXRMZWZ0IH1weGAgKTtcblx0fSwgWyBhY3RpdmVJbmRleCwgY2FsbGJhY2ssIHNlZ21lbnRzLCBjb250cm9sUmVmIF0gKTtcblxuXHRmdW5jdGlvbiBvbklucHV0Q2hhbmdlKCB2YWx1ZSwgaW5kZXggKSB7XG5cdFx0c2V0QWN0aXZlSW5kZXgoIGluZGV4ICk7XG5cdFx0Y2FsbGJhY2soIHZhbHVlLCBpbmRleCApO1xuXHR9XG5cblx0Y29uc3QgY29udHJvbHNDbGFzcyA9IGNsYXNzTmFtZXMoICdjb250cm9scycsIHtcblx0XHRyZWFkeTogY29tcG9uZW50UmVhZHkuY3VycmVudCA9PT0gdHJ1ZSxcblx0XHRpZGxlOiBjb21wb25lbnRSZWFkeS5jdXJyZW50ID09PSBmYWxzZSxcblx0fSApO1xuXG5cdHJldHVybiAoXG5cdFx0PGRpdiBjbGFzc05hbWU9XCJjb250cm9scy1jb250YWluZXJcIiByZWY9eyBjb250cm9sUmVmIH0+XG5cdFx0XHQ8ZGl2IGNsYXNzTmFtZT17IGNvbnRyb2xzQ2xhc3MgfT5cblx0XHRcdFx0eyBzZWdtZW50cy5tYXAoICggaXRlbSwgaSApID0+IChcblx0XHRcdFx0XHQ8ZGl2XG5cdFx0XHRcdFx0XHRrZXk9eyBpdGVtLnZhbHVlIH1cblx0XHRcdFx0XHRcdGNsYXNzTmFtZT17IGNsYXNzTmFtZXMoICdzZWdtZW50Jywge1xuXHRcdFx0XHRcdFx0XHRhY3RpdmU6IGkgPT09IGFjdGl2ZUluZGV4LFxuXHRcdFx0XHRcdFx0XHRpbmFjdGl2ZTogaSAhPT0gYWN0aXZlSW5kZXgsXG5cdFx0XHRcdFx0XHR9ICkgfVxuXHRcdFx0XHRcdFx0cmVmPXsgaXRlbS5yZWYgfVxuXHRcdFx0XHRcdD5cblx0XHRcdFx0XHRcdDxpbnB1dFxuXHRcdFx0XHRcdFx0XHR0eXBlPVwicmFkaW9cIlxuXHRcdFx0XHRcdFx0XHR2YWx1ZT17IGl0ZW0udmFsdWUgfVxuXHRcdFx0XHRcdFx0XHRpZD17IGl0ZW0ubGFiZWwgfVxuXHRcdFx0XHRcdFx0XHRuYW1lPXsgbmFtZSB9XG5cdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgKCkgPT4gb25JbnB1dENoYW5nZSggaXRlbS52YWx1ZSwgaSApIH1cblx0XHRcdFx0XHRcdFx0Y2hlY2tlZD17IGkgPT09IGFjdGl2ZUluZGV4IH1cblx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHQ8bGFiZWwgaHRtbEZvcj17IGl0ZW0ubGFiZWwgfT5cblx0XHRcdFx0XHRcdFx0eyBpdGVtLmljb24gPyAoXG5cdFx0XHRcdFx0XHRcdFx0PEljb24gaWNvbj17IGl0ZW0uaWNvbiB9IC8+XG5cdFx0XHRcdFx0XHRcdCkgOiAoXG5cdFx0XHRcdFx0XHRcdFx0aXRlbS5sYWJlbFxuXHRcdFx0XHRcdFx0XHQpIH1cblx0XHRcdFx0XHRcdDwvbGFiZWw+XG5cdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdCkgKSB9XG5cdFx0XHQ8L2Rpdj5cblx0XHQ8L2Rpdj5cblx0KTtcbn1cblxuZXhwb3J0IGRlZmF1bHQgU2VnbWVudGVkQ29udHJvbDtcbiIsImltcG9ydCB7IEJ1dHRvbiB9IGZyb20gJ0B3b3JkcHJlc3MvY29tcG9uZW50cyc7XG5pbXBvcnQgY2xhc3NOYW1lcyBmcm9tICdjbGFzc25hbWVzJztcblxuZnVuY3Rpb24gQ2F0ZWdvcnkoIHtcblx0aGFuZGxlQ2F0ZWdvcnksXG5cdGNhdGVnb3JpZXMsXG5cdGRlc2lnbkNvdW50LFxuXHRzZWxlY3RlZENhdGVnb3J5LFxufSApIHtcblx0Y29uc3QgZmxhdENhdGVnb3JpZXMgPSBjYXRlZ29yaWVzLmZsYXRNYXAoIE9iamVjdC52YWx1ZXMgKTtcblxuXHRjb25zdCB1bmlxdWVDYXRlZ29yaWVzID0gQXJyYXkuZnJvbShcblx0XHRuZXcgTWFwKFxuXHRcdFx0ZmxhdENhdGVnb3JpZXMubWFwKCAoIGl0ZW0gKSA9PiBbIGl0ZW0uc2x1ZywgaXRlbSBdIClcblx0XHQpLnZhbHVlcygpXG5cdCk7XG5cblx0Y29uc3Qgb3JkZXIgPSBbJ2Jhbm5lcicsICd0cmlwcycsICdkZXN0aW5hdGlvbicsICdhY3Rpdml0aWVzJywgJ3Rlc3RpbW9uaWFscycsICdsb2dvJywgJ2N0YXMnLCAnYmxvZycsICdhYm91dCcsICd0ZWFtJywgJ3NlcnZpY2VzJywgJ21hcCddO1xuXG5cdGNvbnN0IG9yZGVyZWRVbmlxdWVDYXRlZ29yaWVzID0gdW5pcXVlQ2F0ZWdvcmllcy5zb3J0KChhLCBiKSA9PiB7XG5cdFx0Y29uc3QgaW5kZXhBID0gb3JkZXIuaW5kZXhPZihhLnNsdWcpO1xuXHRcdGNvbnN0IGluZGV4QiA9IG9yZGVyLmluZGV4T2YoYi5zbHVnKTtcblxuXHRcdGlmIChpbmRleEEgPT09IC0xICYmIGluZGV4QiA9PT0gLTEpIHtcblx0XHRcdHJldHVybiBhLnNsdWcubG9jYWxlQ29tcGFyZShiLnNsdWcpO1xuXHRcdH1cblx0XHRpZiAoaW5kZXhBID09PSAtMSkge1xuXHRcdFx0cmV0dXJuIDE7XG5cdFx0fVxuXHRcdGlmIChpbmRleEIgPT09IC0xKSB7XG5cdFx0XHRyZXR1cm4gLTE7XG5cdFx0fVxuXHRcdHJldHVybiBpbmRleEEgLSBpbmRleEI7XG5cdH0pO1xuXG5cdGNvbnN0IGFsbENhdEJ0biA9IGNsYXNzTmFtZXMoXG5cdFx0J2N3LXBhdHRlcm4tbGlicmFyeV9fY2F0ZWdvcnktYnRuIHRyYW5zZm9ybS1zY2FsZScsXG5cdFx0e1xuXHRcdFx0J2lzLWFjdGl2ZSc6IHNlbGVjdGVkQ2F0ZWdvcnkgPT09ICcnLFxuXHRcdH1cblx0KTtcblxuXHRjb25zdCBjYXRlZ29yeUJ1dHRvbnMgPSB1bmlxdWVDYXRlZ29yaWVzLm1hcCggKCB7IG5hbWUsIGNvdW50IH0gKSA9PiB7XG5cdFx0Y29uc3QgY2F0ZWdvcnlCdG4gPSBjbGFzc05hbWVzKFxuXHRcdFx0J2N3LXBhdHRlcm4tbGlicmFyeV9fY2F0ZWdvcnktYnRuIHRyYW5zZm9ybS1zY2FsZScsXG5cdFx0XHR7XG5cdFx0XHRcdCdpcy1hY3RpdmUnOiBzZWxlY3RlZENhdGVnb3J5ID09PSBuYW1lLFxuXHRcdFx0fVxuXHRcdCk7XG5cdFx0cmV0dXJuIChcblx0XHRcdDxsaSBrZXk9eyBuYW1lIH0+XG5cdFx0XHRcdDxCdXR0b25cblx0XHRcdFx0XHRjbGFzc05hbWU9eyBjYXRlZ29yeUJ0biB9XG5cdFx0XHRcdFx0c3R5bGU9eyB7ICctLXNjYWxlLXgnOiAnMC45OScsICctLXNjYWxlLXknOiAnMC45OScgfSB9XG5cdFx0XHRcdFx0b25DbGljaz17ICgpID0+IGhhbmRsZUNhdGVnb3J5KCBuYW1lICkgfVxuXHRcdFx0XHQ+XG5cdFx0XHRcdFx0eyBuYW1lIH1cblx0XHRcdFx0PC9CdXR0b24+XG5cdFx0XHQ8L2xpPlxuXHRcdCk7XG5cdH0gKTtcblxuXHRyZXR1cm4gKFxuXHRcdDxkaXYgY2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X19jYXRlZ29yeVwiPlxuXHRcdFx0PHVsIGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9fY2F0ZWdvcnktbGlzdFwiPlxuXHRcdFx0XHQ8bGk+XG5cdFx0XHRcdFx0PEJ1dHRvblxuXHRcdFx0XHRcdFx0Y2xhc3NOYW1lPXsgYWxsQ2F0QnRuIH1cblx0XHRcdFx0XHRcdHN0eWxlPXsgeyAnLS1zY2FsZS14JzogJzAuOTknLCAnLS1zY2FsZS15JzogJzAuOTknIH0gfVxuXHRcdFx0XHRcdFx0b25DbGljaz17ICgpID0+IGhhbmRsZUNhdGVnb3J5KCAnJyApIH1cblx0XHRcdFx0XHQ+XG5cdFx0XHRcdFx0XHRBbGxcblx0XHRcdFx0XHRcdDxzcGFuPnsgZGVzaWduQ291bnQgfTwvc3Bhbj5cblx0XHRcdFx0XHQ8L0J1dHRvbj5cblx0XHRcdFx0PC9saT5cblx0XHRcdFx0eyBjYXRlZ29yeUJ1dHRvbnMgfVxuXHRcdFx0PC91bD5cblx0XHQ8L2Rpdj5cblx0KTtcbn1cblxuZXhwb3J0IGRlZmF1bHQgQ2F0ZWdvcnk7XG4iLCJpbXBvcnQgeyBTZWFyY2hDb250cm9sIH0gZnJvbSAnQHdvcmRwcmVzcy9jb21wb25lbnRzJztcbmltcG9ydCB7IF9fIH0gZnJvbSAnQHdvcmRwcmVzcy9pMThuJztcblxuZnVuY3Rpb24gU2VhcmNoKCB7IGhhbmRsZVNlYXJjaCwgc2VhcmNoUXVlcnkgfSApIHtcblx0cmV0dXJuIChcblx0XHQ8U2VhcmNoQ29udHJvbFxuXHRcdFx0dmFsdWU9eyBzZWFyY2hRdWVyeSB9XG5cdFx0XHRvbkNoYW5nZT17IGhhbmRsZVNlYXJjaCB9XG5cdFx0XHRwbGFjZWhvbGRlcj17IF9fKCAnU2VhcmNoIGZvciDigKYnLCAnY3ctcGF0dGVybi1pbXBvcnQnICkgfVxuXHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X19zZWFyY2hcIlxuXHRcdC8+XG5cdCk7XG59XG5cbmV4cG9ydCBkZWZhdWx0IFNlYXJjaDtcbiIsImltcG9ydCB7IFNlbGVjdENvbnRyb2wsIEJ1dHRvbiB9IGZyb20gJ0B3b3JkcHJlc3MvY29tcG9uZW50cyc7XG5pbXBvcnQgeyBfXyB9IGZyb20gJ0B3b3JkcHJlc3MvaTE4bic7XG5pbXBvcnQgeyBJY29uIH0gZnJvbSAnY29tcG9uZW50cyc7XG5pbXBvcnQgY2xhc3NOYW1lcyBmcm9tICdjbGFzc25hbWVzJztcbmltcG9ydCB7IHVzZU1vZGFsQ29udGV4dCB9IGZyb20gJ3V0aWxzL2hvb2tzJztcblxuZnVuY3Rpb24gVG9wQmFyKCB7XG5cdGhhbmRsZUxheW91dENoYW5nZSxcblx0cGxhbk9wdGlvbnMsXG5cdHBsYW4sXG5cdGhhbmRsZVBsYW5DaGFuZ2UsXG5cdHNlbGVjdGVkTGF5b3V0LFxufSApIHtcblx0Y29uc3QgeyBzeW5jLCBoYW5kbGVTeW5jIH0gPSB1c2VNb2RhbENvbnRleHQoKTtcblxuXHRjb25zdCBjb2wyQ2xhc3MgPSBjbGFzc05hbWVzKCAnY3ctbGF5b3V0LWJ0biB0cmFuc2Zvcm0tc2NhbGUnLCB7XG5cdFx0J2lzLWFjdGl2ZSc6IHNlbGVjdGVkTGF5b3V0ID09PSAyLFxuXHR9ICk7XG5cdGNvbnN0IGNvbDNDbGFzcyA9IGNsYXNzTmFtZXMoICdjdy1sYXlvdXQtYnRuIHRyYW5zZm9ybS1zY2FsZScsIHtcblx0XHQnaXMtYWN0aXZlJzogc2VsZWN0ZWRMYXlvdXQgPT09IDMsXG5cdH0gKTtcblxuXHRyZXR1cm4gKFxuXHRcdDw+XG5cdFx0XHR7XG5cdFx0XHRcdGN3QWRtaW4uZnJlZVByb0ZpbHRlciAmJlxuXHRcdFx0XHQ8U2VsZWN0Q29udHJvbFxuXHRcdFx0XHRcdHZhbHVlPXsgcGxhbiB9XG5cdFx0XHRcdFx0b3B0aW9ucz17IHBsYW5PcHRpb25zIH1cblx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVQbGFuQ2hhbmdlKCB2YWx1ZSApIH1cblx0XHRcdFx0XHRjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX3NlbGVjdFwiXG5cdFx0XHRcdFx0X19uZXh0SGFzTm9NYXJnaW5Cb3R0b21cblx0XHRcdFx0Lz5cblx0XHRcdH1cblx0XHRcdDxkaXYgY2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X19idG4tZ3JvdXBcIj5cblx0XHRcdFx0PEJ1dHRvblxuXHRcdFx0XHRcdG9uQ2xpY2s9eyAoKSA9PiBoYW5kbGVTeW5jKCB0cnVlICkgfVxuXHRcdFx0XHRcdGNsYXNzTmFtZT1cImN3LXN5bmMtYnRuIHRyYW5zZm9ybS1zY2FsZVwiXG5cdFx0XHRcdFx0bGFiZWw9eyBfXyggJ1N5bmMgTGlicmFyeScsICdjdy1wYXR0ZXJuLWltcG9ydCcgKSB9XG5cdFx0XHRcdD5cblx0XHRcdFx0XHQ8SWNvbiBpY29uPVwic3luY1wiIC8+XG5cdFx0XHRcdDwvQnV0dG9uPlxuXHRcdFx0XHQ8c3BhbiBjbGFzc05hbWU9XCJjdy1kaXZpZGVyXCI+PC9zcGFuPlxuXHRcdFx0XHQ8QnV0dG9uXG5cdFx0XHRcdFx0b25DbGljaz17ICgpID0+IGhhbmRsZUxheW91dENoYW5nZSggMyApIH1cblx0XHRcdFx0XHRjbGFzc05hbWU9eyBjb2wzQ2xhc3MgfVxuXHRcdFx0XHRcdGxhYmVsPXsgX18oICczIENvbHVtbiBQcmV2aWV3JywgJ2N3LXBhdHRlcm4taW1wb3J0JyApIH1cblx0XHRcdFx0PlxuXHRcdFx0XHRcdDxJY29uIGljb249XCJjb2wzXCIgLz5cblx0XHRcdFx0PC9CdXR0b24+XG5cdFx0XHRcdDxCdXR0b25cblx0XHRcdFx0XHRvbkNsaWNrPXsgKCkgPT4gaGFuZGxlTGF5b3V0Q2hhbmdlKCAyICkgfVxuXHRcdFx0XHRcdGNsYXNzTmFtZT17IGNvbDJDbGFzcyB9XG5cdFx0XHRcdFx0bGFiZWw9eyBfXyggJzIgQ29sdW1uIFByZXZpZXcnLCAnY3ctcGF0dGVybi1pbXBvcnQnICkgfVxuXHRcdFx0XHQ+XG5cdFx0XHRcdFx0PEljb24gaWNvbj1cImNvbDJcIiAvPlxuXHRcdFx0XHQ8L0J1dHRvbj5cblx0XHRcdDwvZGl2PlxuXHRcdDwvPlxuXHQpO1xufVxuXG5leHBvcnQgZGVmYXVsdCBUb3BCYXI7XG4iLCJpbXBvcnQgY2xhc3NOYW1lcyBmcm9tICdjbGFzc25hbWVzJztcblxuY29uc3QgaWNvbnMgPSB7XG5cdGxvZ286IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjIzNlwiXG5cdFx0XHRoZWlnaHQ9XCIzMlwiXG5cdFx0XHR2aWV3Qm94PVwiMCAwIDIzNiAzMlwiXG5cdFx0XHRmaWxsPVwibm9uZVwiXG5cdFx0XHR4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCJcblx0XHQ+XG5cdFx0XHQ8cGF0aFxuXHRcdFx0XHRkPVwiTTYzLjAyIDIyLjhMNTguOTUgNy4zOTk5OUg2MS45NjRMNjQuNzM2IDE5LjY1NEw2Ny45OTIgNy4zOTk5OUg3MS4wOTRMNzQuMjYyIDE5LjY1NEw3Ny4wMzQgNy4zOTk5OUg4MC4wN0w3NS44OSAyMi44SDcyLjU0Nkw2OS40ODggMTEuMzgyTDY2LjM0MiAyMi44SDYzLjAyWk04MS42NzA4IDIyLjhWNy4zOTk5OUw4Ny4zNjg4IDcuMzk5OTlDODguNjAwOCA3LjM5OTk5IDg5LjYyMDIgNy42MDUzMiA5MC40MjY4IDguMDE1OTlDOTEuMjMzNSA4LjQyNjY1IDkxLjgzNDggOC45OTEzMiA5Mi4yMzA4IDkuNzA5OTlDOTIuNjI2OCAxMC40Mjg3IDkyLjgyNDggMTEuMjM1MyA5Mi44MjQ4IDEyLjEzQzkyLjgyNDggMTIuOTgwNyA5Mi42MzQyIDEzLjc2NTMgOTIuMjUyOCAxNC40ODRDOTEuODcxNSAxNS4xODggOTEuMjc3NSAxNS43NiA5MC40NzA4IDE2LjJDODkuNjY0MiAxNi42MjUzIDg4LjYzMDIgMTYuODM4IDg3LjM2ODggMTYuODM4SDg0LjQ4NjhWMjIuOEg4MS42NzA4Wk04NC40ODY4IDE0LjU1SDg3LjE5MjhDODguMTc1NSAxNC41NSA4OC44Nzk1IDE0LjMzNzMgODkuMzA0OCAxMy45MTJDODkuNzQ0OCAxMy40NzIgODkuOTY0OCAxMi44NzggODkuOTY0OCAxMi4xM0M4OS45NjQ4IDExLjM2NzMgODkuNzQ0OCAxMC43NzMzIDg5LjMwNDggMTAuMzQ4Qzg4Ljg3OTUgOS45MDc5OSA4OC4xNzU1IDkuNjg3OTkgODcuMTkyOCA5LjY4Nzk5SDg0LjQ4NjhWMTQuNTVaXCJcblx0XHRcdFx0ZmlsbD1cIiMzRjQ5NEJcIlxuXHRcdFx0Lz5cblx0XHRcdDxwYXRoXG5cdFx0XHRcdGQ9XCJNMTAyLjk4MiAyMi44VjkuNjY1OTlIOTguNDk0M1Y3LjM5OTk5SDExMC4zMDhWOS42NjU5OUwxMDUuNzk4IDkuNjY1OTlWMjIuOEgxMDIuOTgyWk0xMTAuODE0IDIyLjhWMTEuODg4SDExMy4zMjJMMTEzLjU4NiAxMy45MzRDMTEzLjk4MiAxMy4yMyAxMTQuNTE3IDEyLjY3MjcgMTE1LjE5MiAxMi4yNjJDMTE1Ljg4MSAxMS44MzY3IDExNi42ODggMTEuNjI0IDExNy42MTIgMTEuNjI0VjE0LjU5NEgxMTYuODJDMTE2LjIwNCAxNC41OTQgMTE1LjY1NCAxNC42ODkzIDExNS4xNyAxNC44OEMxMTQuNjg2IDE1LjA3MDcgMTE0LjMwNSAxNS40MDA3IDExNC4wMjYgMTUuODdDMTEzLjc2MiAxNi4zMzkzIDExMy42MyAxNi45OTIgMTEzLjYzIDE3LjgyOFYyMi44SDExMC44MTRaTTEyMi44NTkgMjMuMDY0QzEyMS45MiAyMy4wNjQgMTIxLjE1IDIyLjkxNzMgMTIwLjU0OSAyMi42MjRDMTE5Ljk0NyAyMi4zMTYgMTE5LjUgMjEuOTEyNyAxMTkuMjA3IDIxLjQxNEMxMTguOTEzIDIwLjkxNTMgMTE4Ljc2NyAyMC4zNjUzIDExOC43NjcgMTkuNzY0QzExOC43NjcgMTguNzUyIDExOS4xNjMgMTcuOTMwNyAxMTkuOTU1IDE3LjNDMTIwLjc0NyAxNi42NjkzIDEyMS45MzUgMTYuMzU0IDEyMy41MTkgMTYuMzU0SDEyNi4yOTFWMTYuMDlDMTI2LjI5MSAxNS4zNDIgMTI2LjA3OCAxNC43OTIgMTI1LjY1MyAxNC40NEMxMjUuMjI3IDE0LjA4OCAxMjQuNjk5IDEzLjkxMiAxMjQuMDY5IDEzLjkxMkMxMjMuNDk3IDEzLjkxMiAxMjIuOTk4IDE0LjA1MTMgMTIyLjU3MyAxNC4zM0MxMjIuMTQ3IDE0LjU5NCAxMjEuODgzIDE0Ljk5IDEyMS43ODEgMTUuNTE4SDExOS4wMzFDMTE5LjEwNCAxNC43MjYgMTE5LjM2OCAxNC4wMzY3IDExOS44MjMgMTMuNDVDMTIwLjI5MiAxMi44NjMzIDEyMC44OTMgMTIuNDE2IDEyMS42MjcgMTIuMTA4QzEyMi4zNiAxMS43ODUzIDEyMy4xODEgMTEuNjI0IDEyNC4wOTEgMTEuNjI0QzEyNS42NDUgMTEuNjI0IDEyNi44NyAxMi4wMTI3IDEyNy43NjUgMTIuNzlDMTI4LjY1OSAxMy41NjczIDEyOS4xMDcgMTQuNjY3MyAxMjkuMTA3IDE2LjA5VjIyLjhIMTI2LjcwOUwxMjYuNDQ1IDIxLjA0QzEyNi4xMjIgMjEuNjI2NyAxMjUuNjY3IDIyLjExMDcgMTI1LjA4MSAyMi40OTJDMTI0LjUwOSAyMi44NzMzIDEyMy43NjggMjMuMDY0IDEyMi44NTkgMjMuMDY0Wk0xMjMuNDk3IDIwLjg2NEMxMjQuMzAzIDIwLjg2NCAxMjQuOTI3IDIwLjYgMTI1LjM2NyAyMC4wNzJDMTI1LjgyMSAxOS41NDQgMTI2LjEwNyAxOC44OTEzIDEyNi4yMjUgMTguMTE0SDEyMy44MjdDMTIzLjA3OSAxOC4xMTQgMTIyLjU0MyAxOC4yNTMzIDEyMi4yMjEgMTguNTMyQzEyMS44OTggMTguNzk2IDEyMS43MzcgMTkuMTI2IDEyMS43MzcgMTkuNTIyQzEyMS43MzcgMTkuOTQ3MyAxMjEuODk4IDIwLjI3NzMgMTIyLjIyMSAyMC41MTJDMTIyLjU0MyAyMC43NDY3IDEyMi45NjkgMjAuODY0IDEyMy40OTcgMjAuODY0Wk0xMzMuOTggMjIuOEwxMjkuOTc2IDExLjg4OEgxMzIuOTI0TDEzNS42OTYgMjAuMTM4TDEzOC40NjggMTEuODg4SDE0MS40MTZMMTM3LjM5IDIyLjhIMTMzLjk4Wk0xNDcuNTE3IDIzLjA2NEMxNDYuNDE3IDIzLjA2NCAxNDUuNDQyIDIyLjgyOTMgMTQ0LjU5MSAyMi4zNkMxNDMuNzQgMjEuODkwNyAxNDMuMDczIDIxLjIzMDcgMTQyLjU4OSAyMC4zOEMxNDIuMTA1IDE5LjUyOTMgMTQxLjg2MyAxOC41NDY3IDE0MS44NjMgMTcuNDMyQzE0MS44NjMgMTYuMzAyNyAxNDIuMDk4IDE1LjI5OCAxNDIuNTY3IDE0LjQxOEMxNDMuMDUxIDEzLjUzOCAxNDMuNzExIDEyLjg1NiAxNDQuNTQ3IDEyLjM3MkMxNDUuMzk4IDExLjg3MzMgMTQ2LjM5NSAxMS42MjQgMTQ3LjUzOSAxMS42MjRDMTQ4LjYxIDExLjYyNCAxNDkuNTU2IDExLjg1ODcgMTUwLjM3NyAxMi4zMjhDMTUxLjE5OCAxMi43OTczIDE1MS44MzYgMTMuNDQyNyAxNTIuMjkxIDE0LjI2NEMxNTIuNzYgMTUuMDcwNyAxNTIuOTk1IDE1Ljk3MjcgMTUyLjk5NSAxNi45N0MxNTIuOTk1IDE3LjEzMTMgMTUyLjk4OCAxNy4zIDE1Mi45NzMgMTcuNDc2QzE1Mi45NzMgMTcuNjUyIDE1Mi45NjYgMTcuODM1MyAxNTIuOTUxIDE4LjAyNkgxNDQuNjU3QzE0NC43MTYgMTguODc2NyAxNDUuMDA5IDE5LjU0NCAxNDUuNTM3IDIwLjAyOEMxNDYuMDggMjAuNTEyIDE0Ni43MzIgMjAuNzU0IDE0Ny40OTUgMjAuNzU0QzE0OC4wNjcgMjAuNzU0IDE0OC41NDQgMjAuNjI5MyAxNDguOTI1IDIwLjM4QzE0OS4zMjEgMjAuMTE2IDE0OS42MTQgMTkuNzc4NyAxNDkuODA1IDE5LjM2OEgxNTIuNjY1QzE1Mi40NiAyMC4wNTczIDE1Mi4xMTUgMjAuNjg4IDE1MS42MzEgMjEuMjZDMTUxLjE2MiAyMS44MTczIDE1MC41NzUgMjIuMjU3MyAxNDkuODcxIDIyLjU4QzE0OS4xODIgMjIuOTAyNyAxNDguMzk3IDIzLjA2NCAxNDcuNTE3IDIzLjA2NFpNMTQ3LjUzOSAxMy45MTJDMTQ2Ljg1IDEzLjkxMiAxNDYuMjQxIDE0LjExIDE0NS43MTMgMTQuNTA2QzE0NS4xODUgMTQuODg3MyAxNDQuODQ4IDE1LjQ3NCAxNDQuNzAxIDE2LjI2NkgxNTAuMTM1QzE1MC4wOTEgMTUuNTQ3MyAxNDkuODI3IDE0Ljk3NTMgMTQ5LjM0MyAxNC41NUMxNDguODU5IDE0LjEyNDcgMTQ4LjI1OCAxMy45MTIgMTQ3LjUzOSAxMy45MTJaTTE1NC45MjMgMjIuOFY2Ljk1OTk5SDE1Ny43MzlWMjIuOEgxNTQuOTIzWk0xNjUuMDQ1IDIyLjhWNy4zOTk5OUgxNzUuMDk5VjkuNjY1OTlMMTY3Ljg2MSA5LjY2NTk5VjEzLjg5TDE3NC40MzkgMTMuODlWMTYuMDlIMTY3Ljg2MVYyMC41MzRIMTc1LjA5OVYyMi44SDE2NS4wNDVaTTE3Ny4yNTggMjIuOFYxMS44ODhIMTc5Ljc0NEwxNzkuOTY0IDEzLjczNkMxODAuMzAxIDEzLjA5MDcgMTgwLjc4NSAxMi41NzczIDE4MS40MTYgMTIuMTk2QzE4Mi4wNjEgMTEuODE0NyAxODIuODE2IDExLjYyNCAxODMuNjgyIDExLjYyNEMxODUuMDMxIDExLjYyNCAxODYuMDggMTIuMDQ5MyAxODYuODI4IDEyLjlDMTg3LjU3NiAxMy43NTA3IDE4Ny45NSAxNC45OTczIDE4Ny45NSAxNi42NFYyMi44SDE4NS4xMzRWMTYuOTA0QzE4NS4xMzQgMTUuOTY1MyAxODQuOTQzIDE1LjI0NjcgMTg0LjU2MiAxNC43NDhDMTg0LjE4IDE0LjI0OTMgMTgzLjU4NiAxNCAxODIuNzggMTRDMTgxLjk4OCAxNCAxODEuMzM1IDE0LjI3ODcgMTgwLjgyMiAxNC44MzZDMTgwLjMyMyAxNS4zOTMzIDE4MC4wNzQgMTYuMTcwNyAxODAuMDc0IDE3LjE2OFYyMi44SDE3Ny4yNThaTTE5NC44MjIgMTkuNTQ0QzE5NC4yOTQgMTkuNTQ0IDE5My44MDMgMTkuNDg1MyAxOTMuMzQ4IDE5LjM2OEwxOTIuNTM0IDIwLjE4MkMxOTIuNzg0IDIwLjMxNCAxOTMuMTIxIDIwLjQyNCAxOTMuNTQ2IDIwLjUxMkMxOTMuOTcyIDIwLjYgMTk0LjY2MSAyMC42ODggMTk1LjYxNCAyMC43NzZDMTk3LjA2NiAyMC45MDggMTk4LjEyMiAyMS4yNTI3IDE5OC43ODIgMjEuODFDMTk5LjQ0MiAyMi4zNjczIDE5OS43NzIgMjMuMTM3MyAxOTkuNzcyIDI0LjEyQzE5OS43NzIgMjQuNzY1MyAxOTkuNTk2IDI1LjM3NCAxOTkuMjQ0IDI1Ljk0NkMxOTguODkyIDI2LjUzMjcgMTk4LjM1IDI3LjAwMiAxOTcuNjE2IDI3LjM1NEMxOTYuODgzIDI3LjcyMDcgMTk1Ljk0NCAyNy45MDQgMTk0LjggMjcuOTA0QzE5My4yNDYgMjcuOTA0IDE5MS45OTIgMjcuNjEwNyAxOTEuMDM4IDI3LjAyNEMxOTAuMDg1IDI2LjQ1MiAxODkuNjA4IDI1LjU4NjcgMTg5LjYwOCAyNC40MjhDMTg5LjYwOCAyMy40NDUzIDE5MC4wODUgMjIuNTk0NyAxOTEuMDM4IDIxLjg3NkMxOTAuNzQ1IDIxLjc0NCAxOTAuNDg4IDIxLjYwNDcgMTkwLjI2OCAyMS40NThDMTkwLjA2MyAyMS4zMTEzIDE4OS44OCAyMS4xNTczIDE4OS43MTggMjAuOTk2VjIwLjQ5TDE5MS42MzIgMTguNDY2QzE5MC43ODIgMTcuNzE4IDE5MC4zNTYgMTYuNzU3MyAxOTAuMzU2IDE1LjU4NEMxOTAuMzU2IDE0Ljg1MDcgMTkwLjUzMiAxNC4xODMzIDE5MC44ODQgMTMuNTgyQzE5MS4yNTEgMTIuOTgwNyAxOTEuNzY0IDEyLjUwNCAxOTIuNDI0IDEyLjE1MkMxOTMuMDg0IDExLjggMTkzLjg4NCAxMS42MjQgMTk0LjgyMiAxMS42MjRDMTk1LjQzOCAxMS42MjQgMTk2LjAxIDExLjcxMiAxOTYuNTM4IDExLjg4OEgyMDAuNjc0VjEzLjYwNEwxOTguODA0IDEzLjczNkMxOTkuMDk4IDE0LjI5MzMgMTk5LjI0NCAxNC45MDkzIDE5OS4yNDQgMTUuNTg0QzE5OS4yNDQgMTYuMzE3MyAxOTkuMDY4IDE2Ljk4NDcgMTk4LjcxNiAxNy41ODZDMTk4LjM2NCAxOC4xODczIDE5Ny44NTEgMTguNjY0IDE5Ny4xNzYgMTkuMDE2QzE5Ni41MTYgMTkuMzY4IDE5NS43MzIgMTkuNTQ0IDE5NC44MjIgMTkuNTQ0Wk0xOTQuODIyIDE3LjM4OEMxOTUuMzk0IDE3LjM4OCAxOTUuODY0IDE3LjIzNCAxOTYuMjMgMTYuOTI2QzE5Ni42MTIgMTYuNjE4IDE5Ni44MDIgMTYuMTc4IDE5Ni44MDIgMTUuNjA2QzE5Ni44MDIgMTUuMDM0IDE5Ni42MTIgMTQuNTk0IDE5Ni4yMyAxNC4yODZDMTk1Ljg2NCAxMy45NzggMTk1LjM5NCAxMy44MjQgMTk0LjgyMiAxMy44MjRDMTk0LjIyMSAxMy44MjQgMTkzLjczNyAxMy45NzggMTkzLjM3IDE0LjI4NkMxOTMuMDA0IDE0LjU5NCAxOTIuODIgMTUuMDM0IDE5Mi44MiAxNS42MDZDMTkyLjgyIDE2LjE3OCAxOTMuMDA0IDE2LjYxOCAxOTMuMzcgMTYuOTI2QzE5My43MzcgMTcuMjM0IDE5NC4yMjEgMTcuMzg4IDE5NC44MjIgMTcuMzg4Wk0xOTIuMTgyIDI0LjE0MkMxOTIuMTgyIDI0LjY4NDcgMTkyLjQzMiAyNS4wODggMTkyLjkzIDI1LjM1MkMxOTMuNDQ0IDI1LjYzMDcgMTk0LjA2NyAyNS43NyAxOTQuOCAyNS43N0MxOTUuNTA0IDI1Ljc3IDE5Ni4wNzYgMjUuNjIzMyAxOTYuNTE2IDI1LjMzQzE5Ni45NTYgMjUuMDUxMyAxOTcuMTc2IDI0LjY3IDE5Ny4xNzYgMjQuMTg2QzE5Ny4xNzYgMjMuNzkgMTk3LjAzIDIzLjQ2IDE5Ni43MzYgMjMuMTk2QzE5Ni40NTggMjIuOTMyIDE5NS44OTMgMjIuNzcwNyAxOTUuMDQyIDIyLjcxMkMxOTQuNDQxIDIyLjY2OCAxOTMuODg0IDIyLjYwMiAxOTMuMzcgMjIuNTE0QzE5Mi45NDUgMjIuNzQ4NyAxOTIuNjM3IDIzLjAwNTMgMTkyLjQ0NiAyMy4yODRDMTkyLjI3IDIzLjU2MjcgMTkyLjE4MiAyMy44NDg3IDE5Mi4xODIgMjQuMTQyWk0yMDMuODA2IDEwLjE5NEMyMDMuMjkyIDEwLjE5NCAyMDIuODY3IDEwLjA0IDIwMi41MyA5LjczMTk5QzIwMi4yMDcgOS40MjM5OSAyMDIuMDQ2IDkuMDM1MzIgMjAyLjA0NiA4LjU2NTk5QzIwMi4wNDYgOC4wOTY2NSAyMDIuMjA3IDcuNzE1MzIgMjAyLjUzIDcuNDIxOTlDMjAyLjg2NyA3LjExMzk5IDIwMy4yOTIgNi45NTk5OSAyMDMuODA2IDYuOTU5OTlDMjA0LjMxOSA2Ljk1OTk5IDIwNC43MzcgNy4xMTM5OSAyMDUuMDYgNy40MjE5OUMyMDUuMzk3IDcuNzE1MzIgMjA1LjU2NiA4LjA5NjY1IDIwNS41NjYgOC41NjU5OUMyMDUuNTY2IDkuMDM1MzIgMjA1LjM5NyA5LjQyMzk5IDIwNS4wNiA5LjczMTk5QzIwNC43MzcgMTAuMDQgMjA0LjMxOSAxMC4xOTQgMjAzLjgwNiAxMC4xOTRaTTIwMi4zOTggMjIuOFYxMS44ODhMMjA1LjIxNCAxMS44ODhWMjIuOEgyMDIuMzk4Wk0yMDcuNzEzIDIyLjhWMTEuODg4SDIxMC4xOTlMMjEwLjQxOSAxMy43MzZDMjEwLjc1NiAxMy4wOTA3IDIxMS4yNCAxMi41NzczIDIxMS44NzEgMTIuMTk2QzIxMi41MTYgMTEuODE0NyAyMTMuMjcyIDExLjYyNCAyMTQuMTM3IDExLjYyNEMyMTUuNDg2IDExLjYyNCAyMTYuNTM1IDEyLjA0OTMgMjE3LjI4MyAxMi45QzIxOC4wMzEgMTMuNzUwNyAyMTguNDA1IDE0Ljk5NzMgMjE4LjQwNSAxNi42NFYyMi44TDIxNS41ODkgMjIuOFYxNi45MDRDMjE1LjU4OSAxNS45NjUzIDIxNS4zOTggMTUuMjQ2NyAyMTUuMDE3IDE0Ljc0OEMyMTQuNjM2IDE0LjI0OTMgMjE0LjA0MiAxNCAyMTMuMjM1IDE0QzIxMi40NDMgMTQgMjExLjc5IDE0LjI3ODcgMjExLjI3NyAxNC44MzZDMjEwLjc3OCAxNS4zOTMzIDIxMC41MjkgMTYuMTcwNyAyMTAuNTI5IDE3LjE2OFYyMi44SDIwNy43MTNaTTIyNS45NiAyMy4wNjRDMjI0Ljg2IDIzLjA2NCAyMjMuODg0IDIyLjgyOTMgMjIzLjAzNCAyMi4zNkMyMjIuMTgzIDIxLjg5MDcgMjIxLjUxNiAyMS4yMzA3IDIyMS4wMzIgMjAuMzhDMjIwLjU0OCAxOS41MjkzIDIyMC4zMDYgMTguNTQ2NyAyMjAuMzA2IDE3LjQzMkMyMjAuMzA2IDE2LjMwMjcgMjIwLjU0IDE1LjI5OCAyMjEuMDEgMTQuNDE4QzIyMS40OTQgMTMuNTM4IDIyMi4xNTQgMTIuODU2IDIyMi45OSAxMi4zNzJDMjIzLjg0IDExLjg3MzMgMjI0LjgzOCAxMS42MjQgMjI1Ljk4MiAxMS42MjRDMjI3LjA1MiAxMS42MjQgMjI3Ljk5OCAxMS44NTg3IDIyOC44MiAxMi4zMjhDMjI5LjY0MSAxMi43OTczIDIzMC4yNzkgMTMuNDQyNyAyMzAuNzM0IDE0LjI2NEMyMzEuMjAzIDE1LjA3MDcgMjMxLjQzOCAxNS45NzI3IDIzMS40MzggMTYuOTdDMjMxLjQzOCAxNy4xMzEzIDIzMS40MyAxNy4zIDIzMS40MTYgMTcuNDc2QzIzMS40MTYgMTcuNjUyIDIzMS40MDggMTcuODM1MyAyMzEuMzk0IDE4LjAyNkgyMjMuMUMyMjMuMTU4IDE4Ljg3NjcgMjIzLjQ1MiAxOS41NDQgMjIzLjk4IDIwLjAyOEMyMjQuNTIyIDIwLjUxMiAyMjUuMTc1IDIwLjc1NCAyMjUuOTM4IDIwLjc1NEMyMjYuNTEgMjAuNzU0IDIyNi45ODYgMjAuNjI5MyAyMjcuMzY4IDIwLjM4QzIyNy43NjQgMjAuMTE2IDIyOC4wNTcgMTkuNzc4NyAyMjguMjQ4IDE5LjM2OEgyMzEuMTA4QzIzMC45MDIgMjAuMDU3MyAyMzAuNTU4IDIwLjY4OCAyMzAuMDc0IDIxLjI2QzIyOS42MDQgMjEuODE3MyAyMjkuMDE4IDIyLjI1NzMgMjI4LjMxNCAyMi41OEMyMjcuNjI0IDIyLjkwMjcgMjI2Ljg0IDIzLjA2NCAyMjUuOTYgMjMuMDY0Wk0yMjUuOTgyIDEzLjkxMkMyMjUuMjkyIDEzLjkxMiAyMjQuNjg0IDE0LjExIDIyNC4xNTYgMTQuNTA2QzIyMy42MjggMTQuODg3MyAyMjMuMjkgMTUuNDc0IDIyMy4xNDQgMTYuMjY2SDIyOC41NzhDMjI4LjUzNCAxNS41NDczIDIyOC4yNyAxNC45NzUzIDIyNy43ODYgMTQuNTVDMjI3LjMwMiAxNC4xMjQ3IDIyNi43IDEzLjkxMiAyMjUuOTgyIDEzLjkxMlpcIlxuXHRcdFx0XHRmaWxsPVwidXJsKCNwYWludDBfbGluZWFyXzc5NTQ0Xzk4OSlcIlxuXHRcdFx0Lz5cblx0XHRcdDxnIGNsaXBQYXRoPVwidXJsKCNjbGlwMF83OTU0NF85ODkpXCI+XG5cdFx0XHRcdDxwYXRoXG5cdFx0XHRcdFx0ZD1cIk00OS4yNjY4IDE2LjI4MDZDNDkuOTQwMSAxNS4yNzQ1IDQ4LjkzNzcgMTQuNDI2NSA0OC45Mzc3IDE0LjQyNjVDNDguOTM3NyAxNC40MjY1IDQ3Ljc2NzcgMTMuODMyNCA0Ny4wOTc1IDE0LjgzODVDNDYuNDI0MiAxNS44NDQ3IDQ0LjcyNzYgMTguMzg4NSA0NC43Mjc2IDE4LjM4ODVMMzcuODA2NyAxOC41NDk3TDM2LjY0NTcgMjAuMjg3M0w0Mi41MjI0IDIxLjY4NzZMMzkuODg2MyAyNC44NjQzQzQwLjcwNjEgMjUuMTgzOCA0MS40MDMzIDI1Ljc3MTkgNDIuMTkzMiAyNi4xNjZMNDQuNjkxNyAyMy4xMjk2TDQ4LjI0OTUgMjguMDAyMkw0OS40MTA0IDI2LjI2NDZMNDYuODk0IDE5LjgzMDVDNDYuODk3IDE5LjgzMDUgNDguNTk2NiAxNy4yODY4IDQ5LjI2NjggMTYuMjgwNlpcIlxuXHRcdFx0XHRcdGZpbGw9XCIjM0Y0OTRCXCJcblx0XHRcdFx0Lz5cblx0XHRcdFx0PHBhdGhcblx0XHRcdFx0XHRkPVwiTTI0LjU3NDkgMTQuODUwNUMyNi42NjU0IDE0Ljg1MDUgMjguMzYwMSAxMy4xNTk2IDI4LjM2MDEgMTEuMDczN0MyOC4zNjAxIDguOTg3ODIgMjYuNjY1NCA3LjI5Njg4IDI0LjU3NDkgNy4yOTY4OEMyMi40ODQ1IDcuMjk2ODggMjAuNzg5OCA4Ljk4NzgyIDIwLjc4OTggMTEuMDczN0MyMC43ODk4IDEzLjE1OTYgMjIuNDg0NSAxNC44NTA1IDI0LjU3NDkgMTQuODUwNVpcIlxuXHRcdFx0XHRcdGZpbGw9XCJ1cmwoI3BhaW50MV9saW5lYXJfNzk1NDRfOTg5KVwiXG5cdFx0XHRcdC8+XG5cdFx0XHRcdDxwYXRoXG5cdFx0XHRcdFx0ZD1cIk00NS41MzU0IDE5LjI0ODRDNDQuOTA3IDE4Ljg1NDMgNDQuMDc1MiAxOS4wNDI0IDQzLjY4MDIgMTkuNjcyM0MzOS44MzIzIDI1Ljc2OSAzNS44MTM3IDI5LjEwMSAzMi4wNTg1IDI5LjMwNEMyNy4zMDM5IDI5LjU2NjcgMjQuMTc0MSAyNC45NDc5IDIxLjQ0MjIgMjEuODYzOEMxOS45OTQgMjAuMjI3NyAxOC42Nzc0IDE4LjQ4MTEgMTcuNTI4NCAxNi42MjRDMTYuNjE4OCAxNS4xNTgxIDE1LjYxMzQgMTMuNDA1NSAxNS42MTM0IDExLjYzMkMxNS42MTA0IDYuNjk5NzYgMTkuNjMxOSAyLjY4NzA3IDI0LjU3NSAyLjY4NzA3QzI5LjUxNTIgMi42ODcwNyAzMy41MzY3IDYuNjk5NzYgMzMuNTM2NyAxMS42MzJDMzMuNTM2NyAxNC41ODQ4IDI5Ljk3NiAxOS4zMTQxIDI2LjkxNzkgMjIuNzY1NEMyNi40MzYyIDIzLjMwODggMjYuNDkgMjQuMTM1OCAyNy4wMjU3IDI0LjYyODVDMjcuMDM0NiAyNC42MzQ0IDI3LjA0MDYgMjQuNjQzNCAyNy4wNDk2IDI0LjY0OTRDMjcuNTk0MiAyNS4xNTEgMjguNDQ5OSAyNS4xMDMyIDI4Ljk0MDcgMjQuNTUwOEMzMi4xMDA0IDIxLjAwNjkgMzYuMjMyNyAxNS42MTE5IDM2LjIzMjcgMTEuNjI5QzM2LjIyOTcgNS4yMTU5IDMxLjAwMjMgMCAyNC41NzUgMEMxOC4xNDc4IDAgMTIuOTE3NCA1LjIxNTkgMTIuOTE3NCAxMS42MzJDMTIuOTE3NCAxNS4zNzkgMTYuNTc2OSAyMC4zNzY5IDE5LjY0OTkgMjMuOTE0OUwxOS42NDA5IDIzLjkwNkMyMC40NTQ4IDI0LjY3MzMgMjEuMTU0OSAyNS42MDE4IDIxLjkyMzkgMjYuNDE2OUMyMy40MTcxIDI3Ljk5MzMgMjQuOTQzMSAyOS42NzQyIDI2Ljg2MTEgMzAuNzMxMUMyNi44NjQxIDMwLjczNDEgMjYuODcwMSAzMC43MzQxIDI2Ljg4MiAzMC43NDNDMjguMTY1NyAzMS40NDE3IDI5Ljc4NDUgMzIgMzEuNjgxNSAzMkMzMS44NDAxIDMyIDMyLjAwMTcgMzEuOTk3IDMyLjE2NjMgMzEuOTg4MUMzNi45MDg5IDMxLjc1MjIgNDEuNTQ5OCAyOC4wODg4IDQ1Ljk1NzMgMjEuMTAyNEM0Ni4zNTUzIDIwLjQ3NTUgNDYuMTYzOCAxOS42NDU1IDQ1LjUzNTQgMTkuMjQ4NFpcIlxuXHRcdFx0XHRcdGZpbGw9XCIjM0Y0OTRCXCJcblx0XHRcdFx0Lz5cblx0XHRcdFx0PHBhdGhcblx0XHRcdFx0XHRkPVwiTTIxLjY3ODYgMjguOTk2N0MyMS4yODY2IDI4LjYyNjUgMjAuNzEyMSAyOC41MzM5IDIwLjIxNTQgMjguNzQyOUMxOS4zMTc4IDI5LjExOTEgMTguMjY3NSAyOS4zNjk5IDE3LjA5MTYgMjkuMzA0MkMxMy4zMzY0IDI5LjEwMTIgOS4zMTc4MyAyNS43NjkyIDUuNDg0ODIgMTkuNjkzNUwyLjQ5ODYgMTQuNzU4MkMyLjExNTYgMTQuMTIyMyAxLjI4Njc2IDEzLjkxOTMgMC42NTI0MTIgMTQuMzAxNEMwLjAxNTA3MzMgMTQuNjg2NiAtMC4xOTEzODkgMTUuNTEwNiAwLjE5NDYwNSAxNi4xNDY2TDMuMTkyNzkgMjEuMTAyN0M3LjYwMDMgMjguMDg5MSAxMi4yNDEyIDMxLjc1MjQgMTYuOTgzOCAzMS45ODgzQzE3LjE0ODQgMzEuOTk3MyAxNy4zMSAzMi4wMDAzIDE3LjQ2ODYgMzIuMDAwM0MxOC45MDE4IDMyLjAwMDMgMjAuMTc5NSAzMS42ODA4IDIxLjI3MTcgMzEuMjIxQzIyLjE3MjMgMzAuODQxOCAyMi4zOTM3IDI5LjY3MTUgMjEuNjg0NiAyOC45OTk3TDIxLjY3ODYgMjguOTk2N1pcIlxuXHRcdFx0XHRcdGZpbGw9XCJ1cmwoI3BhaW50Ml9saW5lYXJfNzk1NDRfOTg5KVwiXG5cdFx0XHRcdC8+XG5cdFx0XHQ8L2c+XG5cdFx0XHQ8ZGVmcz5cblx0XHRcdFx0PGxpbmVhckdyYWRpZW50XG5cdFx0XHRcdFx0aWQ9XCJwYWludDBfbGluZWFyXzc5NTQ0Xzk4OVwiXG5cdFx0XHRcdFx0eDE9XCIxMC45NDU0XCJcblx0XHRcdFx0XHR5MT1cIi0zLjY4MTgzXCJcblx0XHRcdFx0XHR4Mj1cIjI3LjE2NzVcIlxuXHRcdFx0XHRcdHkyPVwiODMuOTc0NFwiXG5cdFx0XHRcdFx0Z3JhZGllbnRVbml0cz1cInVzZXJTcGFjZU9uVXNlXCJcblx0XHRcdFx0PlxuXHRcdFx0XHRcdDxzdG9wIHN0b3BDb2xvcj1cIiMxRkMwQTFcIiAvPlxuXHRcdFx0XHRcdDxzdG9wIHN0b3BDb2xvcj1cIiMxRkMwQTFcIiAvPlxuXHRcdFx0XHRcdDxzdG9wIG9mZnNldD1cIjFcIiBzdG9wQ29sb3I9XCIjMDBBODlGXCIgLz5cblx0XHRcdFx0PC9saW5lYXJHcmFkaWVudD5cblx0XHRcdFx0PGxpbmVhckdyYWRpZW50XG5cdFx0XHRcdFx0aWQ9XCJwYWludDFfbGluZWFyXzc5NTQ0Xzk4OVwiXG5cdFx0XHRcdFx0eDE9XCIxOC43MjUyXCJcblx0XHRcdFx0XHR5MT1cIjYuMTI5NDlcIlxuXHRcdFx0XHRcdHgyPVwiMzAuNDcwM1wiXG5cdFx0XHRcdFx0eTI9XCIxNi43MzAzXCJcblx0XHRcdFx0XHRncmFkaWVudFVuaXRzPVwidXNlclNwYWNlT25Vc2VcIlxuXHRcdFx0XHQ+XG5cdFx0XHRcdFx0PHN0b3Agc3RvcENvbG9yPVwiIzFGQzBBMVwiIC8+XG5cdFx0XHRcdFx0PHN0b3Agc3RvcENvbG9yPVwiIzFGQzBBMVwiIC8+XG5cdFx0XHRcdFx0PHN0b3Agb2Zmc2V0PVwiMVwiIHN0b3BDb2xvcj1cIiMwMEE4OUZcIiAvPlxuXHRcdFx0XHQ8L2xpbmVhckdyYWRpZW50PlxuXHRcdFx0XHQ8bGluZWFyR3JhZGllbnRcblx0XHRcdFx0XHRpZD1cInBhaW50Ml9saW5lYXJfNzk1NDRfOTg5XCJcblx0XHRcdFx0XHR4MT1cIi02LjAyODY0XCJcblx0XHRcdFx0XHR5MT1cIjExLjM0MzVcIlxuXHRcdFx0XHRcdHgyPVwiMjEuNzc4OFwiXG5cdFx0XHRcdFx0eTI9XCI0Mi4yODM4XCJcblx0XHRcdFx0XHRncmFkaWVudFVuaXRzPVwidXNlclNwYWNlT25Vc2VcIlxuXHRcdFx0XHQ+XG5cdFx0XHRcdFx0PHN0b3Agc3RvcENvbG9yPVwiIzFGQzBBMVwiIC8+XG5cdFx0XHRcdFx0PHN0b3Agc3RvcENvbG9yPVwiIzFGQzBBMVwiIC8+XG5cdFx0XHRcdFx0PHN0b3Agb2Zmc2V0PVwiMVwiIHN0b3BDb2xvcj1cIiMwMEE4OUZcIiAvPlxuXHRcdFx0XHQ8L2xpbmVhckdyYWRpZW50PlxuXHRcdFx0XHQ8Y2xpcFBhdGggaWQ9XCJjbGlwMF83OTU0NF85ODlcIj5cblx0XHRcdFx0XHQ8cmVjdCB3aWR0aD1cIjQ5LjVcIiBoZWlnaHQ9XCIzMlwiIGZpbGw9XCJ3aGl0ZVwiIC8+XG5cdFx0XHRcdDwvY2xpcFBhdGg+XG5cdFx0XHQ8L2RlZnM+XG5cdFx0PC9zdmc+XG5cdCksXG5cdGNvbDI6IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjI0XCJcblx0XHRcdGhlaWdodD1cIjI0XCJcblx0XHRcdHZpZXdCb3g9XCIwIDAgMjQgMjRcIlxuXHRcdFx0ZmlsbD1cIm5vbmVcIlxuXHRcdFx0eG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiXG5cdFx0PlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0xMSA0LjQ0MTMzQzExIDMuNjQ1MzMgMTAuMzU0NyAzIDkuNTU4NjcgM0g0LjQ0MTMzQzMuNjQ1MzMgMyAzIDMuNjQ1MzMgMyA0LjQ0MTMzVjkuNTU4NjdDMyAxMC4zNTQ3IDMuNjQ1MzMgMTEgNC40NDEzMyAxMUg5LjU1ODY3QzEwLjM1NDcgMTEgMTEgMTAuMzU0NyAxMSA5LjU1ODY3VjQuNDQxMzNaXCJcblx0XHRcdFx0ZmlsbD1cImN1cnJlbnRDb2xvclwiXG5cdFx0XHQvPlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0yMSA0LjQ0MTMzQzIxIDMuNjQ1MzMgMjAuMzU0NyAzIDE5LjU1ODcgM0gxNC40NDEzQzEzLjY0NTMgMyAxMyAzLjY0NTMzIDEzIDQuNDQxMzNWOS41NTg2N0MxMyAxMC4zNTQ3IDEzLjY0NTMgMTEgMTQuNDQxMyAxMUgxOS41NTg3QzIwLjM1NDcgMTEgMjEgMTAuMzU0NyAyMSA5LjU1ODY3VjQuNDQxMzNaXCJcblx0XHRcdFx0ZmlsbD1cImN1cnJlbnRDb2xvclwiXG5cdFx0XHQvPlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0xMSAxNC40NDEzQzExIDEzLjY0NTMgMTAuMzU0NyAxMyA5LjU1ODY3IDEzSDQuNDQxMzNDMy42NDUzMyAxMyAzIDEzLjY0NTMgMyAxNC40NDEzVjE5LjU1ODdDMyAyMC4zNTQ3IDMuNjQ1MzMgMjEgNC40NDEzMyAyMUg5LjU1ODY3QzEwLjM1NDcgMjEgMTEgMjAuMzU0NyAxMSAxOS41NTg3VjE0LjQ0MTNaXCJcblx0XHRcdFx0ZmlsbD1cImN1cnJlbnRDb2xvclwiXG5cdFx0XHQvPlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0yMSAxNC40NDEzQzIxIDEzLjY0NTMgMjAuMzU0NyAxMyAxOS41NTg3IDEzSDE0LjQ0MTNDMTMuNjQ1MyAxMyAxMyAxMy42NDUzIDEzIDE0LjQ0MTNWMTkuNTU4N0MxMyAyMC4zNTQ3IDEzLjY0NTMgMjEgMTQuNDQxMyAyMUgxOS41NTg3QzIwLjM1NDcgMjEgMjEgMjAuMzU0NyAyMSAxOS41NTg3VjE0LjQ0MTNaXCJcblx0XHRcdFx0ZmlsbD1cImN1cnJlbnRDb2xvclwiXG5cdFx0XHQvPlxuXHRcdDwvc3ZnPlxuXHQpLFxuXHRjb2wzOiAoXG5cdFx0PHN2Z1xuXHRcdFx0d2lkdGg9XCIyNFwiXG5cdFx0XHRoZWlnaHQ9XCIyNFwiXG5cdFx0XHR2aWV3Qm94PVwiMCAwIDI0IDI0XCJcblx0XHRcdGZpbGw9XCJub25lXCJcblx0XHRcdHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIlxuXHRcdD5cblx0XHRcdDxwYXRoXG5cdFx0XHRcdGQ9XCJNMjAuMTgxOSA5LjU0NTUySDE2LjkwOTFDMTYuMDkxIDkuNTQ1NTIgMTYuMDkxIDkuNTQ1NTIgMTYuMDkxIDEwLjM2MzZWMTMuNjM2NEMxNi4wOTEgMTQuNDU0NSAxNi4wOTEgMTQuNDU0NSAxNi45MDkxIDE0LjQ1NDVIMjAuMTgxOUMyMSAxNC40NTQ1IDIxIDE0LjQ1NDUgMjEgMTMuNjM2NFYxMC4zNjM2QzIxIDkuNTQ1NTIgMjEgOS41NDU1MiAyMC4xODE5IDkuNTQ1NTJaTTEzLjYzNjQgM0gxMC4zNjM2QzkuNTQ1NTIgMyA5LjU0NTUyIDMgOS41NDU1MiAzLjgxODFWNy4wOTA4NkM5LjU0NTUyIDcuOTA4OTYgOS41NDU1MiA3LjkwODk2IDEwLjM2MzYgNy45MDg5NkgxMy42MzY0QzE0LjQ1NDUgNy45MDg5NiAxNC40NTQ1IDcuOTA4OTYgMTQuNDU0NSA3LjA5MDg2VjMuODE4MUMxNC40NTQ1IDMgMTQuNDU0NSAzIDEzLjYzNjQgM1pNMjAuMTgxOSAxNi4wOTA5SDE2LjkwOTFDMTYuMDkxIDE2LjA5MDkgMTYuMDkxIDE2LjA5MDkgMTYuMDkxIDE2LjkwOVYyMC4xODE3QzE2LjA5MSAyMC45OTk4IDE2LjA5MSAyMC45OTk4IDE2LjkwOTEgMjAuOTk5OEgyMC4xODE5QzIxIDIxIDIxIDIxIDIxIDIwLjE4MTlWMTYuOTA5MUMyMSAxNi4wOTA5IDIxIDE2LjA5MDkgMjAuMTgxOSAxNi4wOTA5Wk0xMy42MzY0IDkuNTQ1NTJIMTAuMzYzNkM5LjU0NTUyIDkuNTQ1NTIgOS41NDU1MiA5LjU0NTUyIDkuNTQ1NTIgMTAuMzYzNlYxMy42MzY0QzkuNTQ1NTIgMTQuNDU0NSA5LjU0NTUyIDE0LjQ1NDUgMTAuMzYzNiAxNC40NTQ1SDEzLjYzNjRDMTQuNDU0NSAxNC40NTQ1IDE0LjQ1NDUgMTQuNDU0NSAxNC40NTQ1IDEzLjYzNjRWMTAuMzYzNkMxNC40NTQ1IDkuNTQ1NTIgMTQuNDU0NSA5LjU0NTUyIDEzLjYzNjQgOS41NDU1MlpNNy4wOTA4NiAzSDMuODE4MUMzIDMgMyAzIDMgMy44MTgxVjcuMDkwODZDMyA3LjkwODk2IDMgNy45MDg5NiAzLjgxODEgNy45MDg5Nkg3LjA5MDg2QzcuOTA4OTYgNy45MDg5NiA3LjkwODk2IDcuOTA4OTYgNy45MDg5NiA3LjA5MDg2VjMuODE4MUM3LjkwOTE0IDMgNy45MDkxNCAzIDcuMDkwODYgM1pNMTMuNjM2NCAxNi4wOTA5SDEwLjM2MzZDOS41NDU1MiAxNi4wOTA5IDkuNTQ1NTIgMTYuMDkwOSA5LjU0NTUyIDE2LjkwOVYyMC4xODE3QzkuNTQ1NTIgMjAuOTk5OCA5LjU0NTUyIDIwLjk5OTggMTAuMzYzNiAyMC45OTk4SDEzLjYzNjRDMTQuNDU0NSAyMC45OTk4IDE0LjQ1NDUgMjAuOTk5OCAxNC40NTQ1IDIwLjE4MTdWMTYuOTA5MUMxNC40NTQ1IDE2LjA5MDkgMTQuNDU0NSAxNi4wOTA5IDEzLjYzNjQgMTYuMDkwOVpNNy4wOTA4NiA5LjU0NTUySDMuODE4MUMzIDkuNTQ1NTIgMyA5LjU0NTUyIDMgMTAuMzYzNlYxMy42MzY0QzMgMTQuNDU0NSAzIDE0LjQ1NDUgMy44MTgxIDE0LjQ1NDVINy4wOTA4NkM3LjkwODk2IDE0LjQ1NDUgNy45MDg5NiAxNC40NTQ1IDcuOTA4OTYgMTMuNjM2NFYxMC4zNjM2QzcuOTA5MTQgOS41NDU1MiA3LjkwOTE0IDkuNTQ1NTIgNy4wOTA4NiA5LjU0NTUyWk03LjA5MDg2IDE2LjA5MDlIMy44MTgxQzMgMTYuMDkwOSAzIDE2LjA5MDkgMyAxNi45MDkxVjIwLjE4MTlDMyAyMSAzIDIxIDMuODE4MSAyMUg3LjA5MDg2QzcuOTA4OTYgMjEgNy45MDg5NiAyMSA3LjkwODk2IDIwLjE4MTlWMTYuOTA5MUM3LjkwOTE0IDE2LjA5MDkgNy45MDkxNCAxNi4wOTA5IDcuMDkwODYgMTYuMDkwOVpNMjAuMTgxOSAzSDE2LjkwOTFDMTYuMDkxIDMgMTYuMDkxIDMgMTYuMDkxIDMuODE4MVY3LjA5MDg2QzE2LjA5MSA3LjkwODk2IDE2LjA5MSA3LjkwODk2IDE2LjkwOTEgNy45MDg5NkgyMC4xODE5QzIxIDcuOTA4OTYgMjEgNy45MDg5NiAyMSA3LjA5MDg2VjMuODE4MUMyMSAzIDIxIDMgMjAuMTgxOSAzWlwiXG5cdFx0XHRcdGZpbGw9XCJjdXJyZW50Q29sb3JcIlxuXHRcdFx0Lz5cblx0XHQ8L3N2Zz5cblx0KSxcblx0c3luYzogKFxuXHRcdDxzdmdcblx0XHRcdHdpZHRoPVwiMjRcIlxuXHRcdFx0aGVpZ2h0PVwiMjRcIlxuXHRcdFx0dmlld0JveD1cIjAgMCAyNCAyNFwiXG5cdFx0XHRmaWxsPVwibm9uZVwiXG5cdFx0XHR4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCJcblx0XHQ+XG5cdFx0XHQ8ZyBvcGFjaXR5PVwiMC42XCI+XG5cdFx0XHRcdDxwYXRoXG5cdFx0XHRcdFx0ZD1cIk0yIDE0QzIgMTQgMi4xMjEzMiAxNC44NDkyIDUuNjM2MDQgMTguMzY0QzkuMTUwNzYgMjEuODc4NyAxNC44NDkyIDIxLjg3ODcgMTguMzY0IDE4LjM2NEMxOS42MDkyIDE3LjExODcgMjAuNDEzMyAxNS41OTkzIDIwLjc3NjIgMTRNMiAxNFYyME0yIDE0SDhNMjIgMTBDMjIgMTAgMjEuODc4NyA5LjE1MDc2IDE4LjM2NCA1LjYzNjA0QzE0Ljg0OTIgMi4xMjEzMiA5LjE1MDc2IDIuMTIxMzIgNS42MzYwNCA1LjYzNjA0QzQuMzkwNzYgNi44ODEzMSAzLjU4NjY5IDguNDAwNzIgMy4yMjM4MyAxME0yMiAxMFY0TTIyIDEwSDE2XCJcblx0XHRcdFx0XHRzdHJva2U9XCJjdXJyZW50Q29sb3JcIlxuXHRcdFx0XHRcdHN0cm9rZVdpZHRoPVwiMlwiXG5cdFx0XHRcdFx0c3Ryb2tlTGluZWNhcD1cInJvdW5kXCJcblx0XHRcdFx0XHRzdHJva2VMaW5lam9pbj1cInJvdW5kXCJcblx0XHRcdFx0Lz5cblx0XHRcdDwvZz5cblx0XHQ8L3N2Zz5cblx0KSxcblx0Y3Jvd246IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjE2XCJcblx0XHRcdGhlaWdodD1cIjE0XCJcblx0XHRcdHZpZXdCb3g9XCIwIDAgMTYgMTRcIlxuXHRcdFx0ZmlsbD1cIm5vbmVcIlxuXHRcdFx0eG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiXG5cdFx0PlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0yLjE2NjY3IDEwLjMzMzNMMC41IDEuMTY2NjZMNS4wODMzMyA1LjMzMzMzTDggMC4zMzMzMjhMMTAuOTE2NyA1LjMzMzMzTDE1LjUgMS4xNjY2NkwxMy44MzMzIDEwLjMzMzNIMi4xNjY2N1pNMTMuODMzMyAxMi44MzMzQzEzLjgzMzMgMTMuMzMzMyAxMy41IDEzLjY2NjcgMTMgMTMuNjY2N0gzQzIuNSAxMy42NjY3IDIuMTY2NjcgMTMuMzMzMyAyLjE2NjY3IDEyLjgzMzNWMTJIMTMuODMzM1YxMi44MzMzWlwiXG5cdFx0XHRcdGZpbGw9XCJ3aGl0ZVwiXG5cdFx0XHQvPlxuXHRcdDwvc3ZnPlxuXHQpLFxuXHRpbWc6IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjI0XCJcblx0XHRcdGhlaWdodD1cIjI0XCJcblx0XHRcdHZpZXdCb3g9XCIwIDAgMjQgMjRcIlxuXHRcdFx0ZmlsbD1cIm5vbmVcIlxuXHRcdFx0eG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiXG5cdFx0PlxuXHRcdFx0PGcgY2xpcFBhdGg9XCJ1cmwoI2NsaXAwXzc5NTQ2XzExODg4KVwiPlxuXHRcdFx0XHQ8cGF0aFxuXHRcdFx0XHRcdGQ9XCJNOS4wMDI5OSA4LjI1QzkuMDAyOTkgOC44NDY3NCA4Ljc2NTk0IDkuNDE5MDMgOC4zNDM5OCA5Ljg0MDk5QzcuOTIyMDIgMTAuMjYyOSA3LjM0OTczIDEwLjUgNi43NTI5OSAxMC41QzYuMTU2MjUgMTAuNSA1LjU4Mzk2IDEwLjI2MjkgNS4xNjIgOS44NDA5OUM0Ljc0MDA0IDkuNDE5MDMgNC41MDI5OSA4Ljg0Njc0IDQuNTAyOTkgOC4yNUM0LjUwMjk5IDcuNjUzMjYgNC43NDAwNCA3LjA4MDk3IDUuMTYyIDYuNjU5MDFDNS41ODM5NiA2LjIzNzA1IDYuMTU2MjUgNiA2Ljc1Mjk5IDZDNy4zNDk3MyA2IDcuOTIyMDIgNi4yMzcwNSA4LjM0Mzk4IDYuNjU5MDFDOC43NjU5NCA3LjA4MDk3IDkuMDAyOTkgNy42NTMyNiA5LjAwMjk5IDguMjVaXCJcblx0XHRcdFx0XHRmaWxsPVwiIzBDQjE5RlwiXG5cdFx0XHRcdC8+XG5cdFx0XHRcdDxwYXRoXG5cdFx0XHRcdFx0ZD1cIk0zLjAwMjk5IDEuNUMyLjIwNzM0IDEuNSAxLjQ0NDI4IDEuODE2MDcgMC44ODE2NyAyLjM3ODY4QzAuMzE5MDYxIDIuOTQxMjkgMC4wMDI5OTA3MiAzLjcwNDM1IDAuMDAyOTkwNzIgNC41VjE5LjVDMC4wMDI5OTA3MiAyMC4yOTU2IDAuMzE5MDYxIDIxLjA1ODcgMC44ODE2NyAyMS42MjEzQzEuNDQ0MjggMjIuMTgzOSAyLjIwNzM0IDIyLjUgMy4wMDI5OSAyMi41SDIxLjAwM0MyMS43OTg2IDIyLjUgMjIuNTYxNyAyMi4xODM5IDIzLjEyNDMgMjEuNjIxM0MyMy42ODY5IDIxLjA1ODcgMjQuMDAzIDIwLjI5NTYgMjQuMDAzIDE5LjVWNC41QzI0LjAwMyAzLjcwNDM1IDIzLjY4NjkgMi45NDEyOSAyMy4xMjQzIDIuMzc4NjhDMjIuNTYxNyAxLjgxNjA3IDIxLjc5ODYgMS41IDIxLjAwMyAxLjVIMy4wMDI5OVpNMjEuMDAzIDNDMjEuNDAwOCAzIDIxLjc4MjMgMy4xNTgwNCAyMi4wNjM3IDMuNDM5MzRDMjIuMzQ1IDMuNzIwNjQgMjIuNTAzIDQuMTAyMTggMjIuNTAzIDQuNVYxNC4yNUwxNi44Mzc1IDExLjMyOTVDMTYuNjk2OCAxMS4yNTkgMTYuNTM3NiAxMS4yMzQ2IDE2LjM4MjIgMTEuMjU5NkMxNi4yMjY5IDExLjI4NDcgMTYuMDgzNCAxMS4zNTc5IDE1Ljk3MiAxMS40NjlMMTAuNDA3IDE3LjAzNEw2LjQxNjk5IDE0LjM3NkM2LjI3MjkzIDE0LjI4MDEgNi4xMDAxMyAxNC4yMzcgNS45Mjc5IDE0LjI1MzlDNS43NTU2NiAxNC4yNzA5IDUuNTk0NTkgMTQuMzQ2OCA1LjQ3MTk5IDE0LjQ2OUwxLjUwMjk5IDE4VjQuNUMxLjUwMjk5IDQuMTAyMTggMS42NjEwMyAzLjcyMDY0IDEuOTQyMzMgMy40MzkzNEMyLjIyMzY0IDMuMTU4MDQgMi42MDUxNyAzIDMuMDAyOTkgM0gyMS4wMDNaXCJcblx0XHRcdFx0XHRmaWxsPVwiIzBDQjE5RlwiXG5cdFx0XHRcdC8+XG5cdFx0XHQ8L2c+XG5cdFx0XHQ8ZGVmcz5cblx0XHRcdFx0PGNsaXBQYXRoIGlkPVwiY2xpcDBfNzk1NDZfMTE4ODhcIj5cblx0XHRcdFx0XHQ8cmVjdCB3aWR0aD1cIjI0XCIgaGVpZ2h0PVwiMjRcIiBmaWxsPVwid2hpdGVcIiAvPlxuXHRcdFx0XHQ8L2NsaXBQYXRoPlxuXHRcdFx0PC9kZWZzPlxuXHRcdDwvc3ZnPlxuXHQpLFxuXHRkb3duX2Fycm93OiAoXG5cdFx0PHN2Z1xuXHRcdFx0d2lkdGg9XCIxNlwiXG5cdFx0XHRoZWlnaHQ9XCIxNlwiXG5cdFx0XHR2aWV3Qm94PVwiMCAwIDE2IDE2XCJcblx0XHRcdGZpbGw9XCJub25lXCJcblx0XHRcdHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIlxuXHRcdD5cblx0XHRcdDxwYXRoXG5cdFx0XHRcdGQ9XCJNOCAyQzguMjY1MjIgMiA4LjUxOTU3IDIuMTA1MzYgOC43MDcxMSAyLjI5Mjg5QzguODk0NjQgMi40ODA0MyA5IDIuNzM0NzggOSAzVjEwLjkyTDExLjYyNjcgOC4yOTMzM0MxMS44MTYyIDguMTE2NjkgMTIuMDY3IDguMDIwNTMgMTIuMzI2IDguMDI1MUMxMi41ODUxIDguMDI5NjcgMTIuODMyMyA4LjEzNDYyIDEzLjAxNTUgOC4zMTc4NEMxMy4xOTg3IDguNTAxMDUgMTMuMzAzNyA4Ljc0ODI0IDEzLjMwODIgOS4wMDczQzEzLjMxMjggOS4yNjYzNyAxMy4yMTY2IDkuNTE3MSAxMy4wNCA5LjcwNjY3TDguNzA2NjcgMTQuMDRDOC41MTkxNyAxNC4yMjczIDguMjY1IDE0LjMzMjUgOCAxNC4zMzI1QzcuNzM1IDE0LjMzMjUgNy40ODA4MyAxNC4yMjczIDcuMjkzMzMgMTQuMDRMMi45NiA5LjcwNjY3QzIuODYxNzUgOS42MTUxMiAyLjc4Mjk1IDkuNTA0NzIgMi43MjgyOSA5LjM4MjA1QzIuNjczNjQgOS4yNTkzOSAyLjY0NDI1IDkuMTI2OTcgMi42NDE4OCA4Ljk5MjdDMi42Mzk1MSA4Ljg1ODQzIDIuNjY0MjEgOC43MjUwNSAyLjcxNDUgOC42MDA1NEMyLjc2NDggOC40NzYwMiAyLjgzOTY2IDguMzYyOTEgMi45MzQ2MiA4LjI2Nzk1QzMuMDI5NTggOC4xNzI5OSAzLjE0MjY5IDguMDk4MTMgMy4yNjcyIDguMDQ3ODRDMy4zOTE3MiA3Ljk5NzU0IDMuNTI1MDkgNy45NzI4NCAzLjY1OTM2IDcuOTc1MjFDMy43OTM2NCA3Ljk3NzU4IDMuOTI2MDUgOC4wMDY5NyA0LjA0ODcyIDguMDYxNjNDNC4xNzEzOCA4LjExNjI4IDQuMjgxNzkgOC4xOTUwOCA0LjM3MzMzIDguMjkzMzNMNyAxMC45MlYzQzcgMi43MzQ3OCA3LjEwNTM2IDIuNDgwNDMgNy4yOTI4OSAyLjI5Mjg5QzcuNDgwNDMgMi4xMDUzNiA3LjczNDc4IDIgOCAyWlwiXG5cdFx0XHRcdGZpbGw9XCJ3aGl0ZVwiXG5cdFx0XHQvPlxuXHRcdDwvc3ZnPlxuXHQpLFxuXHRoZWFydF9maWxsOiAoXG5cdFx0PHN2Z1xuXHRcdFx0d2lkdGg9XCIyMFwiXG5cdFx0XHRoZWlnaHQ9XCIyMFwiXG5cdFx0XHR2aWV3Qm94PVwiMCAwIDIwIDIwXCJcblx0XHRcdGZpbGw9XCJub25lXCJcblx0XHRcdHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIlxuXHRcdD5cblx0XHRcdDxtYXNrXG5cdFx0XHRcdGlkPVwibWFzazBfNzk1NTBfMTIxNDRcIlxuXHRcdFx0XHRzdHlsZT17eyBtYXNrVHlwZTogJ2FscGhhJyB9fVxuXHRcdFx0XHRtYXNrVW5pdHM9XCJ1c2VyU3BhY2VPblVzZVwiXG5cdFx0XHRcdHg9XCIwXCJcblx0XHRcdFx0eT1cIjBcIlxuXHRcdFx0XHR3aWR0aD1cIjIwXCJcblx0XHRcdFx0aGVpZ2h0PVwiMjBcIlxuXHRcdFx0PlxuXHRcdFx0XHQ8cmVjdCB3aWR0aD1cIjIwXCIgaGVpZ2h0PVwiMjBcIiBmaWxsPVwiI0Q5RDlEOVwiIC8+XG5cdFx0XHQ8L21hc2s+XG5cdFx0XHQ8ZyBtYXNrPVwidXJsKCNtYXNrMF83OTU1MF8xMjE0NClcIj5cblx0XHRcdFx0PHBhdGhcblx0XHRcdFx0XHRkPVwiTTEwLjAwMDMgMTguNjQ1N0w4Ljc5MTk5IDE3LjU2MjNDNy4zODkyMSAxNi4yOTg0IDYuMjI5NDkgMTUuMjA4MiA1LjMxMjgzIDE0LjI5MTVDNC4zOTYxNiAxMy4zNzQ4IDMuNjY2OTkgMTIuNTUxOSAzLjEyNTMzIDExLjgyMjhDMi41ODM2NiAxMS4wOTM2IDIuMjA1MTkgMTAuNDIzNCAxLjk4OTkxIDkuODEyMzRDMS43NzQ2MyA5LjIwMTIzIDEuNjY2OTkgOC41NzYyMyAxLjY2Njk5IDcuOTM3MzRDMS42NjY5OSA2LjYzMTc4IDIuMTA0NDkgNS41NDE1IDIuOTc5NDkgNC42NjY1QzMuODU0NDkgMy43OTE1IDQuOTQ0NzcgMy4zNTQgNi4yNTAzMyAzLjM1NEM2Ljk3MjU1IDMuMzU0IDcuNjYwMDUgMy41MDY3OCA4LjMxMjgzIDMuODEyMzRDOC45NjU2IDQuMTE3ODkgOS41MjgxIDQuNTQ4NDUgMTAuMDAwMyA1LjEwNEMxMC40NzI1IDQuNTQ4NDUgMTEuMDM1IDQuMTE3ODkgMTEuNjg3OCAzLjgxMjM0QzEyLjM0MDYgMy41MDY3OCAxMy4wMjgxIDMuMzU0IDEzLjc1MDMgMy4zNTRDMTUuMDU1OSAzLjM1NCAxNi4xNDYyIDMuNzkxNSAxNy4wMjEyIDQuNjY2NUMxNy44OTYyIDUuNTQxNSAxOC4zMzM3IDYuNjMxNzggMTguMzMzNyA3LjkzNzM0QzE4LjMzMzcgOC41NzYyMyAxOC4yMjYgOS4yMDEyMyAxOC4wMTA3IDkuODEyMzRDMTcuNzk1NSAxMC40MjM0IDE3LjQxNyAxMS4wOTM2IDE2Ljg3NTMgMTEuODIyOEMxNi4zMzM3IDEyLjU1MTkgMTUuNjA0NSAxMy4zNzQ4IDE0LjY4NzggMTQuMjkxNUMxMy43NzEyIDE1LjIwODIgMTIuNjExNCAxNi4yOTg0IDExLjIwODcgMTcuNTYyM0wxMC4wMDAzIDE4LjY0NTdaXCJcblx0XHRcdFx0XHRmaWxsPVwiY3VycmVudENvbG9yXCJcblx0XHRcdFx0Lz5cblx0XHRcdDwvZz5cblx0XHQ8L3N2Zz5cblx0KSxcblx0aGVhcnRfb3V0bGluZTogKFxuXHRcdDxzdmdcblx0XHRcdHdpZHRoPVwiMjBcIlxuXHRcdFx0aGVpZ2h0PVwiMjBcIlxuXHRcdFx0dmlld0JveD1cIjAgMCAyMCAyMFwiXG5cdFx0XHRmaWxsPVwibm9uZVwiXG5cdFx0XHR4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCJcblx0XHQ+XG5cdFx0XHQ8bWFza1xuXHRcdFx0XHRpZD1cIm1hc2swXzc5NTUwXzEyMTM5XCJcblx0XHRcdFx0c3R5bGU9e3sgbWFza1R5cGU6ICdhbHBoYScgfX1cblx0XHRcdFx0bWFza1VuaXRzPVwidXNlclNwYWNlT25Vc2VcIlxuXHRcdFx0XHR4PVwiMFwiXG5cdFx0XHRcdHk9XCIwXCJcblx0XHRcdFx0d2lkdGg9XCIyMFwiXG5cdFx0XHRcdGhlaWdodD1cIjIwXCJcblx0XHRcdD5cblx0XHRcdFx0PHJlY3Qgd2lkdGg9XCIyMFwiIGhlaWdodD1cIjIwXCIgZmlsbD1cIiNEOUQ5RDlcIiAvPlxuXHRcdFx0PC9tYXNrPlxuXHRcdFx0PGcgbWFzaz1cInVybCgjbWFzazBfNzk1NTBfMTIxMzkpXCI+XG5cdFx0XHRcdDxwYXRoXG5cdFx0XHRcdFx0ZD1cIk0xMC4wMDAzIDE4LjY0NTdMOC43OTE5OSAxNy41NjIzQzcuMzg5MjEgMTYuMjk4NCA2LjIyOTQ5IDE1LjIwODIgNS4zMTI4MyAxNC4yOTE1QzQuMzk2MTYgMTMuMzc0OCAzLjY2Njk5IDEyLjU1MTkgMy4xMjUzMyAxMS44MjI4QzIuNTgzNjYgMTEuMDkzNiAyLjIwNTE5IDEwLjQyMzQgMS45ODk5MSA5LjgxMjM0QzEuNzc0NjMgOS4yMDEyMyAxLjY2Njk5IDguNTc2MjMgMS42NjY5OSA3LjkzNzM0QzEuNjY2OTkgNi42MzE3OCAyLjEwNDQ5IDUuNTQxNSAyLjk3OTQ5IDQuNjY2NUMzLjg1NDQ5IDMuNzkxNSA0Ljk0NDc3IDMuMzU0IDYuMjUwMzMgMy4zNTRDNi45NzI1NSAzLjM1NCA3LjY2MDA1IDMuNTA2NzggOC4zMTI4MyAzLjgxMjM0QzguOTY1NiA0LjExNzg5IDkuNTI4MSA0LjU0ODQ1IDEwLjAwMDMgNS4xMDRDMTAuNDcyNSA0LjU0ODQ1IDExLjAzNSA0LjExNzg5IDExLjY4NzggMy44MTIzNEMxMi4zNDA2IDMuNTA2NzggMTMuMDI4MSAzLjM1NCAxMy43NTAzIDMuMzU0QzE1LjA1NTkgMy4zNTQgMTYuMTQ2MiAzLjc5MTUgMTcuMDIxMiA0LjY2NjVDMTcuODk2MiA1LjU0MTUgMTguMzMzNyA2LjYzMTc4IDE4LjMzMzcgNy45MzczNEMxOC4zMzM3IDguNTc2MjMgMTguMjI2IDkuMjAxMjMgMTguMDEwNyA5LjgxMjM0QzE3Ljc5NTUgMTAuNDIzNCAxNy40MTcgMTEuMDkzNiAxNi44NzUzIDExLjgyMjhDMTYuMzMzNyAxMi41NTE5IDE1LjYwNDUgMTMuMzc0OCAxNC42ODc4IDE0LjI5MTVDMTMuNzcxMiAxNS4yMDgyIDEyLjYxMTQgMTYuMjk4NCAxMS4yMDg3IDE3LjU2MjNMMTAuMDAwMyAxOC42NDU3Wk0xMC4wMDAzIDE2LjM5NTdDMTEuMzMzNyAxNS4yMDEyIDEyLjQzMDkgMTQuMTc2OSAxMy4yOTIgMTMuMzIyOEMxNC4xNTMxIDEyLjQ2ODYgMTQuODMzNyAxMS43MjU1IDE1LjMzMzcgMTEuMDkzNkMxNS44MzM3IDEwLjQ2MTYgMTYuMTgwOSA5Ljg5OTE0IDE2LjM3NTMgOS40MDYwOUMxNi41Njk4IDguOTEzMDMgMTYuNjY3IDguNDIzNDUgMTYuNjY3IDcuOTM3MzRDMTYuNjY3IDcuMTA0IDE2LjM4OTIgNi40MDk1NiAxNS44MzM3IDUuODU0QzE1LjI3ODEgNS4yOTg0NSAxNC41ODM3IDUuMDIwNjcgMTMuNzUwMyA1LjAyMDY3QzEzLjA5NzUgNS4wMjA2NyAxMi40OTM0IDUuMjA0NyAxMS45Mzc4IDUuNTcyNzVDMTEuMzgyMyA1Ljk0MDgxIDExLjAwMDMgNi40MDk1NiAxMC43OTIgNi45NzlIOS4yMDg2NkM5LjAwMDMzIDYuNDA5NTYgOC42MTgzOCA1Ljk0MDgxIDguMDYyODMgNS41NzI3NUM3LjUwNzI3IDUuMjA0NyA2LjkwMzEgNS4wMjA2NyA2LjI1MDMzIDUuMDIwNjdDNS40MTY5OSA1LjAyMDY3IDQuNzIyNTUgNS4yOTg0NSA0LjE2Njk5IDUuODU0QzMuNjExNDQgNi40MDk1NiAzLjMzMzY2IDcuMTA0IDMuMzMzNjYgNy45MzczNEMzLjMzMzY2IDguNDIzNDUgMy40MzA4OCA4LjkxMzAzIDMuNjI1MzMgOS40MDYwOUMzLjgxOTc3IDkuODk5MTQgNC4xNjY5OSAxMC40NjE2IDQuNjY2OTkgMTEuMDkzNkM1LjE2Njk5IDExLjcyNTUgNS44NDc1NSAxMi40Njg2IDYuNzA4NjYgMTMuMzIyOEM3LjU2OTc3IDE0LjE3NjkgOC42NjY5OSAxNS4yMDEyIDEwLjAwMDMgMTYuMzk1N1pcIlxuXHRcdFx0XHRcdGZpbGw9XCJjdXJyZW50Q29sb3JcIlxuXHRcdFx0XHQvPlxuXHRcdFx0PC9nPlxuXHRcdDwvc3ZnPlxuXHQpLFxuXHRlcnJvcjogKFxuXHRcdDxzdmdcblx0XHRcdHdpZHRoPVwiNjZcIlxuXHRcdFx0aGVpZ2h0PVwiNjZcIlxuXHRcdFx0dmlld0JveD1cIjAgMCA2NiA2NlwiXG5cdFx0XHRmaWxsPVwibm9uZVwiXG5cdFx0XHR4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCJcblx0XHQ+XG5cdFx0XHQ8cmVjdFxuXHRcdFx0XHR4PVwiOVwiXG5cdFx0XHRcdHk9XCI5XCJcblx0XHRcdFx0d2lkdGg9XCI0OFwiXG5cdFx0XHRcdGhlaWdodD1cIjQ4XCJcblx0XHRcdFx0cng9XCIyNFwiXG5cdFx0XHRcdGZpbGw9XCIjRDkyRDIwXCJcblx0XHRcdFx0ZmlsbE9wYWNpdHk9XCIwLjJcIlxuXHRcdFx0Lz5cblx0XHRcdDxwYXRoXG5cdFx0XHRcdGQ9XCJNMzMuNDc2MSAyOC42NzYyVjMzLjQ3NjJNMzMuNDc2MSAzOC4yNzYySDMzLjQ4ODFNNDUuNDc2MSAzMy40NzYyQzQ1LjQ3NjEgNDAuMTAzNiA0MC4xMDM1IDQ1LjQ3NjIgMzMuNDc2MSA0NS40NzYyQzI2Ljg0ODcgNDUuNDc2MiAyMS40NzYxIDQwLjEwMzYgMjEuNDc2MSAzMy40NzYyQzIxLjQ3NjEgMjYuODQ4OCAyNi44NDg3IDIxLjQ3NjIgMzMuNDc2MSAyMS40NzYyQzQwLjEwMzUgMjEuNDc2MiA0NS40NzYxIDI2Ljg0ODggNDUuNDc2MSAzMy40NzYyWlwiXG5cdFx0XHRcdHN0cm9rZT1cIiNEOTJEMjBcIlxuXHRcdFx0XHRzdHJva2VXaWR0aD1cIjJcIlxuXHRcdFx0XHRzdHJva2VMaW5lY2FwPVwicm91bmRcIlxuXHRcdFx0XHRzdHJva2VMaW5lam9pbj1cInJvdW5kXCJcblx0XHRcdC8+XG5cdFx0XHQ8cmVjdFxuXHRcdFx0XHR4PVwiNC44NTcxNFwiXG5cdFx0XHRcdHk9XCI0Ljg1NzE0XCJcblx0XHRcdFx0d2lkdGg9XCI1Ni4yODU3XCJcblx0XHRcdFx0aGVpZ2h0PVwiNTYuMjg1N1wiXG5cdFx0XHRcdHJ4PVwiMjguMTQyOVwiXG5cdFx0XHRcdHN0cm9rZT1cIiNEOTJEMjBcIlxuXHRcdFx0XHRzdHJva2VPcGFjaXR5PVwiMC4xXCJcblx0XHRcdFx0c3Ryb2tlV2lkdGg9XCI4LjI4NTcxXCJcblx0XHRcdC8+XG5cdFx0PC9zdmc+XG5cdCksXG5cdGRlc2t0b3A6IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjIxXCJcblx0XHRcdGhlaWdodD1cIjIwXCJcblx0XHRcdHZpZXdCb3g9XCIwIDAgMjEgMjBcIlxuXHRcdFx0ZmlsbD1cIm5vbmVcIlxuXHRcdFx0eG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiXG5cdFx0PlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk03LjE2NjY2IDE3LjVIMTMuODMzM00xMC41IDE0LjE2NjdWMTcuNU02LjE2NjY2IDE0LjE2NjdIMTQuODMzM0MxNi4yMzM1IDE0LjE2NjcgMTYuOTMzNSAxNC4xNjY3IDE3LjQ2ODMgMTMuODk0MkMxNy45Mzg3IDEzLjY1NDUgMTguMzIxMiAxMy4yNzIgMTguNTYwOCAxMi44MDE2QzE4LjgzMzMgMTIuMjY2OSAxOC44MzMzIDExLjU2NjggMTguODMzMyAxMC4xNjY3VjYuNUMxOC44MzMzIDUuMDk5ODcgMTguODMzMyA0LjM5OTggMTguNTYwOCAzLjg2NTAyQzE4LjMyMTIgMy4zOTQ2MiAxNy45Mzg3IDMuMDEyMTcgMTcuNDY4MyAyLjc3MjQ4QzE2LjkzMzUgMi41IDE2LjIzMzUgMi41IDE0LjgzMzMgMi41SDYuMTY2NjZDNC43NjY1MiAyLjUgNC4wNjY0NiAyLjUgMy41MzE2OCAyLjc3MjQ4QzMuMDYxMjcgMy4wMTIxNyAyLjY3ODgyIDMuMzk0NjIgMi40MzkxNCAzLjg2NTAyQzIuMTY2NjYgNC4zOTk4IDIuMTY2NjYgNS4wOTk4NyAyLjE2NjY2IDYuNVYxMC4xNjY3QzIuMTY2NjYgMTEuNTY2OCAyLjE2NjY2IDEyLjI2NjkgMi40MzkxNCAxMi44MDE2QzIuNjc4ODIgMTMuMjcyIDMuMDYxMjcgMTMuNjU0NSAzLjUzMTY4IDEzLjg5NDJDNC4wNjY0NiAxNC4xNjY3IDQuNzY2NTIgMTQuMTY2NyA2LjE2NjY2IDE0LjE2NjdaXCJcblx0XHRcdFx0c3Ryb2tlPVwiY3VycmVudENvbG9yXCJcblx0XHRcdFx0c3Ryb2tlV2lkdGg9XCIxLjY3XCJcblx0XHRcdFx0c3Ryb2tlTGluZWNhcD1cInJvdW5kXCJcblx0XHRcdFx0c3Ryb2tlTGluZWpvaW49XCJyb3VuZFwiXG5cdFx0XHQvPlxuXHRcdDwvc3ZnPlxuXHQpLFxuXHR0YWJsZXQ6IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjIxXCJcblx0XHRcdGhlaWdodD1cIjIwXCJcblx0XHRcdHZpZXdCb3g9XCIwIDAgMjEgMjBcIlxuXHRcdFx0ZmlsbD1cIm5vbmVcIlxuXHRcdFx0eG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiXG5cdFx0PlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0xMC41IDE0LjU4MzRIMTAuNTA4M002LjUwMDAxIDE4LjMzMzRIMTQuNUMxNS40MzM0IDE4LjMzMzQgMTUuOTAwMSAxOC4zMzM0IDE2LjI1NjcgMTguMTUxN0MxNi41NzAzIDE3Ljk5MTkgMTYuODI1MiAxNy43MzY5IDE2Ljk4NSAxNy40MjMzQzE3LjE2NjcgMTcuMDY2OCAxNy4xNjY3IDE2LjYwMDEgMTcuMTY2NyAxNS42NjY3VjQuMzMzMzVDMTcuMTY2NyAzLjM5OTkzIDE3LjE2NjcgMi45MzMyMiAxNi45ODUgMi41NzY3QzE2LjgyNTIgMi4yNjMxIDE2LjU3MDMgMi4wMDgxMyAxNi4yNTY3IDEuODQ4MzRDMTUuOTAwMSAxLjY2NjY5IDE1LjQzMzQgMS42NjY2OSAxNC41IDEuNjY2NjlINi41MDAwMUM1LjU2NjU5IDEuNjY2NjkgNS4wOTk4OCAxLjY2NjY5IDQuNzQzMzYgMS44NDgzNEM0LjQyOTc2IDIuMDA4MTMgNC4xNzQ3OSAyLjI2MzEgNC4wMTUgMi41NzY3QzMuODMzMzQgMi45MzMyMiAzLjgzMzM0IDMuMzk5OTMgMy44MzMzNCA0LjMzMzM1VjE1LjY2NjdDMy44MzMzNCAxNi42MDAxIDMuODMzMzQgMTcuMDY2OCA0LjAxNSAxNy40MjMzQzQuMTc0NzkgMTcuNzM2OSA0LjQyOTc2IDE3Ljk5MTkgNC43NDMzNiAxOC4xNTE3QzUuMDk5ODggMTguMzMzNCA1LjU2NjU5IDE4LjMzMzQgNi41MDAwMSAxOC4zMzM0Wk0xMC45MTY3IDE0LjU4MzRDMTAuOTE2NyAxNC44MTM1IDEwLjczMDEgMTUgMTAuNSAxNUMxMC4yNjk5IDE1IDEwLjA4MzMgMTQuODEzNSAxMC4wODMzIDE0LjU4MzRDMTAuMDgzMyAxNC4zNTMyIDEwLjI2OTkgMTQuMTY2NyAxMC41IDE0LjE2NjdDMTAuNzMwMSAxNC4xNjY3IDEwLjkxNjcgMTQuMzUzMiAxMC45MTY3IDE0LjU4MzRaXCJcblx0XHRcdFx0c3Ryb2tlPVwiY3VycmVudENvbG9yXCJcblx0XHRcdFx0c3Ryb2tlV2lkdGg9XCIxLjY3XCJcblx0XHRcdFx0c3Ryb2tlTGluZWNhcD1cInJvdW5kXCJcblx0XHRcdFx0c3Ryb2tlTGluZWpvaW49XCJyb3VuZFwiXG5cdFx0XHQvPlxuXHRcdDwvc3ZnPlxuXHQpLFxuXHRtb2JpbGU6IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjIxXCJcblx0XHRcdGhlaWdodD1cIjIwXCJcblx0XHRcdHZpZXdCb3g9XCIwIDAgMjEgMjBcIlxuXHRcdFx0ZmlsbD1cIm5vbmVcIlxuXHRcdFx0eG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiXG5cdFx0PlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0xMC41IDE0LjRIMTAuNTA3MU03Ljc4NTcxIDE4SDEzLjIxNDNDMTQuMDE0NCAxOCAxNC40MTQ0IDE4IDE0LjcyIDE3LjgyNTZDMTQuOTg4OCAxNy42NzIyIDE1LjIwNzMgMTcuNDI3NCAxNS4zNDQzIDE3LjEyNjRDMTUuNSAxNi43ODQxIDE1LjUgMTYuMzM2MSAxNS41IDE1LjQ0VjQuNTZDMTUuNSAzLjY2MzkyIDE1LjUgMy4yMTU4NyAxNS4zNDQzIDIuODczNjJDMTUuMjA3MyAyLjU3MjU2IDE0Ljk4ODggMi4zMjc3OSAxNC43MiAyLjE3NDM5QzE0LjQxNDQgMiAxNC4wMTQ0IDIgMTMuMjE0MyAySDcuNzg1NzFDNi45ODU2NCAyIDYuNTg1NiAyIDYuMjgwMDEgMi4xNzQzOUM2LjAxMTIxIDIuMzI3NzkgNS43OTI2NyAyLjU3MjU2IDUuNjU1NyAyLjg3MzYyQzUuNSAzLjIxNTg3IDUuNSAzLjY2MzkyIDUuNSA0LjU2VjE1LjQ0QzUuNSAxNi4zMzYxIDUuNSAxNi43ODQxIDUuNjU1NyAxNy4xMjY0QzUuNzkyNjcgMTcuNDI3NCA2LjAxMTIxIDE3LjY3MjIgNi4yODAwMSAxNy44MjU2QzYuNTg1NiAxOCA2Ljk4NTY0IDE4IDcuNzg1NzEgMThaTTEwLjg1NzEgMTQuNEMxMC44NTcxIDE0LjYyMDkgMTAuNjk3MiAxNC44IDEwLjUgMTQuOEMxMC4zMDI4IDE0LjggMTAuMTQyOSAxNC42MjA5IDEwLjE0MjkgMTQuNEMxMC4xNDI5IDE0LjE3OTEgMTAuMzAyOCAxNCAxMC41IDE0QzEwLjY5NzIgMTQgMTAuODU3MSAxNC4xNzkxIDEwLjg1NzEgMTQuNFpcIlxuXHRcdFx0XHRzdHJva2U9XCJjdXJyZW50Q29sb3JcIlxuXHRcdFx0XHRzdHJva2VXaWR0aD1cIjEuNjdcIlxuXHRcdFx0XHRzdHJva2VMaW5lY2FwPVwicm91bmRcIlxuXHRcdFx0XHRzdHJva2VMaW5lam9pbj1cInJvdW5kXCJcblx0XHRcdC8+XG5cdFx0PC9zdmc+XG5cdCksXG5cdGJhY2s6IChcblx0XHQ8c3ZnXG5cdFx0XHR3aWR0aD1cIjIwXCJcblx0XHRcdGhlaWdodD1cIjIwXCJcblx0XHRcdHZpZXdCb3g9XCIwIDAgMjAgMjBcIlxuXHRcdFx0ZmlsbD1cIm5vbmVcIlxuXHRcdFx0eG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiXG5cdFx0PlxuXHRcdFx0PHBhdGhcblx0XHRcdFx0ZD1cIk0xNS44MzM3IDkuOTk5OTZINC4xNjY5OU00LjE2Njk5IDkuOTk5OTZMMTAuMDAwMyAxNS44MzMzTTQuMTY2OTkgOS45OTk5NkwxMC4wMDAzIDQuMTY2NjNcIlxuXHRcdFx0XHRzdHJva2U9XCJjdXJyZW50Q29sb3JcIlxuXHRcdFx0XHRzdHJva2VXaWR0aD1cIjEuNjdcIlxuXHRcdFx0XHRzdHJva2VMaW5lY2FwPVwicm91bmRcIlxuXHRcdFx0XHRzdHJva2VMaW5lam9pbj1cInJvdW5kXCJcblx0XHRcdC8+XG5cdFx0PC9zdmc+XG5cdCksXG5cdGV5ZTogKFxuXHRcdDxzdmdcblx0XHRcdHdpZHRoPVwiNDhcIlxuXHRcdFx0aGVpZ2h0PVwiNDhcIlxuXHRcdFx0dmlld0JveD1cIjAgMCA0OCA0OFwiXG5cdFx0XHRmaWxsPVwibm9uZVwiXG5cdFx0XHR4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCJcblx0XHQ+XG5cdFx0XHQ8cGF0aFxuXHRcdFx0XHRkPVwiTTMwIDI0QzMwIDI1LjU5MTMgMjkuMzY3OSAyNy4xMTc0IDI4LjI0MjYgMjguMjQyNkMyNy4xMTc0IDI5LjM2NzkgMjUuNTkxMyAzMCAyNCAzMEMyMi40MDg3IDMwIDIwLjg4MjYgMjkuMzY3OSAxOS43NTc0IDI4LjI0MjZDMTguNjMyMSAyNy4xMTc0IDE4IDI1LjU5MTMgMTggMjRDMTggMjIuNDA4NyAxOC42MzIxIDIwLjg4MjYgMTkuNzU3NCAxOS43NTc0QzIwLjg4MjYgMTguNjMyMSAyMi40MDg3IDE4IDI0IDE4QzI1LjU5MTMgMTggMjcuMTE3NCAxOC42MzIxIDI4LjI0MjYgMTkuNzU3NEMyOS4zNjc5IDIwLjg4MjYgMzAgMjIuNDA4NyAzMCAyNFpcIlxuXHRcdFx0XHRzdHJva2U9XCJjdXJyZW50Q29sb3JcIlxuXHRcdFx0XHRzdHJva2VPcGFjaXR5PVwiMVwiXG5cdFx0XHRcdHN0cm9rZVdpZHRoPVwiM1wiXG5cdFx0XHRcdHN0cm9rZUxpbmVjYXA9XCJyb3VuZFwiXG5cdFx0XHRcdHN0cm9rZUxpbmVqb2luPVwicm91bmRcIlxuXHRcdFx0Lz5cblx0XHRcdDxwYXRoXG5cdFx0XHRcdGQ9XCJNNCAyNEM3LjIgMTUuODA2IDE0LjY3MiAxMCAyNCAxMEMzMy4zMjggMTAgNDAuOCAxNS44MDYgNDQgMjRDNDAuOCAzMi4xOTQgMzMuMzI4IDM4IDI0IDM4QzE0LjY3MiAzOCA3LjIgMzIuMTk0IDQgMjRaXCJcblx0XHRcdFx0c3Ryb2tlPVwiY3VycmVudENvbG9yXCJcblx0XHRcdFx0c3Ryb2tlT3BhY2l0eT1cIjFcIlxuXHRcdFx0XHRzdHJva2VXaWR0aD1cIjNcIlxuXHRcdFx0XHRzdHJva2VMaW5lY2FwPVwicm91bmRcIlxuXHRcdFx0XHRzdHJva2VMaW5lam9pbj1cInJvdW5kXCJcblx0XHRcdC8+XG5cdFx0PC9zdmc+XG5cdCksXG5cdCd3cC10cmF2ZWwtZW5naW5lLXdoaXRlJzogKFxuXHRcdDxzdmcgd2lkdGg9XCIyNFwiIGhlaWdodD1cIjE1XCIgdmlld0JveD1cIjAgMCAyNCAxNVwiIGZpbGw9XCJub25lXCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiPlxuXHRcdFx0PGcgY2xpcFBhdGg9XCJ1cmwoI2NsaXAwXzc5OTA2XzM1KVwiPlxuXHRcdFx0XHQ8cGF0aCBkPVwiTTIzLjg4NjkgNy42MzE1NUMyNC4yMTMzIDcuMTU5OTEgMjMuNzI3MyA2Ljc2MjQ1IDIzLjcyNzMgNi43NjI0NUMyMy43MjczIDYuNzYyNDUgMjMuMTYwMSA2LjQ4Mzk1IDIyLjgzNTEgNi45NTU1OEMyMi41MDg3IDcuNDI3MjIgMjEuNjg2MSA4LjYxOTYgMjEuNjg2MSA4LjYxOTZMMTguMzMwNSA4LjY5NTE4TDE3Ljc2NzYgOS41MDk2OUwyMC42MTY5IDEwLjE2NjFMMTkuMzM4OCAxMS42NTUxQzE5LjczNjMgMTEuODA0OSAyMC4wNzQzIDEyLjA4MDYgMjAuNDU3MyAxMi4yNjUzTDIxLjY2ODcgMTAuODQyTDIzLjM5MzYgMTMuMTI2TDIzLjk1NjUgMTIuMzExNUwyMi43MzY0IDkuMjk1NTdDMjIuNzM3OSA5LjI5NTU3IDIzLjU2MTkgOC4xMDMxOCAyMy44ODY5IDcuNjMxNTVaXCIgZmlsbD1cIndoaXRlXCIgLz5cblx0XHRcdFx0PHBhdGggZD1cIk0xMS45MTUyIDYuOTYxMThDMTIuOTI4NyA2Ljk2MTE4IDEzLjc1MDQgNi4xNjg1NSAxMy43NTA0IDUuMTkwOEMxMy43NTA0IDQuMjEzMDQgMTIuOTI4NyAzLjQyMDQxIDExLjkxNTIgMy40MjA0MUMxMC45MDE2IDMuNDIwNDEgMTAuMDggNC4yMTMwNCAxMC4wOCA1LjE5MDhDMTAuMDggNi4xNjg1NSAxMC45MDE2IDYuOTYxMTggMTEuOTE1MiA2Ljk2MTE4WlwiIGZpbGw9XCJ3aGl0ZVwiIC8+XG5cdFx0XHRcdDxwYXRoIGQ9XCJNMjIuMjM3MyA5LjA4ODFDMjEuOTMyNiA4LjkwMzM3IDIxLjUyOTMgOC45OTE1NCAyMS4zMzc4IDkuMjg2ODNDMTkuNDcyMSAxMi4xNDQ2IDE3LjUyMzcgMTMuNzA2NSAxNS43MDMgMTMuODAxN0MxMy4zOTc4IDEzLjkyNDggMTEuODgwMyAxMS43NTk4IDEwLjU1NTcgMTAuMzE0MUM5Ljg1MzU0IDkuNTQ3MTQgOS4yMTUyMSA4LjcyODQzIDguNjU4MTEgNy44NTc5M0M4LjIxNzA4IDcuMTcwNzcgNy43Mjk2MiA2LjM0OTI1IDcuNzI5NjIgNS41MTc5NEM3LjcyODE3IDMuMjA1OTQgOS42NzggMS4zMjQ5OSAxMi4wNzQ3IDEuMzI0OTlDMTQuNDY5OSAxLjMyNDk5IDE2LjQxOTcgMy4yMDU5NCAxNi40MTk3IDUuNTE3OTRDMTYuNDE5NyA2LjkwMjA2IDE0LjY5MzMgOS4xMTg4OSAxMy4yMTA2IDEwLjczNjdDMTIuOTc3IDEwLjk5MTQgMTMuMDAzMiAxMS4zNzkxIDEzLjI2MjggMTEuNjFDMTMuMjY3MiAxMS42MTI4IDEzLjI3MDEgMTEuNjE3IDEzLjI3NDQgMTEuNjE5OEMxMy41Mzg1IDExLjg1NDkgMTMuOTUzNCAxMS44MzI2IDE0LjE5MTMgMTEuNTczNkMxNS43MjMzIDkuOTEyNDIgMTcuNzI2OCA3LjM4MzQ5IDE3LjcyNjggNS41MTY1NEMxNy43MjU0IDIuNTEwMzggMTUuMTkwOSAwLjA2NTQyOTcgMTIuMDc0NyAwLjA2NTQyOTdDOC45NTg0MiAwLjA2NTQyOTcgNi40MjI0OSAyLjUxMDM4IDYuNDIyNDkgNS41MTc5NEM2LjQyMjQ5IDcuMjc0MzMgOC4xOTY3NyA5LjYxNzEyIDkuNjg2NzEgMTEuMjc1NUw5LjY4MjM1IDExLjI3MTNDMTAuMDc3IDExLjYzMSAxMC40MTY0IDEyLjA2NjMgMTAuNzg5MyAxMi40NDgzQzExLjUxMzIgMTMuMTg3MyAxMi4yNTMxIDEzLjk3NTIgMTMuMTgzIDE0LjQ3MDZDMTMuMTg0NSAxNC40NzIgMTMuMTg3NCAxNC40NzIgMTMuMTkzMiAxNC40NzYyQzEzLjgxNTYgMTQuODAzNyAxNC42MDA0IDE1LjA2NTQgMTUuNTIwMiAxNS4wNjU0QzE1LjU5NzEgMTUuMDY1NCAxNS42NzU1IDE1LjA2NCAxNS43NTUzIDE1LjA1OThDMTguMDU0NyAxNC45NDkzIDIwLjMwNDggMTMuMjMyMSAyMi40NDE4IDkuOTU3MkMyMi42MzQ4IDkuNjYzMyAyMi41NDE5IDkuMjc0MjQgMjIuMjM3MyA5LjA4ODFaXCIgZmlsbD1cIndoaXRlXCIgLz5cblx0XHRcdFx0PHBhdGggZD1cIk0xMC41MTA4IDEzLjU5MjJDMTAuMzIwOCAxMy40MTg3IDEwLjA0MjIgMTMuMzc1MyA5LjgwMTQyIDEzLjQ3MzNDOS4zNjYxOSAxMy42NDk2IDguODU2OTcgMTMuNzY3MiA4LjI4NjgyIDEzLjczNjRDNi40NjYxMSAxMy42NDEyIDQuNTE3NzMgMTIuMDc5MyAyLjY1OTMxIDkuMjMxMzNMMS4yMTE0NCA2LjkxNzkzQzEuMDI1NzQgNi42MTk4MyAwLjYyMzg4MyA2LjUyNDY2IDAuMzE2MzIxIDYuNzAzOEMwLjAwNzMwODI2IDYuODg0MzQgLTAuMDkyNzk0NSA3LjI3MDYxIDAuMDk0MzU0MSA3LjU2ODdMMS41NDgwMiA5Ljg5MTlDMy42ODUgMTMuMTY2OCA1LjkzNTEzIDE0Ljg4NCA4LjIzNDU5IDE0Ljk5NDVDOC4zMTQzOCAxNC45OTg3IDguMzkyNzMgMTUuMDAwMSA4LjQ2OTYyIDE1LjAwMDFDOS4xNjQ1MyAxNS4wMDAxIDkuNzg0MDEgMTQuODUwNCAxMC4zMTM1IDE0LjYzNDlDMTAuNzUwMiAxNC40NTcxIDEwLjg1NzYgMTMuOTA4NSAxMC41MTM3IDEzLjU5MzZMMTAuNTEwOCAxMy41OTIyWlwiIGZpbGw9XCJ3aGl0ZVwiIC8+XG5cdFx0XHQ8L2c+XG5cdFx0XHQ8ZGVmcz5cblx0XHRcdFx0PGNsaXBQYXRoIGlkPVwiY2xpcDBfNzk5MDZfMzVcIj5cblx0XHRcdFx0XHQ8cmVjdCB3aWR0aD1cIjI0XCIgaGVpZ2h0PVwiMTVcIiBmaWxsPVwiY3VycmVudENvbG9yXCIgLz5cblx0XHRcdFx0PC9jbGlwUGF0aD5cblx0XHRcdDwvZGVmcz5cblx0XHQ8L3N2Zz5cblx0KSxcblx0J3dwLXRyYXZlbC1lbmdpbmUtY29sb3InOiAoXG5cdFx0PHN2ZyB3aWR0aD1cIjQ5XCIgaGVpZ2h0PVwiMzJcIiB2aWV3Qm94PVwiMCAwIDQ5IDMyXCIgZmlsbD1cIm5vbmVcIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCI+XG5cdFx0XHQ8ZyBjbGlwUGF0aD1cInVybCgjY2xpcDBfNzk5MDZfNDYpXCI+XG5cdFx0XHRcdDxwYXRoIGQ9XCJNNDguNzY5MSAxNi4yODA2QzQ5LjQzNTYgMTUuMjc0NSA0OC40NDMzIDE0LjQyNjUgNDguNDQzMyAxNC40MjY1QzQ4LjQ0MzMgMTQuNDI2NSA0Ny4yODUyIDEzLjgzMjQgNDYuNjIxNyAxNC44Mzg1QzQ1Ljk1NTIgMTUuODQ0NyA0NC4yNzU4IDE4LjM4ODUgNDQuMjc1OCAxOC4zODg1TDM3LjQyNDggMTguNTQ5N0wzNi4yNzU1IDIwLjI4NzNMNDIuMDkyOCAyMS42ODc2TDM5LjQ4MzMgMjQuODY0M0M0MC4yOTQ5IDI1LjE4MzggNDAuOTg1MSAyNS43NzE5IDQxLjc2NyAyNi4xNjZMNDQuMjQwMyAyMy4xMjk2TDQ3Ljc2MjEgMjguMDAyMkw0OC45MTEzIDI2LjI2NDZMNDYuNDIwMyAxOS44MzA1QzQ2LjQyMzIgMTkuODMwNSA0OC4xMDU2IDE3LjI4NjggNDguNzY5MSAxNi4yODA2WlwiIGZpbGw9XCIjM0Y0OTRCXCIgLz5cblx0XHRcdFx0PHBhdGggZD1cIk0yNC4zMjY3IDE0Ljg1MDVDMjYuMzk2MSAxNC44NTA1IDI4LjA3MzYgMTMuMTU5NiAyOC4wNzM2IDExLjA3MzdDMjguMDczNiA4Ljk4NzgyIDI2LjM5NjEgNy4yOTY4OCAyNC4zMjY3IDcuMjk2ODhDMjIuMjU3NCA3LjI5Njg4IDIwLjU3OTggOC45ODc4MiAyMC41Nzk4IDExLjA3MzdDMjAuNTc5OCAxMy4xNTk2IDIyLjI1NzQgMTQuODUwNSAyNC4zMjY3IDE0Ljg1MDVaXCIgZmlsbD1cInVybCgjcGFpbnQwX2xpbmVhcl83OTkwNl80NilcIiAvPlxuXHRcdFx0XHQ8cGF0aCBkPVwiTTQ1LjA3NTUgMTkuMjQ4NEM0NC40NTM1IDE4Ljg1NDMgNDMuNjMwMSAxOS4wNDI0IDQzLjIzOTEgMTkuNjcyM0MzOS40MyAyNS43NjkgMzUuNDUyIDI5LjEwMSAzMS43MzQ4IDI5LjMwNEMyNy4wMjgyIDI5LjU2NjcgMjMuOTI5OSAyNC45NDc5IDIxLjIyNTcgMjEuODYzOEMxOS43OTIxIDIwLjIyNzcgMTguNDg4OCAxOC40ODExIDE3LjM1MTQgMTYuNjI0QzE2LjQ1MSAxNS4xNTgxIDE1LjQ1NTcgMTMuNDA1NSAxNS40NTU3IDExLjYzMkMxNS40NTI4IDYuNjk5NzYgMTkuNDMzNyAyLjY4NzA3IDI0LjMyNjggMi42ODcwN0MyOS4yMTcxIDIuNjg3MDcgMzMuMTk4IDYuNjk5NzYgMzMuMTk4IDExLjYzMkMzMy4xOTggMTQuNTg0OCAyOS42NzMyIDE5LjMxNDEgMjYuNjQ2MSAyMi43NjU0QzI2LjE2OTIgMjMuMzA4OCAyNi4yMjI1IDI0LjEzNTggMjYuNzUyNyAyNC42Mjg1QzI2Ljc2MTYgMjQuNjM0NCAyNi43Njc1IDI0LjY0MzQgMjYuNzc2NCAyNC42NDk0QzI3LjMxNTUgMjUuMTUxIDI4LjE2MjYgMjUuMTAzMiAyOC42NDg0IDI0LjU1MDhDMzEuNzc2MiAyMS4wMDY5IDM1Ljg2NjcgMTUuNjExOSAzNS44NjY3IDExLjYyOUMzNS44NjM4IDUuMjE1OSAzMC42ODkyIDAgMjQuMzI2OCAwQzE3Ljk2NDUgMCAxMi43ODcgNS4yMTU5IDEyLjc4NyAxMS42MzJDMTIuNzg3IDE1LjM3OSAxNi40MDk1IDIwLjM3NjkgMTkuNDUxNCAyMy45MTQ5TDE5LjQ0MjYgMjMuOTA2QzIwLjI0ODIgMjQuNjczMyAyMC45NDEzIDI1LjYwMTggMjEuNzAyNSAyNi40MTY5QzIzLjE4MDYgMjcuOTkzMyAyNC42OTEyIDI5LjY3NDIgMjYuNTg5OCAzMC43MzExQzI2LjU5MjggMzAuNzM0MSAyNi41OTg3IDMwLjczNDEgMjYuNjEwNSAzMC43NDNDMjcuODgxMiAzMS40NDE3IDI5LjQ4MzcgMzIgMzEuMzYxNSAzMkMzMS41MTg1IDMyIDMxLjY3ODUgMzEuOTk3IDMxLjg0MTQgMzEuOTg4MUMzNi41MzYxIDMxLjc1MjIgNDEuMTMwMSAyOC4wODg4IDQ1LjQ5MzEgMjEuMTAyNEM0NS44ODcxIDIwLjQ3NTUgNDUuNjk3NSAxOS42NDU1IDQ1LjA3NTUgMTkuMjQ4NFpcIiBmaWxsPVwiIzNGNDk0QlwiIC8+XG5cdFx0XHRcdDxwYXRoIGQ9XCJNMjEuNDU5NiAyOC45OTY3QzIxLjA3MTYgMjguNjI2NSAyMC41MDI5IDI4LjUzMzkgMjAuMDExMiAyOC43NDI5QzE5LjEyMjYgMjkuMTE5MSAxOC4wODMgMjkuMzY5OSAxNi45MTg5IDI5LjMwNDJDMTMuMjAxNiAyOS4xMDEyIDkuMjIzNzEgMjUuNzY5MiA1LjQyOTQyIDE5LjY5MzVMMi40NzMzNiAxNC43NTgyQzIuMDk0MjMgMTQuMTIyMyAxLjI3Mzc2IDEzLjkxOTMgMC42NDU4MjIgMTQuMzAxNEMwLjAxNDkyMSAxNC42ODY2IC0wLjE4OTQ1NSAxNS41MTA2IDAuMTkyNjQgMTYuMTQ2NkwzLjE2MDU0IDIxLjEwMjdDNy41MjM1MyAyOC4wODkxIDEyLjExNzYgMzEuNzUyNCAxNi44MTIzIDMxLjk4ODNDMTYuOTc1MiAzMS45OTczIDE3LjEzNTIgMzIuMDAwMyAxNy4yOTIxIDMyLjAwMDNDMTguNzEwOSAzMi4wMDAzIDE5Ljk3NTcgMzEuNjgwOCAyMS4wNTY4IDMxLjIyMUMyMS45NDg0IDMwLjg0MTggMjIuMTY3NSAyOS42NzE1IDIxLjQ2NTYgMjguOTk5N0wyMS40NTk2IDI4Ljk5NjdaXCIgZmlsbD1cInVybCgjcGFpbnQxX2xpbmVhcl83OTkwNl80NilcIiAvPlxuXHRcdFx0PC9nPlxuXHRcdFx0PGRlZnM+XG5cdFx0XHRcdDxsaW5lYXJHcmFkaWVudCBpZD1cInBhaW50MF9saW5lYXJfNzk5MDZfNDZcIiB4MT1cIjE4LjUzNjFcIiB5MT1cIjYuMTI5NDlcIiB4Mj1cIjMwLjI2ODVcIiB5Mj1cIjE2LjYxMThcIiBncmFkaWVudFVuaXRzPVwidXNlclNwYWNlT25Vc2VcIj5cblx0XHRcdFx0XHQ8c3RvcCBzdG9wQ29sb3I9XCIjMUZDMEExXCIgLz5cblx0XHRcdFx0XHQ8c3RvcCBzdG9wQ29sb3I9XCIjMUZDMEExXCIgLz5cblx0XHRcdFx0XHQ8c3RvcCBvZmZzZXQ9XCIxXCIgc3RvcENvbG9yPVwiIzAwQTg5RlwiIC8+XG5cdFx0XHRcdDwvbGluZWFyR3JhZGllbnQ+XG5cdFx0XHRcdDxsaW5lYXJHcmFkaWVudCBpZD1cInBhaW50MV9saW5lYXJfNzk5MDZfNDZcIiB4MT1cIi01Ljk2Nzc0XCIgeTE9XCIxMS4zNDM1XCIgeDI9XCIyMS44NjgzXCIgeTI9XCI0Mi4wMDI4XCIgZ3JhZGllbnRVbml0cz1cInVzZXJTcGFjZU9uVXNlXCI+XG5cdFx0XHRcdFx0PHN0b3Agc3RvcENvbG9yPVwiIzFGQzBBMVwiIC8+XG5cdFx0XHRcdFx0PHN0b3Agc3RvcENvbG9yPVwiIzFGQzBBMVwiIC8+XG5cdFx0XHRcdFx0PHN0b3Agb2Zmc2V0PVwiMVwiIHN0b3BDb2xvcj1cIiMwMEE4OUZcIiAvPlxuXHRcdFx0XHQ8L2xpbmVhckdyYWRpZW50PlxuXHRcdFx0XHQ8Y2xpcFBhdGggaWQ9XCJjbGlwMF83OTkwNl80NlwiPlxuXHRcdFx0XHRcdDxyZWN0IHdpZHRoPVwiNDlcIiBoZWlnaHQ9XCIzMlwiIGZpbGw9XCJ3aGl0ZVwiIC8+XG5cdFx0XHRcdDwvY2xpcFBhdGg+XG5cdFx0XHQ8L2RlZnM+XG5cdFx0PC9zdmc+XG5cblx0KSxcbn07XG5cbmZ1bmN0aW9uIEljb24oeyBpY29uLCBjbGFzc05hbWUsIHNpemUgfSkge1xuXHRjb25zdCBzdmdDbGFzcyA9IGNsYXNzTmFtZXMoXG5cdFx0J2N3LWljb24nLFxuXHRcdGNsYXNzTmFtZSAmJiBgY3ctaWNvbl9fJHtjbGFzc05hbWV9YFxuXHQpO1xuXG5cdHJldHVybiAoXG5cdFx0PHNwYW5cblx0XHRcdGNsYXNzTmFtZT17c3ZnQ2xhc3N9XG5cdFx0XHRzdHlsZT17e1xuXHRcdFx0XHQnLS1pY29uLXdpZHRoJzogc2l6ZT8ud2lkdGgsXG5cdFx0XHRcdCctLWljb24taGVpZ2h0Jzogc2l6ZT8uaGVpZ2h0LFxuXHRcdFx0fX1cblx0XHQ+XG5cdFx0XHR7aWNvbnNbaWNvbl19XG5cdFx0PC9zcGFuPlxuXHQpO1xufTtcblxuZXhwb3J0IGRlZmF1bHQgSWNvbiIsImV4cG9ydCB7IGRlZmF1bHQgYXMgSWNvbiB9IGZyb20gJy4vaWNvbi5qc3gnO1xuZXhwb3J0IHsgZGVmYXVsdCBhcyBDYXRlZ29yeSB9IGZyb20gJy4vU2lkZWJhci9DYXRlZ29yeS9DYXRlZ29yeS5qc3gnO1xuZXhwb3J0IHsgZGVmYXVsdCBhcyBTZWFyY2ggfSBmcm9tICcuL1NpZGViYXIvU2VhcmNoL1NlYXJjaC5qc3gnO1xuZXhwb3J0IHsgZGVmYXVsdCBhcyBUb3BCYXIgfSBmcm9tICcuL1RvcEJhci9Ub3BCYXIuanN4JztcbmV4cG9ydCB7IGRlZmF1bHQgYXMgUGF0dGVybkxpc3QgfSBmcm9tICcuL1BhdHRlcm5MaXN0L1BhdHRlcm5MaXN0LmpzeCc7XG5leHBvcnQgeyBkZWZhdWx0IGFzIEZhdkJ1dHRvbiB9IGZyb20gJy4vRmF2QnV0dG9uL0ZhdkJ1dHRvbi5qc3gnO1xuZXhwb3J0IHsgZGVmYXVsdCBhcyBSZXF1aXJlZFBsdWdpbnNNb2RhbCB9IGZyb20gJy4vUmVxdWlyZWRQbHVnaW5zTW9kYWwvUmVxdWlyZWRQbHVnaW5zTW9kYWwuanN4JztcbmV4cG9ydCB7IGRlZmF1bHQgYXMgTm90aWNlTW9kYWwgfSBmcm9tICcuL05vdGljZU1vZGFsL05vdGljZU1vZGFsLmpzeCc7XG5leHBvcnQgeyBkZWZhdWx0IGFzIFByZXZpZXdNb2RhbCB9IGZyb20gJy4vUHJldmlld01vZGFsL1ByZXZpZXdNb2RhbC5qc3gnO1xuZXhwb3J0IHsgZGVmYXVsdCBhcyBTZWdtZW50ZWRDb250cm9sIH0gZnJvbSAnLi9TZWdtZW50ZWRDb250cm9sL1NlZ21lbnRlZENvbnRyb2wuanN4JztcbiIsImltcG9ydCB7IHVzZVN0YXRlLCB1c2VNZW1vIH0gZnJvbSAnQHdvcmRwcmVzcy9lbGVtZW50JztcbmltcG9ydCB7IF9fIH0gZnJvbSAnQHdvcmRwcmVzcy9pMThuJztcbmltcG9ydCB7IENhdGVnb3J5LCBTZWFyY2gsIFRvcEJhciwgUGF0dGVybkxpc3QgfSBmcm9tICdjb21wb25lbnRzJztcbmltcG9ydCB7IHVzZURlYm91bmNlIH0gZnJvbSAndXRpbHMvaG9va3MnO1xuXG5jb25zdCBwbGFuT3B0aW9ucyA9IFtcblx0eyBsYWJlbDogJ0FsbCcsIHZhbHVlOiAnJyB9LFxuXHR7IGxhYmVsOiAnRnJlZScsIHZhbHVlOiAnZnJlZScgfSxcblx0eyBsYWJlbDogJ1BybycsIHZhbHVlOiAncHJvJyB9LFxuXTtcblxuZnVuY3Rpb24gUGF0dGVybkxpYnJhcnkoIHsgdGFiLCBkZXNpZ25zLCBjYXRlZ29yaWVzIH0gKSB7XG5cdGNvbnN0IFsgc2VhcmNoUXVlcnksIHNldFNlYXJjaFF1ZXJ5IF0gPSB1c2VTdGF0ZSggJycgKTtcblx0Y29uc3QgWyBmaWx0ZXJzLCBzZXRGaWx0ZXJzIF0gPSB1c2VTdGF0ZSggeyBwYXR0ZXJuczogJycsIHBhZ2U6ICcnIH0gKTtcblx0Y29uc3QgWyBwbGFucywgc2V0UGxhbnMgXSA9IHVzZVN0YXRlKCB7IHBhdHRlcm5zOiAnJywgcGFnZTogJycgfSApO1xuXHRjb25zdCBbIGxheW91dCwgc2V0TGF5b3V0IF0gPSB1c2VTdGF0ZSggMyApO1xuXHRjb25zdCBkZWJvdW5jZWRTZWFyY2hRdWVyeSA9IHVzZURlYm91bmNlKCBzZWFyY2hRdWVyeSwgNTAwICk7XG5cblx0Y29uc3QgaGFuZGxlU2VhcmNoID0gKCB2YWx1ZSApID0+IHtcblx0XHRzZXRTZWFyY2hRdWVyeSggdmFsdWUgKTtcblx0fTtcblxuXHRjb25zdCBoYW5kbGVMYXlvdXRDaGFuZ2UgPSAoIHZhbHVlICkgPT4ge1xuXHRcdHNldExheW91dCggdmFsdWUgKTtcblx0fTtcblxuXHRjb25zdCBoYW5kbGVQbGFuQ2hhbmdlID0gKCB2YWx1ZSwgZmlsdGVyVHlwZSApID0+IHtcblx0XHRzZXRQbGFucyggKCBwcmV2UGxhbnMgKSA9PiAoIHtcblx0XHRcdC4uLnByZXZQbGFucyxcblx0XHRcdFsgZmlsdGVyVHlwZSBdOiB2YWx1ZSxcblx0XHR9ICkgKTtcblx0fTtcblxuXHRjb25zdCBoYW5kbGVGaWx0ZXJDaGFuZ2UgPSAoIHZhbHVlLCBmaWx0ZXJUeXBlICkgPT4ge1xuXHRcdHNldEZpbHRlcnMoICggcHJldkZpbHRlcnMgKSA9PiAoIHtcblx0XHRcdC4uLnByZXZGaWx0ZXJzLFxuXHRcdFx0WyBmaWx0ZXJUeXBlIF06IHZhbHVlLFxuXHRcdH0gKSApO1xuXHR9O1xuXG5cdGNvbnN0IGZpbHRlcmVkRGVzaWducyA9IHVzZU1lbW8oICgpID0+IHtcblx0XHRpZiAoICEgZGVzaWducyApIHJldHVybiBbXTtcblx0XHRsZXQgZmlsdGVyZWREZXNpZ25zID0gZGVzaWducztcblxuXHRcdGlmICggZGVib3VuY2VkU2VhcmNoUXVlcnkgKSB7XG5cdFx0XHRmaWx0ZXJlZERlc2lnbnMgPSBmaWx0ZXJlZERlc2lnbnMuZmlsdGVyKCAoIGRlc2lnbiApID0+IHtcblx0XHRcdFx0Y29uc3QgcXVlcnlMb3dlckNhc2UgPSBzZWFyY2hRdWVyeS50b0xvY2FsZUxvd2VyQ2FzZSgpO1xuXHRcdFx0XHRjb25zdCB0aXRsZU1hdGNoID1cblx0XHRcdFx0XHRkZXNpZ24udGl0bGVcblx0XHRcdFx0XHRcdC50b0xvY2FsZUxvd2VyQ2FzZSgpXG5cdFx0XHRcdFx0XHQuaW5kZXhPZiggcXVlcnlMb3dlckNhc2UgKSAhPT0gLTE7XG5cdFx0XHRcdGNvbnN0IHRhZ01hdGNoID0gZGVzaWduLnRhZ1xuXHRcdFx0XHRcdD8gT2JqZWN0LnZhbHVlcyggZGVzaWduLnRhZyApLnNvbWUoXG5cdFx0XHRcdFx0XHRcdCggdGFnICkgPT5cblx0XHRcdFx0XHRcdFx0XHR0YWcubmFtZVxuXHRcdFx0XHRcdFx0XHRcdFx0LnRvTG9jYWxlTG93ZXJDYXNlKClcblx0XHRcdFx0XHRcdFx0XHRcdC5pbmRleE9mKCBxdWVyeUxvd2VyQ2FzZSApICE9PSAtMVxuXHRcdFx0XHRcdCAgKVxuXHRcdFx0XHRcdDogJyc7XG5cdFx0XHRcdHJldHVybiB0aXRsZU1hdGNoIHx8IHRhZ01hdGNoO1xuXHRcdFx0fSApO1xuXHRcdH1cblxuXHRcdGNvbnN0IGZpbHRlciA9IGZpbHRlcnNbIHRhYiBdO1xuXHRcdGlmICggZmlsdGVyICkge1xuXHRcdFx0ZmlsdGVyZWREZXNpZ25zID0gZmlsdGVyZWREZXNpZ25zLmZpbHRlciggKCBmaWx0ZXJlZERlc2lnbiApID0+IHtcblx0XHRcdFx0Y29uc3QgbWF0Y2hDYXRlZ29yeSA9IE9iamVjdC52YWx1ZXMoXG5cdFx0XHRcdFx0ZmlsdGVyZWREZXNpZ24uY3dfY2F0ZWdvcmllc1xuXHRcdFx0XHRcdFx0PyBmaWx0ZXJlZERlc2lnbi5jd19jYXRlZ29yaWVzXG5cdFx0XHRcdFx0XHQ6IFtdXG5cdFx0XHRcdCkuc29tZShcblx0XHRcdFx0XHQoIGNhdGVnb3J5ICkgPT5cblx0XHRcdFx0XHRcdGNhdGVnb3J5Lm5hbWUudG9Mb2NhbGVMb3dlckNhc2UoKSA9PT1cblx0XHRcdFx0XHRcdGZpbHRlci50b0xvY2FsZUxvd2VyQ2FzZSgpXG5cdFx0XHRcdCk7XG5cdFx0XHRcdGNvbnN0IG1hdGNoVGFnID0gZmlsdGVyZWREZXNpZ24udGFnXG5cdFx0XHRcdFx0PyBPYmplY3QudmFsdWVzKCBmaWx0ZXJlZERlc2lnbi50YWcgKS5zb21lKFxuXHRcdFx0XHRcdFx0XHQoIHRhZyApID0+XG5cdFx0XHRcdFx0XHRcdFx0dGFnLm5hbWUudG9Mb2NhbGVMb3dlckNhc2UoKSA9PT1cblx0XHRcdFx0XHRcdFx0XHRmaWx0ZXIudG9Mb2NhbGVMb3dlckNhc2UoKVxuXHRcdFx0XHRcdCAgKVxuXHRcdFx0XHRcdDogJyc7XG5cdFx0XHRcdHJldHVybiBtYXRjaENhdGVnb3J5IHx8IG1hdGNoVGFnO1xuXHRcdFx0fSApO1xuXHRcdH1cblxuXHRcdGNvbnN0IHBsYW4gPSBwbGFuc1sgdGFiIF07XG5cdFx0aWYgKCBwbGFuICkge1xuXHRcdFx0ZmlsdGVyZWREZXNpZ25zID0gZmlsdGVyZWREZXNpZ25zLmZpbHRlciggKCBkZXNpZ24gKSA9PiB7XG5cdFx0XHRcdGNvbnN0IGRlc2luZ1BsYW4gPVxuXHRcdFx0XHRcdGRlc2lnbi5tZXRhLmZyZWV2c3BybyA9PT0gJzEnID8gJ3BybycgOiAnZnJlZSc7XG5cdFx0XHRcdGNvbnN0IG1hdGNoUGxhbiA9IHBsYW4gPT09IGRlc2luZ1BsYW4udG9Mb2NhbGVMb3dlckNhc2UoKTtcblx0XHRcdFx0cmV0dXJuIG1hdGNoUGxhbjtcblx0XHRcdH0gKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gZmlsdGVyZWREZXNpZ25zO1xuXHR9LCBbIGRlc2lnbnMsIGZpbHRlcnMsIGRlYm91bmNlZFNlYXJjaFF1ZXJ5LCB0YWIsIHBsYW5zLCBzZWFyY2hRdWVyeSBdICk7XG5cblx0cmV0dXJuIChcblx0XHQ8ZGl2IGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9fY29udGVudFwiPlxuXHRcdFx0PGFzaWRlIGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9fc2lkZWJhclwiPlxuXHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9fc2lkZWJhci1zdGlja3lcIj5cblx0XHRcdFx0XHQ8U2VhcmNoXG5cdFx0XHRcdFx0XHRoYW5kbGVTZWFyY2g9eyBoYW5kbGVTZWFyY2ggfVxuXHRcdFx0XHRcdFx0c2VhcmNoUXVlcnk9eyBzZWFyY2hRdWVyeSB9XG5cdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHQ8Q2F0ZWdvcnlcblx0XHRcdFx0XHRcdGNhdGVnb3JpZXM9eyBjYXRlZ29yaWVzIH1cblx0XHRcdFx0XHRcdGhhbmRsZUNhdGVnb3J5PXsgKCB2YWx1ZSApID0+XG5cdFx0XHRcdFx0XHRcdGhhbmRsZUZpbHRlckNoYW5nZSggdmFsdWUsIHRhYiApXG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRkZXNpZ25Db3VudD17IGRlc2lnbnMubGVuZ3RoIH1cblx0XHRcdFx0XHRcdHNlbGVjdGVkQ2F0ZWdvcnk9eyBmaWx0ZXJzWyB0YWIgXSB9XG5cdFx0XHRcdFx0Lz5cblx0XHRcdFx0PC9kaXY+XG5cdFx0XHQ8L2FzaWRlPlxuXHRcdFx0PGRpdiBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX21haW5cIj5cblx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX3RvcGJhclwiPlxuXHRcdFx0XHRcdDxUb3BCYXJcblx0XHRcdFx0XHRcdHBsYW49eyBwbGFuc1sgdGFiIF0gfVxuXHRcdFx0XHRcdFx0cGxhbk9wdGlvbnM9eyBwbGFuT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRoYW5kbGVMYXlvdXRDaGFuZ2U9eyBoYW5kbGVMYXlvdXRDaGFuZ2UgfVxuXHRcdFx0XHRcdFx0aGFuZGxlUGxhbkNoYW5nZT17ICggdmFsdWUgKSA9PlxuXHRcdFx0XHRcdFx0XHRoYW5kbGVQbGFuQ2hhbmdlKCB2YWx1ZSwgdGFiIClcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdHNlbGVjdGVkTGF5b3V0PXsgbGF5b3V0IH1cblx0XHRcdFx0XHQvPlxuXHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX2Rlc2lnblwiPlxuXHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X19kZXNpZ24td3JhcFwiPlxuXHRcdFx0XHRcdFx0PFBhdHRlcm5MaXN0XG5cdFx0XHRcdFx0XHRcdGRlc2lnbnM9eyBmaWx0ZXJlZERlc2lnbnMgfVxuXHRcdFx0XHRcdFx0XHRzZWxlY3RlZExheW91dD17IGxheW91dCB9XG5cdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHQ8L2Rpdj5cblx0XHRcdDwvZGl2PlxuXHRcdDwvZGl2PlxuXHQpO1xufVxuXG5leHBvcnQgZGVmYXVsdCBQYXR0ZXJuTGlicmFyeTtcbiIsImltcG9ydCB7XG5cdE1vZGFsLFxuXHRWaXN1YWxseUhpZGRlbixcblx0VGFiUGFuZWwsXG5cdFNwaW5uZXIsXG59IGZyb20gJ0B3b3JkcHJlc3MvY29tcG9uZW50cyc7XG5pbXBvcnQgeyBfXyB9IGZyb20gJ0B3b3JkcHJlc3MvaTE4bic7XG5pbXBvcnQgeyBJY29uIH0gZnJvbSAnY29tcG9uZW50cyc7XG5pbXBvcnQgUGF0dGVybkxpYnJhcnkgZnJvbSAnLi9QYXR0ZXJuTGlicmFyeSc7XG5pbXBvcnQgeyBmZXRjaERlc2lnbnMgfSBmcm9tICd1dGlscyc7XG5pbXBvcnQgeyB1c2VTdGF0ZSwgdXNlRWZmZWN0LCB1c2VDYWxsYmFjaywgdXNlTWVtbyB9IGZyb20gJ0B3b3JkcHJlc3MvZWxlbWVudCc7XG5pbXBvcnQgeyB1c2VMb2NhbFN0b3JhZ2UsIE1vZGFsQ29udGV4dCB9IGZyb20gJ3V0aWxzL2hvb2tzJztcblxuZnVuY3Rpb24gUGF0dGVybk1vZGFsKCB7IG9uQ2xvc2UsIGZ1bGxTY3JlZW4sIGNsYXNzTmFtZSB9ICkge1xuXHRjb25zdCBbIHN5bmMsIHNldFN5bmMgXSA9IHVzZVN0YXRlKCBmYWxzZSApO1xuXHRjb25zdCBbIGlzTG9hZGluZywgc2V0SXNMb2FkaW5nIF0gPSB1c2VTdGF0ZSggZmFsc2UgKTtcblx0Y29uc3QgWyBpc0Vycm9yLCBzZXRJc0Vycm9yIF0gPSB1c2VTdGF0ZSggZmFsc2UgKTtcblx0Y29uc3QgWyBwYXR0ZXJucywgc2V0UGF0dGVybnMgXSA9IHVzZVN0YXRlKCBbXSApO1xuXHRjb25zdCBbIGZhdlBhdHRlcm4sIHNldEZhdlBhdHRlcm4gXSA9IHVzZUxvY2FsU3RvcmFnZShcblx0XHQnY3ctZmF2LXBhdHRlcm5zJyxcblx0XHRbXSxcblx0XHRmYWxzZVxuXHQpO1xuXG5cdC8vIEZldGNoIHBhdHRlcm5zXG5cdHVzZUVmZmVjdCggKCkgPT4ge1xuXHRcdGNvbnN0IGNvbnRyb2xsZXIgPSBuZXcgQWJvcnRDb250cm9sbGVyKCk7XG5cblx0XHRzZXRJc0xvYWRpbmcoIHRydWUgKTtcblx0XHRpZiAoIHN5bmMgKSB7XG5cdFx0XHRzZXRQYXR0ZXJucyggW10gKTtcblx0XHR9XG5cdFx0ZmV0Y2hEZXNpZ25zKCBzeW5jLCBjb250cm9sbGVyIClcblx0XHRcdC50aGVuKCAoIHJlc3BvbnNlICkgPT4ge1xuXHRcdFx0XHRpZiAoIHJlc3BvbnNlID09PSAnZXJyb3InICkge1xuXHRcdFx0XHRcdHNldElzRXJyb3IoIHRydWUgKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRzZXRQYXR0ZXJucyggcmVzcG9uc2UgKTtcblx0XHRcdFx0fVxuXHRcdFx0fSApXG5cdFx0XHQuZmluYWxseSggKCkgPT4ge1xuXHRcdFx0XHRzZXRTeW5jKCBmYWxzZSApO1xuXHRcdFx0XHRzZXRJc0xvYWRpbmcoIGZhbHNlICk7XG5cdFx0XHR9ICk7XG5cdFx0cmV0dXJuICgpID0+IGNvbnRyb2xsZXIuYWJvcnQoKTtcblx0fSwgWyBzeW5jIF0gKTtcblxuXHQvLyB3cmFwIGhhbmRsZVN5bmMgZnVuY3Rpb24gd2l0aCB1c2VDYWxsYmFjayB0byBhdm9pZCB1bm5lY2Vzc2FyeSByZS1yZW5kZXJzXG5cdGNvbnN0IGhhbmRsZVN5bmMgPSB1c2VDYWxsYmFjayggKCB2YWx1ZSApID0+IHtcblx0XHRzZXRTeW5jKCB2YWx1ZSApO1xuXHR9LCBbXSApO1xuXG5cdGNvbnN0IG1vZGFsQ29udGV4dFZhbHVlID0gdXNlTWVtbyhcblx0XHQoKSA9PiAoIHtcblx0XHRcdHN5bmMsXG5cdFx0XHRoYW5kbGVTeW5jLFxuXHRcdFx0b25DbG9zZSxcblx0XHR9ICksXG5cdFx0WyBzeW5jLCBoYW5kbGVTeW5jLCBvbkNsb3NlIF1cblx0KTtcblxuXHRmdW5jdGlvbiBmaWx0ZXJDYXRlZ29yaWVzKCBkZXNpZ25zICkge1xuXHRcdHJldHVybiBkZXNpZ25zLm1hcCggKCBwYXR0ZXJuICkgPT5cblx0XHRcdHBhdHRlcm4uY3dfY2F0ZWdvcmllcyA/IHBhdHRlcm4uY3dfY2F0ZWdvcmllcyA6IFtdXG5cdFx0KTtcblx0fVxuXG5cdHJldHVybiAoXG5cdFx0PE1vZGFsXG5cdFx0XHRvblJlcXVlc3RDbG9zZT17IG9uQ2xvc2UgfVxuXHRcdFx0aXNGdWxsU2NyZWVuPXsgZnVsbFNjcmVlbiB9XG5cdFx0XHRjbGFzc05hbWU9eyBjbGFzc05hbWUgfVxuXHRcdFx0dGl0bGU9e1xuXHRcdFx0XHQ8PlxuXHRcdFx0XHRcdDxJY29uIGljb249XCJ3cC10cmF2ZWwtZW5naW5lLWNvbG9yXCIgY2xhc3NOYW1lPVwibG9nb1wiIC8+XG5cdFx0XHRcdFx0PFZpc3VhbGx5SGlkZGVuPlxuXHRcdFx0XHRcdFx0eyBfXyggJ0NXIFBhdHRlcm4gTGlicmFyeScsICdjdy1wYXR0ZXJuLWltcG9ydCcgKSB9XG5cdFx0XHRcdFx0PC9WaXN1YWxseUhpZGRlbj5cblx0XHRcdFx0PC8+XG5cdFx0XHR9XG5cdFx0PlxuXHRcdFx0PGRpdiBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX3dyYXBcIj5cblx0XHRcdFx0PFRhYlBhbmVsXG5cdFx0XHRcdFx0Y2xhc3NOYW1lPVwiY3ctcGF0dGVybi1saWJyYXJ5X190YWJzXCJcblx0XHRcdFx0XHRhY3RpdmVDbGFzcz1cInBhdHRlcm5zXCJcblx0XHRcdFx0XHR0YWJzPXsgW1xuXHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRuYW1lOiAncGF0dGVybnMnLFxuXHRcdFx0XHRcdFx0XHR0aXRsZTogX18oICdQYXR0ZXJucycsICdjdy1wYXR0ZXJuLWltcG9ydCcgKSxcblx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lOiAnY3ctdGFiLWJ0biBwYXR0ZXJucyB0cmFuc2Zvcm0tc2NhbGUnLFxuXHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0bmFtZTogJ3BhZ2UnLFxuXHRcdFx0XHRcdFx0XHR0aXRsZTogX18oICdQYWdlJywgJ2N3LXBhdHRlcm4taW1wb3J0JyApLFxuXHRcdFx0XHRcdFx0XHRjbGFzc05hbWU6ICdjdy10YWItYnRuIHBhZ2UgdHJhbnNmb3JtLXNjYWxlJyxcblx0XHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdG5hbWU6ICdzYXZlZCcsXG5cdFx0XHRcdFx0XHRcdHRpdGxlOiBfXyggJ0Zhdm9yaXRlcycsICdjdy1wYXR0ZXJuLWltcG9ydCcgKSxcblx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lOiAnY3ctdGFiLWJ0biBzYXZlZCB0cmFuc2Zvcm0tc2NhbGUnLFxuXHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRdIH1cblx0XHRcdFx0PlxuXHRcdFx0XHRcdHsgKCB0YWIgKSA9PiB7XG5cdFx0XHRcdFx0XHRpZiAoIGlzRXJyb3IgKSB7XG5cdFx0XHRcdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLWxpYnJhcnlfX2NlbnRlclwiPlxuXHRcdFx0XHRcdFx0XHRcdFx0PEljb24gaWNvbj1cImVycm9yXCIgLz5cblx0XHRcdFx0XHRcdFx0XHRcdDxwPlxuXHRcdFx0XHRcdFx0XHRcdFx0XHR7IF9fKFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdTb21ldGhpbmcgd2VudCB3cm9uZyBwbGVhc2UgdHJ5IGFnYWluJyxcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY3ctcGF0dGVybi1pbXBvcnQnXG5cdFx0XHRcdFx0XHRcdFx0XHRcdCkgfVxuXHRcdFx0XHRcdFx0XHRcdFx0PC9wPlxuXHRcdFx0XHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHRcdFx0XHQpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0aWYgKCBpc0xvYWRpbmcgfHwgc3luYyApIHtcblx0XHRcdFx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeV9fY2VudGVyXCI+XG5cdFx0XHRcdFx0XHRcdFx0XHQ8U3Bpbm5lciAvPlxuXHRcdFx0XHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHRcdFx0XHQpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0bGV0IGRlc2lnbnM7XG5cdFx0XHRcdFx0XHRpZiAoIHRhYi5uYW1lID09PSAncGF0dGVybnMnICkge1xuXHRcdFx0XHRcdFx0XHRkZXNpZ25zID0gcGF0dGVybnMuZmlsdGVyKFxuXHRcdFx0XHRcdFx0XHRcdCggcGF0dGVybiApID0+IHBhdHRlcm4ubWV0YS5mdWxscGFnZSA9PT0gJydcblx0XHRcdFx0XHRcdFx0KTtcblx0XHRcdFx0XHRcdH0gZWxzZSBpZiAoIHRhYi5uYW1lID09PSAncGFnZScgKSB7XG5cdFx0XHRcdFx0XHRcdGRlc2lnbnMgPSBwYXR0ZXJucy5maWx0ZXIoXG5cdFx0XHRcdFx0XHRcdFx0KCBkZXNpZ24gKSA9PiBkZXNpZ24ubWV0YS5mdWxscGFnZSA9PT0gJzEnXG5cdFx0XHRcdFx0XHRcdCk7XG5cdFx0XHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdFx0XHRkZXNpZ25zID0gZmF2UGF0dGVybjtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdGNvbnN0IGNhdGVnb3JpZXMgPSBmaWx0ZXJDYXRlZ29yaWVzKCBkZXNpZ25zICk7XG5cdFx0XHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdFx0XHQ8TW9kYWxDb250ZXh0LlByb3ZpZGVyIHZhbHVlPXsgbW9kYWxDb250ZXh0VmFsdWUgfT5cblx0XHRcdFx0XHRcdFx0XHQ8UGF0dGVybkxpYnJhcnlcblx0XHRcdFx0XHRcdFx0XHRcdHRhYj17IHRhYi5uYW1lIH1cblx0XHRcdFx0XHRcdFx0XHRcdGRlc2lnbnM9eyBkZXNpZ25zIH1cblx0XHRcdFx0XHRcdFx0XHRcdGNhdGVnb3JpZXM9eyBjYXRlZ29yaWVzIH1cblx0XHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8L01vZGFsQ29udGV4dC5Qcm92aWRlcj5cblx0XHRcdFx0XHRcdCk7XG5cdFx0XHRcdFx0fSB9XG5cdFx0XHRcdDwvVGFiUGFuZWw+XG5cdFx0XHQ8L2Rpdj5cblx0XHQ8L01vZGFsPlxuXHQpO1xufVxuXG5leHBvcnQgZGVmYXVsdCBQYXR0ZXJuTW9kYWw7XG4iLCJpbXBvcnQgeyBfXyB9IGZyb20gJ0B3b3JkcHJlc3MvaTE4bic7XG5pbXBvcnQgeyBUb29sYmFyQnV0dG9uIH0gZnJvbSAnQHdvcmRwcmVzcy9jb21wb25lbnRzJztcbmltcG9ydCB7IHVzZVN0YXRlIH0gZnJvbSAnQHdvcmRwcmVzcy9lbGVtZW50JztcbmltcG9ydCBQYXR0ZXJuTW9kYWwgZnJvbSAnLi9QYXR0ZXJuTW9kYWwnO1xuaW1wb3J0IHsgSWNvbiB9IGZyb20gJ2NvbXBvbmVudHMnO1xuXG5mdW5jdGlvbiBUb29sYmFyV3JhcCgpIHtcblx0Y29uc3QgWyBpc01vZGFsT3Blbiwgc2V0SXNNb2RhbE9wZW4gXSA9IHVzZVN0YXRlKCBmYWxzZSApO1xuXG5cdHJldHVybiAoXG5cdFx0PD5cblx0XHRcdDxUb29sYmFyQnV0dG9uXG5cdFx0XHRcdGxhYmVsPXsgX18oICdPcGVuIENXIFBhdHRlcm4gTGlicmFyeScsICdjdy1wYXR0ZXJuLWltcG9ydCcgKSB9XG5cdFx0XHRcdG9uQ2xpY2s9eyAoKSA9PiB7XG5cdFx0XHRcdFx0c2V0SXNNb2RhbE9wZW4oIHRydWUgKTtcblx0XHRcdFx0fSB9IFxuXHRcdFx0XHRjbGFzc05hbWU9XCJjdy1wYXR0ZXJuLXRvb2xiYXItYnRuXCJcblx0XHRcdD5cdFxuXHRcdFx0XHQ8SWNvbiBpY29uPVwid3AtdHJhdmVsLWVuZ2luZS13aGl0ZVwiIC8+XG5cdFx0XHRcdHsgX18oICdQYXR0ZXJuIEVuZ2luZScsICdjdy1wYXR0ZXJuLWltcG9ydCcgKSB9XG5cdFx0XHQ8L1Rvb2xiYXJCdXR0b24+XG5cdFx0XHR7IGlzTW9kYWxPcGVuICYmIChcblx0XHRcdFx0PFBhdHRlcm5Nb2RhbFxuXHRcdFx0XHRcdG9uQ2xvc2U9eyAoKSA9PiBzZXRJc01vZGFsT3BlbiggZmFsc2UgKSB9XG5cdFx0XHRcdFx0ZnVsbFNjcmVlbj17IHRydWUgfVxuXHRcdFx0XHRcdGNsYXNzTmFtZT1cImN3LXBhdHRlcm4tbGlicmFyeVwiXG5cdFx0XHRcdC8+XG5cdFx0XHQpIH1cblx0XHQ8Lz5cblx0KTtcbn1cblxuZXhwb3J0IGRlZmF1bHQgVG9vbGJhcldyYXA7XG4iLCJpbXBvcnQgeyBjcmVhdGVSb290IH0gZnJvbSAnQHdvcmRwcmVzcy9lbGVtZW50JztcbmltcG9ydCB7IHN1YnNjcmliZSB9IGZyb20gJ0B3b3JkcHJlc3MvZGF0YSc7XG5pbXBvcnQgeyBfXyB9IGZyb20gJ0B3b3JkcHJlc3MvaTE4bic7XG5pbXBvcnQgVG9vbGJhcldyYXAgZnJvbSAnLi9Ub29sYmFyV3JhcC5qc3gnO1xuaW1wb3J0IGRvbVJlYWR5IGZyb20gJ0B3b3JkcHJlc3MvZG9tLXJlYWR5JztcblxuZnVuY3Rpb24gUGF0dGVyblRvb2xiYXIoKSB7XG5cdGNvbnN0IHRvb2xiYXJXcmFwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ2RpdicgKTtcblx0dG9vbGJhcldyYXAuY2xhc3NMaXN0LmFkZCggJ2N3LXRvb2xiYXItd3JhcHBlcicgKTtcblx0Y3JlYXRlUm9vdCggdG9vbGJhcldyYXAgKS5yZW5kZXIoIDxUb29sYmFyV3JhcCAvPiApO1xuXG5cdHN1YnNjcmliZSggKCkgPT4ge1xuXHRcdGNvbnN0IHRvb2xiYXIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnLmVkaXQtcG9zdC1oZWFkZXItdG9vbGJhcicgKTtcblx0XHRpZiAoICEgdG9vbGJhciApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0aWYgKCAhIHRvb2xiYXIucXVlcnlTZWxlY3RvciggJy5jdy10b29sYmFyLXdyYXBwZXInICkgKSB7XG5cdFx0XHR0b29sYmFyLmFwcGVuZENoaWxkKCB0b29sYmFyV3JhcCApO1xuXHRcdH1cblx0fSApO1xufVxuXG5kb21SZWFkeSggUGF0dGVyblRvb2xiYXIgKTtcbiIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyIsImltcG9ydCB7IGFkZFF1ZXJ5QXJncyB9IGZyb20gJ0B3b3JkcHJlc3MvdXJsJztcbmltcG9ydCBhcGlGZXRjaCBmcm9tICdAd29yZHByZXNzL2FwaS1mZXRjaCdcblxubGV0IHBhdHRlcm5zID0gbnVsbDtcblxuZXhwb3J0IGFzeW5jIGZ1bmN0aW9uIGZldGNoRGVzaWducyggc3luYywgY29udHJvbGxlciApIHtcblx0aWYgKCAhIHBhdHRlcm5zIHx8IHN5bmMgKSB7XG5cdFx0dHJ5IHtcblx0XHRcdGNvbnN0IHJlcyA9IGF3YWl0IGFwaUZldGNoKHtcblx0XHRcdFx0cGF0aDogYWRkUXVlcnlBcmdzKGAvYmxvY2stcGF0dGVybnMvdjEvcGF0dGVybnNgLCB7XG4gICAgICAgICAgICAgICAgICAgIHN5bmM6IHN5bmMsXG4gICAgICAgICAgICAgICAgfSksXG4gICAgICAgICAgICAgICAgc2lnbmFsOiBjb250cm9sbGVyPy5zaWduYWxcblx0XHRcdH0gKTtcblxuXHRcdFx0cGF0dGVybnMgPSBhd2FpdCByZXM7XG5cdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0bGV0IG1lc3NhZ2U7XG5cdFx0XHRpZiAoIGVycm9yLm5hbWUgPT09ICdBYm9ydEVycm9yJyApIHtcblx0XHRcdFx0bWVzc2FnZSA9ICdSZXF1ZXN0IGhhcyBiZWVuIGFib3J0ZWQnO1xuXHRcdFx0XHRjb25zb2xlLmxvZyggbWVzc2FnZSApO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0Y29uc29sZS5lcnJvciggZXJyb3IgKTtcblx0XHRcdFx0bWVzc2FnZSA9ICdBbiBlcnJvciBvY2N1cnJlZCB3aGlsZSBmZXRjaGluZyBkZXNpZ25zJztcblx0XHRcdH1cblx0XHRcdGNvbnNvbGUuZXJyb3IoIGBFUlJPUjogJHsgbWVzc2FnZSB9YCApO1xuXHRcdFx0cmV0dXJuICdlcnJvcic7XG5cdFx0fVxuXHR9XG5cblx0cmV0dXJuIHBhdHRlcm5zO1xufVxuIiwiaW1wb3J0IHsgdXNlU2VsZWN0IH0gZnJvbSAnQHdvcmRwcmVzcy9kYXRhJztcblxuZXhwb3J0IGZ1bmN0aW9uIEdldEFsbFBsdWdpbnMoKSB7XG5cdHJldHVybiB1c2VTZWxlY3QoICggc2VsZWN0ICkgPT4gc2VsZWN0KCAnY29yZScgKS5nZXRQbHVnaW5zKCksIFtdICk7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRQbHVnaW5zKCBzdGF0dXMgKSB7XG5cdGNvbnN0IHBsdWdpbnMgPSBHZXRBbGxQbHVnaW5zKClcblx0XHQ/LmZpbHRlciggKCBwbHVnaW4gKSA9PiBwbHVnaW4uc3RhdHVzID09PSBzdGF0dXMgKVxuXHRcdC5tYXAoICggcGx1Z2luICkgPT4ge1xuXHRcdFx0cmV0dXJuIHtcblx0XHRcdFx0bmFtZTogcGx1Z2luLm5hbWUsXG5cdFx0XHRcdHNsdWc6IHBsdWdpbi5wbHVnaW4uc3BsaXQoICcvJyApWyAwIF0sXG5cdFx0XHR9O1xuXHRcdH0gKTtcblx0cmV0dXJuIHBsdWdpbnMgPyBwbHVnaW5zIDogW107XG59XG4iLCJleHBvcnQgeyBkZWZhdWx0IGFzIHVzZUxvY2FsU3RvcmFnZSB9IGZyb20gJy4vdXNlTG9jYWxTdG9yYWdlLmpzeCc7XG5leHBvcnQgeyBkZWZhdWx0IGFzIHVzZURlYm91bmNlIH0gZnJvbSAnLi91c2VkZWJvdW5jZS5qc3gnO1xuZXhwb3J0ICogZnJvbSAnLi91c2VNb2RhbENvbnRleHQuanN4JztcbiIsImltcG9ydCB7IHVzZVN0YXRlLCB1c2VFZmZlY3QgfSBmcm9tICdAd29yZHByZXNzL2VsZW1lbnQnO1xuXG5mdW5jdGlvbiB1c2VMb2NhbFN0b3JhZ2UoIGtleSwgZGVmYXVsdFZhbHVlLCBkaXNwYXRjaCApIHtcblx0Y29uc3QgWyB2YWx1ZSwgc2V0VmFsdWUgXSA9IHVzZVN0YXRlKCAoKSA9PiB7XG5cdFx0bGV0IGN1cnJlbnRWYWx1ZTtcblxuXHRcdHRyeSB7XG5cdFx0XHRjdXJyZW50VmFsdWUgPSBKU09OLnBhcnNlKFxuXHRcdFx0XHRsb2NhbFN0b3JhZ2UuZ2V0SXRlbSgga2V5ICkgfHwgU3RyaW5nKCBkZWZhdWx0VmFsdWUgKVxuXHRcdFx0KTtcblx0XHR9IGNhdGNoICggZXJyb3IgKSB7XG5cdFx0XHRjdXJyZW50VmFsdWUgPSBkZWZhdWx0VmFsdWU7XG5cdFx0fVxuXHRcdHJldHVybiBjdXJyZW50VmFsdWU7XG5cdH0gKTtcblxuXHR1c2VFZmZlY3QoICgpID0+IHtcblx0XHRjb25zdCBoYW5kbGVTdG9yYWdlQ2hhbmdlID0gKCBlICkgPT4ge1xuXHRcdFx0aWYgKCBlLnN0b3JhZ2VBcmVhID09PSBsb2NhbFN0b3JhZ2UgJiYgZS5rZXkgPT09IGtleSApIHtcblx0XHRcdFx0dHJ5IHtcblx0XHRcdFx0XHRjb25zdCBuZXdWYWx1ZSA9IEpTT04ucGFyc2UoIGUubmV3VmFsdWUgKTtcblx0XHRcdFx0XHRzZXRWYWx1ZSggbmV3VmFsdWUgKTtcblx0XHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHRcdHNldFZhbHVlKCBkZWZhdWx0VmFsdWUgKTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH07XG5cblx0XHR3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lciggJ3N0b3JhZ2UnLCBoYW5kbGVTdG9yYWdlQ2hhbmdlICk7XG5cblx0XHRyZXR1cm4gKCkgPT4ge1xuXHRcdFx0d2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIoICdzdG9yYWdlJywgaGFuZGxlU3RvcmFnZUNoYW5nZSApO1xuXHRcdH07XG5cdH0sIFsga2V5LCBkZWZhdWx0VmFsdWUgXSApO1xuXG5cdHVzZUVmZmVjdCggKCkgPT4ge1xuXHRcdC8vIGRpc3BhdGNoIGEgc3RvcmFnZSBldmVudCBmcm9tIHRoZSBzYW1lIHRhYiBpZiBkaXNwYXRjaCBpcyB0cnVlXG5cdFx0aWYgKCBkaXNwYXRjaCApIHtcblx0XHRcdGNvbnN0IGN1cnJlbnRWYWx1ZSA9IGxvY2FsU3RvcmFnZS5nZXRJdGVtKCBrZXkgKTtcblx0XHRcdGlmICggY3VycmVudFZhbHVlICE9PSBKU09OLnN0cmluZ2lmeSggdmFsdWUgKSApIHtcblx0XHRcdFx0bG9jYWxTdG9yYWdlLnNldEl0ZW0oIGtleSwgSlNPTi5zdHJpbmdpZnkoIHZhbHVlICkgKTtcblxuXHRcdFx0XHQvLyBtYW51YWxseSBkaXNwYXRjaCBhIHN0b3JhZ2UgZXZlbnQgdG8gdHJpZ2dlciB0aGUgc3RvcmFnZSBldmVudCBsaXN0ZW5lclxuXHRcdFx0XHRjb25zdCBzdG9yYWdlRXZlbnQgPSBuZXcgU3RvcmFnZUV2ZW50KCAnc3RvcmFnZScsIHtcblx0XHRcdFx0XHRrZXksXG5cdFx0XHRcdFx0bmV3VmFsdWU6IEpTT04uc3RyaW5naWZ5KCB2YWx1ZSApLFxuXHRcdFx0XHRcdHN0b3JhZ2VBcmVhOiBsb2NhbFN0b3JhZ2UsXG5cdFx0XHRcdH0gKTtcblx0XHRcdFx0d2luZG93LmRpc3BhdGNoRXZlbnQoIHN0b3JhZ2VFdmVudCApO1xuXHRcdFx0fVxuXHRcdH0gZWxzZSB7XG5cdFx0XHRsb2NhbFN0b3JhZ2Uuc2V0SXRlbSgga2V5LCBKU09OLnN0cmluZ2lmeSggdmFsdWUgKSApO1xuXHRcdH1cblx0fSwgWyB2YWx1ZSwga2V5IF0gKTtcblxuXHRyZXR1cm4gWyB2YWx1ZSwgc2V0VmFsdWUsIGRpc3BhdGNoIF07XG59XG5cbmV4cG9ydCBkZWZhdWx0IHVzZUxvY2FsU3RvcmFnZTtcbiIsImltcG9ydCB7IGNyZWF0ZUNvbnRleHQsIHVzZUNvbnRleHQgfSBmcm9tICdAd29yZHByZXNzL2VsZW1lbnQnO1xuXG5leHBvcnQgY29uc3QgTW9kYWxDb250ZXh0ID0gY3JlYXRlQ29udGV4dCggdW5kZWZpbmVkICk7XG5cbmV4cG9ydCBmdW5jdGlvbiB1c2VNb2RhbENvbnRleHQoKSB7XG5cdGNvbnN0IG1vZGFsID0gdXNlQ29udGV4dCggTW9kYWxDb250ZXh0ICk7XG5cblx0aWYgKCBtb2RhbCA9PT0gdW5kZWZpbmVkICkge1xuXHRcdHRocm93IG5ldyBFcnJvciggJ3VzZU1vZGFsQ29udGV4dCBtdXN0IGJlIHVzZWQgd2l0aCBhIE1vZGFsQ29udGV4dCcgKTtcblx0fVxuXG5cdHJldHVybiBtb2RhbDtcbn1cbiIsImltcG9ydCB7IHVzZVN0YXRlLCB1c2VFZmZlY3QgfSBmcm9tICdAd29yZHByZXNzL2VsZW1lbnQnO1xuXG5mdW5jdGlvbiB1c2VEZWJvdW5jZSggdmFsdWUsIGRlbGF5ICkge1xuXHRjb25zdCBbIGRlYm91bmNlZFZhbHVlLCBzZXREZWJvdW5jZWRWYWx1ZSBdID0gdXNlU3RhdGUoIHZhbHVlICk7XG5cblx0dXNlRWZmZWN0KCAoKSA9PiB7XG5cdFx0Y29uc3QgaGFuZGxlciA9IHNldFRpbWVvdXQoICgpID0+IHtcblx0XHRcdHNldERlYm91bmNlZFZhbHVlKCB2YWx1ZSApO1xuXHRcdH0sIGRlbGF5ICk7XG5cblx0XHRyZXR1cm4gKCkgPT4ge1xuXHRcdFx0Y2xlYXJUaW1lb3V0KCBoYW5kbGVyICk7XG5cdFx0fTtcblx0fSwgWyB2YWx1ZSwgZGVsYXkgXSApO1xuXG5cdHJldHVybiBkZWJvdW5jZWRWYWx1ZTtcbn1cblxuZXhwb3J0IGRlZmF1bHQgdXNlRGVib3VuY2U7XG4iLCJleHBvcnQgeyBmZXRjaERlc2lnbnMgfSBmcm9tICcuL2ZldGNoJztcbmV4cG9ydCB7IGluc2VydFBhdHRlcm4gfSBmcm9tICcuL2luc2VydFBhdHRlcm4nO1xuZXhwb3J0IHsgR2V0QWxsUGx1Z2lucyBhcyBnZXRBbGxQbHVnaW5zIH0gZnJvbSAnLi9nZXRQbHVnaW5zJztcbmV4cG9ydCB7IGdldFBsdWdpbnMgfSBmcm9tICcuL2dldFBsdWdpbnMnO1xuZXhwb3J0IHsgb2JqZWN0RXhpc3RzSW5BcnJheSB9IGZyb20gJy4vb2JqZWN0RXhpc3RzSW5BcnJheSc7XG4iLCJpbXBvcnQgeyBwYXJzZSB9IGZyb20gJ0B3b3JkcHJlc3MvYmxvY2tzJztcbmltcG9ydCB7IGRpc3BhdGNoIH0gZnJvbSAnQHdvcmRwcmVzcy9kYXRhJztcblxuZXhwb3J0IGFzeW5jIGZ1bmN0aW9uIGluc2VydFBhdHRlcm4oIHtkZXNpZ24sIHJlbG9hZCA9IGZhbHNlLCBjbG9zZU1vZGFsID0gdW5kZWZpbmVkLCBjbG9zZVBhdHRlcm5MaWJyYXJ5TW9kYWx9ICkge1xuXHRjb25zdCBjb250cm9sbGVyID1cblx0XHR0eXBlb2YgQWJvcnRDb250cm9sbGVyID09PSAndW5kZWZpbmVkJ1xuXHRcdFx0PyB1bmRlZmluZWRcblx0XHRcdDogbmV3IEFib3J0Q29udHJvbGxlcigpO1xuXG5cdHRyeSB7XG5cdFx0Y29uc3QgdXJsID0gYGh0dHBzOi8vZnNlZGVtby5jb20vcGF0dGVybi1lbmdpbmUvd3AtanNvbi9ibG9jay1wYXR0ZXJuL3YxL3BhdHRlcm5zLyR7IGRlc2lnbi5pZCB9YDtcblx0XHRjb25zdCByZXMgPSBhd2FpdCBmZXRjaCggdXJsLCB7XG5cdFx0XHRtZXRob2Q6ICdHRVQnLFxuXHRcdFx0c2lnbmFsOiBjb250cm9sbGVyLnNpZ25hbCxcblx0XHRcdGhlYWRlcnM6IHtcblx0XHRcdFx0J0NvbnRlbnQtVHlwZSc6ICdhcHBsaWNhdGlvbi9qc29uJyxcblx0XHRcdH0sXG5cdFx0fSApO1xuXG5cdFx0aWYgKCAhIHJlcy5vayApIHtcblx0XHRcdHRocm93IG5ldyBFcnJvciggJ05ldHdvcmsgcmVzcG9uc2Ugd2FzIG5vdCBvaycgKTtcblx0XHR9XG5cdFx0Y29uc3QgcGF0dGVybiA9IGF3YWl0IHJlcy5qc29uKCk7XG5cblx0XHR0cnkge1xuXHRcdFx0Y29uc3QgcGFyc2VkQmxvY2tzID0gSlNPTi5wYXJzZSggcGF0dGVybi5jb250ZW50ICk7XG5cdFx0XHRkaXNwYXRjaCggJ2NvcmUvYmxvY2stZWRpdG9yJyApLmluc2VydEJsb2Nrcyhcblx0XHRcdFx0cGFyc2UoIHBhcnNlZEJsb2NrcyApXG5cdFx0XHQpO1xuXG5cdFx0XHRpZiAoIGN3QWRtaW4udXNlckNhbi5lZGl0UG9zdCAmJiByZWxvYWQgKSB7XG5cdFx0XHRcdHRyeSB7XG5cdFx0XHRcdFx0YXdhaXQgZGlzcGF0Y2goICdjb3JlL2VkaXRvcicgKS5zYXZlUG9zdCgpO1xuXHRcdFx0XHRcdGlmICh0eXBlb2YgY2xvc2VNb2RhbCA9PT0gJ2Z1bmN0aW9uJykge1xuXHRcdFx0XHRcdFx0Y2xvc2VNb2RhbCgpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0XHRpZiAodHlwZW9mIGNsb3NlUGF0dGVybkxpYnJhcnlNb2RhbCA9PT0gJ2Z1bmN0aW9uJykge1xuXHRcdFx0XHRcdFx0Y2xvc2VQYXR0ZXJuTGlicmFyeU1vZGFsKCk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdHdpbmRvdy5sb2NhdGlvbi5yZWxvYWQoKTtcblx0XHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHRcdGNvbnNvbGUuZXJyb3IoICdFcnJvciBzYXZpbmcgcG9zdDonLCBlcnJvciApO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0XHRpZiAodHlwZW9mIGNsb3NlTW9kYWwgPT09ICdmdW5jdGlvbicpIHtcblx0XHRcdFx0Y2xvc2VNb2RhbCgpO1xuXHRcdFx0fVxuXHRcdFx0aWYgKHR5cGVvZiBjbG9zZVBhdHRlcm5MaWJyYXJ5TW9kYWwgPT09ICdmdW5jdGlvbicpIHtcblx0XHRcdFx0Y2xvc2VQYXR0ZXJuTGlicmFyeU1vZGFsKCk7XG5cdFx0XHR9XG5cdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0Y29uc29sZS5lcnJvciggJ0Vycm9yIHBhcnNpbmcgb3IgaW5zZXJ0aW5nIGJsb2NrczonLCBlcnJvciApO1xuXHRcdH1cblx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdGlmICggZXJyb3IubmFtZSA9PSAnQWJvcnRFcnJvcicgKSB7XG5cdFx0XHRjb25zb2xlLmVycm9yKCAnQ29udGVudCBmZXRjaCBhYm9ydGVkIGZvciBVUkw6JywgZXJyb3IubWVzc2FnZSApO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRjb25zb2xlLmVycm9yKCAnRXJyb3IgZmV0Y2hpbmcgY29udGVudCBmcm9tIFVSTDonLCBlcnJvci5tZXNzYWdlICk7XG5cdFx0fVxuXHR9IGZpbmFsbHkge1xuXHRcdGNvbnRyb2xsZXI/LmFib3J0KCk7XG5cdH1cbn1cbiIsImV4cG9ydCBmdW5jdGlvbiBvYmplY3RFeGlzdHNJbkFycmF5KCBvYmosIGFycmF5ICkge1xuXHRyZXR1cm4gYXJyYXkuc29tZSggKCBlbCApID0+IGVsLnNsdWcgPT09IG9iai5zbHVnICk7XG59XG4iLCJtb2R1bGUuZXhwb3J0cyA9IHdpbmRvd1tcIndwXCJdW1wiYXBpRmV0Y2hcIl07IiwibW9kdWxlLmV4cG9ydHMgPSB3aW5kb3dbXCJ3cFwiXVtcImJsb2Nrc1wiXTsiLCJtb2R1bGUuZXhwb3J0cyA9IHdpbmRvd1tcIndwXCJdW1wiY29tcG9uZW50c1wiXTsiLCJtb2R1bGUuZXhwb3J0cyA9IHdpbmRvd1tcIndwXCJdW1wiZGF0YVwiXTsiLCJtb2R1bGUuZXhwb3J0cyA9IHdpbmRvd1tcIndwXCJdW1wiZG9tUmVhZHlcIl07IiwibW9kdWxlLmV4cG9ydHMgPSB3aW5kb3dbXCJ3cFwiXVtcImVsZW1lbnRcIl07IiwibW9kdWxlLmV4cG9ydHMgPSB3aW5kb3dbXCJ3cFwiXVtcImkxOG5cIl07IiwibW9kdWxlLmV4cG9ydHMgPSB3aW5kb3dbXCJ3cFwiXVtcInVybFwiXTsiLCJtb2R1bGUuZXhwb3J0cyA9IHdpbmRvd1tcIlJlYWN0XCJdOyIsIi8vIFRoZSBtb2R1bGUgY2FjaGVcbnZhciBfX3dlYnBhY2tfbW9kdWxlX2NhY2hlX18gPSB7fTtcblxuLy8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbmZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG5cdHZhciBjYWNoZWRNb2R1bGUgPSBfX3dlYnBhY2tfbW9kdWxlX2NhY2hlX19bbW9kdWxlSWRdO1xuXHRpZiAoY2FjaGVkTW9kdWxlICE9PSB1bmRlZmluZWQpIHtcblx0XHRyZXR1cm4gY2FjaGVkTW9kdWxlLmV4cG9ydHM7XG5cdH1cblx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcblx0dmFyIG1vZHVsZSA9IF9fd2VicGFja19tb2R1bGVfY2FjaGVfX1ttb2R1bGVJZF0gPSB7XG5cdFx0Ly8gbm8gbW9kdWxlLmlkIG5lZWRlZFxuXHRcdC8vIG5vIG1vZHVsZS5sb2FkZWQgbmVlZGVkXG5cdFx0ZXhwb3J0czoge31cblx0fTtcblxuXHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cblx0X193ZWJwYWNrX21vZHVsZXNfX1ttb2R1bGVJZF0obW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cblx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcblx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xufVxuXG4iLCIvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuX193ZWJwYWNrX3JlcXVpcmVfXy5uID0gKG1vZHVsZSkgPT4ge1xuXHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cblx0XHQoKSA9PiAobW9kdWxlWydkZWZhdWx0J10pIDpcblx0XHQoKSA9PiAobW9kdWxlKTtcblx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgeyBhOiBnZXR0ZXIgfSk7XG5cdHJldHVybiBnZXR0ZXI7XG59OyIsIi8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb25zIGZvciBoYXJtb255IGV4cG9ydHNcbl9fd2VicGFja19yZXF1aXJlX18uZCA9IChleHBvcnRzLCBkZWZpbml0aW9uKSA9PiB7XG5cdGZvcih2YXIga2V5IGluIGRlZmluaXRpb24pIHtcblx0XHRpZihfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZGVmaW5pdGlvbiwga2V5KSAmJiAhX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIGtleSkpIHtcblx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBrZXksIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBkZWZpbml0aW9uW2tleV0gfSk7XG5cdFx0fVxuXHR9XG59OyIsIl9fd2VicGFja19yZXF1aXJlX18ubyA9IChvYmosIHByb3ApID0+IChPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqLCBwcm9wKSkiLCIvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG5fX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSAoZXhwb3J0cykgPT4ge1xuXHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcblx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcblx0fVxuXHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xufTsiLCJpbXBvcnQgJy4vcGF0dGVybi1saWJyYXJ5L3Rvb2xiYXInO1xuaW1wb3J0ICcuL3N0eWxlL2luZGV4LnNjc3MnO1xuIl0sIm5hbWVzIjpbIkljb24iLCJCdXR0b24iLCJfXyIsIkZhdkJ1dHRvbiIsImljb24iLCJoYW5kbGVDbGljayIsImNyZWF0ZUVsZW1lbnQiLCJjbGFzc05hbWUiLCJvbkNsaWNrIiwibGFiZWwiLCJNb2RhbCIsIk5vdGljZU1vZGFsIiwiY2xvc2VNb2RhbCIsIm9uUmVxdWVzdENsb3NlIiwiVmlzdWFsbHlIaWRkZW4iLCJjbGFzc05hbWVzIiwidXNlTG9jYWxTdG9yYWdlIiwidXNlTW9kYWxDb250ZXh0IiwiUmVxdWlyZWRQbHVnaW5zTW9kYWwiLCJQcmV2aWV3TW9kYWwiLCJ1c2VTdGF0ZSIsInVzZUVmZmVjdCIsImluc2VydFBhdHRlcm4iLCJvYmplY3RFeGlzdHNJbkFycmF5IiwiUGF0dGVybkxpc3QiLCJkZXNpZ25zIiwic2VsZWN0ZWRMYXlvdXQiLCJmYXZQYXR0ZXJuIiwic2V0RmF2UGF0dGVybiIsImRlc2lnbnNMaXN0Iiwic2V0RGVzaWduc0xpc3QiLCJpc09wZW4iLCJzZXRPcGVuIiwicmVxdWlyZWRQbHVnaW5zIiwic2V0UmVxdWlyZWRQbHVnaW5zIiwiZGVzaWduVG9JbXBvcnQiLCJzZXREZXNpZ25Ub0ltcG9ydCIsImlzTW9kYWxPcGVuIiwic2V0SXNQcmV2aWV3T3BlbiIsImlmcmFtZUxpbmsiLCJzZXRJZnJhbWVMaW5rIiwiZGlzYWJsZWRCdXR0b25zIiwic2V0RGlzYWJsZWRCdXR0b25zIiwib25DbG9zZSIsImNsb3NlUGF0dGVybkxpYnJhcnlNb2RhbCIsImxlbmd0aCIsIm1hcCIsInRpdGxlIiwiZmVhdHVyZWRfbWVkaWEiLCJpZCIsInBlcm1hbGluayIsImRhdGEiLCJmYXZJY29uIiwiZmluZCIsInBhdHRlcm4iLCJrZXkiLCJ0b2dnbGVGYXYiLCJ1bmRlZmluZWQiLCJzcmMiLCJsb2FkaW5nIiwiaGFuZGxlUHJldmlld01vZGFsIiwibWV0YSIsImZyZWV2c3BybyIsImN3QWRtaW4iLCJsaWNlbnNlX2tleSIsIkltcG9ydEJ1dHRvbiIsImhyZWYiLCJkZXNpZ25MaXN0Q2xhc3MiLCJoYW5kbGVJbnNlcnQiLCJzZWxlY3RlZElkIiwiZGVzaWduIiwicHJldlN0YXRlIiwiZmlsdGVyZWRSZXF1aXJlZFBsdWdpbnMiLCJyZXF1aXJlZF9wbHVnaW5zIiwiZmlsdGVyIiwicmVxdWlyZWRQbHVnaW4iLCJhY3RpdmVQbHVnaW4iLCJ0aGVuIiwiY2F0Y2giLCJmYXZJZCIsInByZXZQYXR0ZXJucyIsImRlc2lnbkluZGV4IiwiZmluZEluZGV4IiwiXyIsImluZGV4IiwiaXNEaXNhYmxlZCIsInNob3dUb29sdGlwIiwiaXNCdXN5IiwiY2xvc2VQcmV2aWV3TW9kYWwiLCJGcmFnbWVudCIsInBsdWdpbnMiLCJwYXR0ZXJuTGluayIsIlNwaW5uZXIiLCJTZWdtZW50ZWRDb250cm9sIiwidXNlUmVmIiwiaWZyYW1lV2lkdGgiLCJzZXRJZnJhbWVXaWR0aCIsImlzTG9hZGluZyIsInNldElzTG9hZGluZyIsImhhbmRsZVdpbmRvd1dpZHRoIiwidmFsIiwiaXNGdWxsU2NyZWVuIiwic2hvdWxkQ2xvc2VPbkNsaWNrT3V0c2lkZSIsIm5hbWUiLCJjYWxsYmFjayIsImRlZmF1bHRJbmRleCIsImNvbnRyb2xSZWYiLCJzZWdtZW50cyIsInZhbHVlIiwicmVmIiwic3R5bGUiLCJhbGxvd0Z1bGxTY3JlZW4iLCJhbGxvd3RyYW5zcGFyZW5jeSIsImJvcmRlciIsInBvc2l0aW9uIiwidG9wIiwibGVmdCIsIndpZHRoIiwiaGVpZ2h0IiwidHJhbnNpdGlvbiIsInJlZmVycmVyUG9saWN5Iiwib25Mb2FkIiwidXNlTWVtbyIsInByb3BzIiwiYnV0dG9uU3RhdGUiLCJzZXRCdXR0b25TdGF0ZSIsImFjdGl2YXRlVXJscyIsInNldEFjdGl2YXRlVXJscyIsInBsdWdpbnNMaXN0IiwicGx1Z2luIiwicGx1Z2luc1RvSW5zdGFsbCIsInVzZXJDYW4iLCJpbnN0YWxsUGx1Z2lucyIsImluYWN0aXZlIiwicGx1Z2luc1RvQWN0aXZhdGUiLCJ1cmxzIiwidXJsIiwicmVwbGFjZSIsInByZXZVcmxzIiwiYWxlcnQiLCJ3cCIsImluc3RhbGxQcm9taXNlcyIsIlByb21pc2UiLCJyZXNvbHZlIiwicmVqZWN0IiwidXBkYXRlcyIsImluc3RhbGxQbHVnaW4iLCJzbHVnIiwic3VjY2VzcyIsInJlc3BvbnNlIiwiYWN0aXZhdGVVcmwiLCJlcnJvciIsImNvbnNvbGUiLCJhbGwiLCJsb2ciLCJhY3RpdmF0ZVBsdWdpbnMiLCJmZXRjaCIsIm1ldGhvZCIsImhlYWRlcnMiLCJib2R5IiwiVVJMU2VhcmNoUGFyYW1zIiwiX3dwbm9uY2UiLCJVUkwiLCJzZWFyY2hQYXJhbXMiLCJnZXQiLCJhY3Rpb24iLCJ0b1N0cmluZyIsIm9rIiwiRXJyb3IiLCJzdGF0dXMiLCJoYW5kbGVEZXNpZ25JbXBvcnQiLCJyZWxvYWQiLCJzaXplIiwidmFyaWFudCIsImRpc2FibGVkIiwiYWN0aXZlSW5kZXgiLCJzZXRBY3RpdmVJbmRleCIsImNvbXBvbmVudFJlYWR5IiwiY3VycmVudCIsImFjdGl2ZVNlZ21lbnRSZWYiLCJvZmZzZXRXaWR0aCIsIm9mZnNldExlZnQiLCJzZXRQcm9wZXJ0eSIsIm9uSW5wdXRDaGFuZ2UiLCJjb250cm9sc0NsYXNzIiwicmVhZHkiLCJpZGxlIiwiaXRlbSIsImkiLCJhY3RpdmUiLCJ0eXBlIiwib25DaGFuZ2UiLCJjaGVja2VkIiwiaHRtbEZvciIsIkNhdGVnb3J5IiwiaGFuZGxlQ2F0ZWdvcnkiLCJjYXRlZ29yaWVzIiwiZGVzaWduQ291bnQiLCJzZWxlY3RlZENhdGVnb3J5IiwiZmxhdENhdGVnb3JpZXMiLCJmbGF0TWFwIiwiT2JqZWN0IiwidmFsdWVzIiwidW5pcXVlQ2F0ZWdvcmllcyIsIkFycmF5IiwiZnJvbSIsIk1hcCIsIm9yZGVyIiwib3JkZXJlZFVuaXF1ZUNhdGVnb3JpZXMiLCJzb3J0IiwiYSIsImIiLCJpbmRleEEiLCJpbmRleE9mIiwiaW5kZXhCIiwibG9jYWxlQ29tcGFyZSIsImFsbENhdEJ0biIsImNhdGVnb3J5QnV0dG9ucyIsImNvdW50IiwiY2F0ZWdvcnlCdG4iLCJTZWFyY2hDb250cm9sIiwiU2VhcmNoIiwiaGFuZGxlU2VhcmNoIiwic2VhcmNoUXVlcnkiLCJwbGFjZWhvbGRlciIsIlNlbGVjdENvbnRyb2wiLCJUb3BCYXIiLCJoYW5kbGVMYXlvdXRDaGFuZ2UiLCJwbGFuT3B0aW9ucyIsInBsYW4iLCJoYW5kbGVQbGFuQ2hhbmdlIiwic3luYyIsImhhbmRsZVN5bmMiLCJjb2wyQ2xhc3MiLCJjb2wzQ2xhc3MiLCJmcmVlUHJvRmlsdGVyIiwib3B0aW9ucyIsIl9fbmV4dEhhc05vTWFyZ2luQm90dG9tIiwiaWNvbnMiLCJsb2dvIiwidmlld0JveCIsImZpbGwiLCJ4bWxucyIsImQiLCJjbGlwUGF0aCIsIngxIiwieTEiLCJ4MiIsInkyIiwiZ3JhZGllbnRVbml0cyIsInN0b3BDb2xvciIsIm9mZnNldCIsImNvbDIiLCJjb2wzIiwib3BhY2l0eSIsInN0cm9rZSIsInN0cm9rZVdpZHRoIiwic3Ryb2tlTGluZWNhcCIsInN0cm9rZUxpbmVqb2luIiwiY3Jvd24iLCJpbWciLCJkb3duX2Fycm93IiwiaGVhcnRfZmlsbCIsIm1hc2tUeXBlIiwibWFza1VuaXRzIiwieCIsInkiLCJtYXNrIiwiaGVhcnRfb3V0bGluZSIsInJ4IiwiZmlsbE9wYWNpdHkiLCJzdHJva2VPcGFjaXR5IiwiZGVza3RvcCIsInRhYmxldCIsIm1vYmlsZSIsImJhY2siLCJleWUiLCJzdmdDbGFzcyIsImRlZmF1bHQiLCJ1c2VEZWJvdW5jZSIsIlBhdHRlcm5MaWJyYXJ5IiwidGFiIiwic2V0U2VhcmNoUXVlcnkiLCJmaWx0ZXJzIiwic2V0RmlsdGVycyIsInBhdHRlcm5zIiwicGFnZSIsInBsYW5zIiwic2V0UGxhbnMiLCJsYXlvdXQiLCJzZXRMYXlvdXQiLCJkZWJvdW5jZWRTZWFyY2hRdWVyeSIsImZpbHRlclR5cGUiLCJwcmV2UGxhbnMiLCJoYW5kbGVGaWx0ZXJDaGFuZ2UiLCJwcmV2RmlsdGVycyIsImZpbHRlcmVkRGVzaWducyIsInF1ZXJ5TG93ZXJDYXNlIiwidG9Mb2NhbGVMb3dlckNhc2UiLCJ0aXRsZU1hdGNoIiwidGFnTWF0Y2giLCJ0YWciLCJzb21lIiwiZmlsdGVyZWREZXNpZ24iLCJtYXRjaENhdGVnb3J5IiwiY3dfY2F0ZWdvcmllcyIsImNhdGVnb3J5IiwibWF0Y2hUYWciLCJkZXNpbmdQbGFuIiwibWF0Y2hQbGFuIiwiVGFiUGFuZWwiLCJmZXRjaERlc2lnbnMiLCJ1c2VDYWxsYmFjayIsIk1vZGFsQ29udGV4dCIsIlBhdHRlcm5Nb2RhbCIsImZ1bGxTY3JlZW4iLCJzZXRTeW5jIiwiaXNFcnJvciIsInNldElzRXJyb3IiLCJzZXRQYXR0ZXJucyIsImNvbnRyb2xsZXIiLCJBYm9ydENvbnRyb2xsZXIiLCJmaW5hbGx5IiwiYWJvcnQiLCJtb2RhbENvbnRleHRWYWx1ZSIsImZpbHRlckNhdGVnb3JpZXMiLCJhY3RpdmVDbGFzcyIsInRhYnMiLCJmdWxscGFnZSIsIlByb3ZpZGVyIiwiVG9vbGJhckJ1dHRvbiIsIlRvb2xiYXJXcmFwIiwic2V0SXNNb2RhbE9wZW4iLCJjcmVhdGVSb290Iiwic3Vic2NyaWJlIiwiZG9tUmVhZHkiLCJQYXR0ZXJuVG9vbGJhciIsInRvb2xiYXJXcmFwIiwiZG9jdW1lbnQiLCJjbGFzc0xpc3QiLCJhZGQiLCJyZW5kZXIiLCJ0b29sYmFyIiwicXVlcnlTZWxlY3RvciIsImFwcGVuZENoaWxkIiwiYWRkUXVlcnlBcmdzIiwiYXBpRmV0Y2giLCJyZXMiLCJwYXRoIiwic2lnbmFsIiwibWVzc2FnZSIsInVzZVNlbGVjdCIsIkdldEFsbFBsdWdpbnMiLCJzZWxlY3QiLCJnZXRQbHVnaW5zIiwic3BsaXQiLCJkZWZhdWx0VmFsdWUiLCJkaXNwYXRjaCIsInNldFZhbHVlIiwiY3VycmVudFZhbHVlIiwiSlNPTiIsInBhcnNlIiwibG9jYWxTdG9yYWdlIiwiZ2V0SXRlbSIsIlN0cmluZyIsImhhbmRsZVN0b3JhZ2VDaGFuZ2UiLCJlIiwic3RvcmFnZUFyZWEiLCJuZXdWYWx1ZSIsIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJyZW1vdmVFdmVudExpc3RlbmVyIiwic3RyaW5naWZ5Iiwic2V0SXRlbSIsInN0b3JhZ2VFdmVudCIsIlN0b3JhZ2VFdmVudCIsImRpc3BhdGNoRXZlbnQiLCJjcmVhdGVDb250ZXh0IiwidXNlQ29udGV4dCIsIm1vZGFsIiwiZGVsYXkiLCJkZWJvdW5jZWRWYWx1ZSIsInNldERlYm91bmNlZFZhbHVlIiwiaGFuZGxlciIsInNldFRpbWVvdXQiLCJjbGVhclRpbWVvdXQiLCJnZXRBbGxQbHVnaW5zIiwianNvbiIsInBhcnNlZEJsb2NrcyIsImNvbnRlbnQiLCJpbnNlcnRCbG9ja3MiLCJlZGl0UG9zdCIsInNhdmVQb3N0IiwibG9jYXRpb24iLCJvYmoiLCJhcnJheSIsImVsIl0sInNvdXJjZVJvb3QiOiIifQ==