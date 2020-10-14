<?php
return
'
.hascustomphonegrid #grid {
  display: none; }

.hascustomphonegrid .cover-region-desktop {
  display: none; }

.hascustomphonegrid .cover-region-placeholder-desktop {
  display: none; }

.footer-hascustomphonegrid #footer {
  display: none; }

.tagline {
  display: none; }

body {
  -webkit-box-sizing: border-box;
          box-sizing: border-box; }

.fp-section.row._100vh, .fp-section.row._100vh.empty {
  min-height: 0; }
  .fp-section.row._100vh .row-inner, .fp-section.row._100vh.empty .row-inner {
    min-height: 0 !important; }

.lay-content.nocustomphonegrid #grid .col,
.lay-content.footer-nocustomphonegrid #footer .col,
.lay-content .cover-region-desktop .col {
  width: 100%;
  -webkit-transform: translate(0, 0) !important;
      -ms-transform: translate(0, 0) !important;
          transform: translate(0, 0) !important; }

html.flexbox .lay-content .row._100vh.one-col-row .column-wrap {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex; }

html.flexbox .lay-content .row._100vh.one-col-row .column-wrap .stack-wrap {
  display: block; }

html.flexbox .lay-content .row._100vh.one-col-row .col.align-middle {
  -webkit-align-self: center;
  -ms-flex-item-align: center;
      align-self: center;
  position: relative; }

html.flexbox .lay-content .row._100vh.one-col-row .col.align-bottom {
  -webkit-align-self: flex-end;
  -ms-flex-item-align: end;
      align-self: flex-end;
  position: relative; }

html.flexbox .lay-content .row._100vh.one-col-row .col.align-top {
  -webkit-align-self: flex-start;
  -ms-flex-item-align: start;
      align-self: flex-start;
  position: relative; }

.lay-content .row {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
  display: block; }

.lay-content .row:last-child .col:last-child {
  margin-bottom: 0 !important; }

html.flexbox #custom-phone-grid .column-wrap,
html.flexbox #footer-custom-phone-grid .column-wrap {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex; }

html.flexbox #custom-phone-grid .column-wrap .stack-wrap,
html.flexbox #footer-custom-phone-grid .column-wrap .stack-wrap {
  display: block; }

html.flexbox #custom-phone-grid .col.align-middle,
html.flexbox #footer-custom-phone-grid .col.align-middle {
  -webkit-align-self: center;
  -ms-flex-item-align: center;
      align-self: center;
  position: relative; }

html.flexbox #custom-phone-grid .col.align-top,
html.flexbox #footer-custom-phone-grid .col.align-top {
  -ms-flex-item-align: start;
      align-self: flex-start; }

html.flexbox #custom-phone-grid .col.align-bottom,
html.flexbox #footer-custom-phone-grid .col.align-bottom {
  -ms-flex-item-align: end;
      align-self: flex-end; }

html.no-flexbox #custom-phone-grid .col.align-middle,
html.no-flexbox #footer-custom-phone-grid .col.align-middle {
  position: relative;
  vertical-align: top; }

html.no-flexbox #custom-phone-grid .col.align-top,
html.no-flexbox #footer-custom-phone-grid .col.align-top {
  vertical-align: top; }

html.no-flexbox #custom-phone-grid .col.align-bottom,
html.no-flexbox #footer-custom-phone-grid .col.align-bottom {
  vertical-align: bottom; }

.row-inner {
  -webkit-box-sizing: border-box;
          box-sizing: border-box; }

.title a, .title {
  opacity: 1; }

.sitetitle {
  display: none; }

.navbar {
  display: block;
  top: 0;
  left: 0;
  bottom: auto;
  right: auto;
  width: 100%;
  z-index: 30;
  border-bottom-style: solid;
  border-bottom-width: 1px; }

.mobile-title.image {
  font-size: 0; }

.mobile-title.text {
  line-height: 1;
  display: -webkit-inline-box;
  display: -ms-inline-flexbox;
  display: inline-flex; }
  .mobile-title.text > span {
    -ms-flex-item-align: center;
        align-self: center; }

.mobile-title {
  z-index: 31;
  display: inline-block;
  -webkit-box-sizing: border-box;
          box-sizing: border-box; }
  .mobile-title img {
    -webkit-box-sizing: border-box;
            box-sizing: border-box;
    height: 100%; }

nav.primary, nav.second_menu, nav.third_menu, nav.fourth_menu {
  display: none; }

body.mobile-menu-style_desktop_menu .burger-wrap,
body.mobile-menu-style_desktop_menu .mobile-menu-close-custom {
  display: none; }

body.mobile-menu-style_desktop_menu nav.mobile-nav {
  z-index: 35;
  line-height: 1;
  white-space: nowrap; }
  body.mobile-menu-style_desktop_menu nav.mobile-nav li {
    vertical-align: top; }
  body.mobile-menu-style_desktop_menu nav.mobile-nav li:last-child {
    margin-right: 0 !important;
    margin-bottom: 0 !important; }
  body.mobile-menu-style_desktop_menu nav.mobile-nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    font-size: 0; }
  body.mobile-menu-style_desktop_menu nav.mobile-nav a {
    text-decoration: none; }
  body.mobile-menu-style_desktop_menu nav.mobile-nav span {
    border-bottom-style: solid;
    border-bottom-width: 0; }

.html5video .html5video-customplayicon {
  max-width: 100px; }

.cover-enabled-on-phone .cover-region {
  position: fixed;
  z-index: 0;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  will-change: transform; }

.cover-disabled-on-phone .cover-region-placeholder {
  display: none; }

#custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"],
#footer-custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"] {
  position: absolute !important;
  margin-left: 0 !important;
  z-index: 1; }

#custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"].align-top,
#footer-custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"].align-top {
  top: 0; }

#custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"].align-middle,
#footer-custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"].align-middle {
  top: 50%;
  -webkit-transform: translateY(-50%);
      -ms-transform: translateY(-50%);
          transform: translateY(-50%); }

#custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"].align-bottom,
#footer-custom-phone-grid ._100vh :not(.stack-element) > .col[data-type="text"].align-bottom {
  bottom: 0; }

body.mobile-menu-style_1.mobile-menu-has-animation.mobile-menu-animation-possible nav.mobile-nav {
  -webkit-transition: -webkit-transform 300ms cubic-bezier(0.52, 0.16, 0.24, 1);
  transition: -webkit-transform 300ms cubic-bezier(0.52, 0.16, 0.24, 1);
  -o-transition: transform 300ms cubic-bezier(0.52, 0.16, 0.24, 1);
  transition: transform 300ms cubic-bezier(0.52, 0.16, 0.24, 1);
  transition: transform 300ms cubic-bezier(0.52, 0.16, 0.24, 1), -webkit-transform 300ms cubic-bezier(0.52, 0.16, 0.24, 1); }

body.mobile-menu-style_1 nav.mobile-nav::-webkit-scrollbar {
  display: none; }

body.mobile-menu-style_1 nav.mobile-nav {
  -webkit-transform: translateY(-99999px);
      -ms-transform: translateY(-99999px);
          transform: translateY(-99999px);
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch;
  white-space: normal;
  width: 100%;
  top: 0;
  left: 0;
  bottom: auto; }
  body.mobile-menu-style_1 nav.mobile-nav .current-menu-item {
    opacity: 1; }
  body.mobile-menu-style_1 nav.mobile-nav li {
    display: block;
    margin-right: 0;
    margin-bottom: 0;
    padding: 0; }
    body.mobile-menu-style_1 nav.mobile-nav li a {
      display: block;
      opacity: 1;
      border-bottom-style: solid;
      border-bottom-width: 1px;
      -webkit-transition: background-color 200ms ease;
      -o-transition: background-color 200ms ease;
      transition: background-color 200ms ease;
      margin: 0; }
    body.mobile-menu-style_1 nav.mobile-nav li a:hover {
      opacity: 1; }
    body.mobile-menu-style_1 nav.mobile-nav li a .span-wrap {
      border-bottom: none; }
    body.mobile-menu-style_1 nav.mobile-nav li a:hover .span-wrap {
      border-bottom: none; }

body.mobile-menu-style_2.mobile-menu-has-animation.mobile-menu-animation-possible nav.mobile-nav {
  -webkit-transition: -webkit-transform 500ms cubic-bezier(0.52, 0.16, 0.24, 1);
  transition: -webkit-transform 500ms cubic-bezier(0.52, 0.16, 0.24, 1);
  -o-transition: transform 500ms cubic-bezier(0.52, 0.16, 0.24, 1);
  transition: transform 500ms cubic-bezier(0.52, 0.16, 0.24, 1);
  transition: transform 500ms cubic-bezier(0.52, 0.16, 0.24, 1), -webkit-transform 500ms cubic-bezier(0.52, 0.16, 0.24, 1); }

body.mobile-menu-style_2 nav.mobile-nav.active {
  -webkit-transform: translateX(0);
      -ms-transform: translateX(0);
          transform: translateX(0); }

body.mobile-menu-style_2 nav.mobile-nav::-webkit-scrollbar {
  display: none; }

body.mobile-menu-style_2 nav.mobile-nav {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
  z-index: 33;
  top: 0;
  height: 100vh;
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch;
  white-space: normal;
  width: 100%;
  -webkit-transform: translateX(100%);
      -ms-transform: translateX(100%);
          transform: translateX(100%); }
  body.mobile-menu-style_2 nav.mobile-nav li a {
    display: block;
    margin: 0;
    -webkit-box-sizing: border-box;
            box-sizing: border-box;
    width: 100%; }

body.mobile-menu-style_3.mobile-menu-has-animation.mobile-menu-animation-possible .mobile-nav ul {
  opacity: 0;
  -webkit-transition: opacity 300ms cubic-bezier(0.52, 0.16, 0.24, 1) 200ms;
  -o-transition: opacity 300ms cubic-bezier(0.52, 0.16, 0.24, 1) 200ms;
  transition: opacity 300ms cubic-bezier(0.52, 0.16, 0.24, 1) 200ms; }

body.mobile-menu-style_3.mobile-menu-has-animation.mobile-menu-animation-possible.mobile-menu-open .mobile-nav ul {
  opacity: 1; }

body.mobile-menu-style_3.mobile-menu-has-animation.mobile-menu-animation-possible nav.mobile-nav {
  -webkit-transition: height 500ms cubic-bezier(0.52, 0.16, 0.24, 1);
  -o-transition: height 500ms cubic-bezier(0.52, 0.16, 0.24, 1);
  transition: height 500ms cubic-bezier(0.52, 0.16, 0.24, 1); }

body.mobile-menu-style_3 nav.mobile-nav.active {
  -webkit-transform: translateX(0);
      -ms-transform: translateX(0);
          transform: translateX(0); }

body.mobile-menu-style_3 nav.mobile-nav::-webkit-scrollbar {
  display: none; }

body.mobile-menu-style_3 nav.mobile-nav {
  width: 100%;
  height: 0;
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
  z-index: 33;
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch;
  white-space: normal;
  width: 100%; }
  body.mobile-menu-style_3 nav.mobile-nav li a {
    display: block;
    margin: 0;
    -webkit-box-sizing: border-box;
            box-sizing: border-box;
    width: 100%; }

/**
 * Toggle Switch Globals
 *
 * All switches should take on the class `c-hamburger` as well as their
 * variant that will give them unique properties. This class is an overview
 * class that acts as a reset for all versions of the icon.
 */
.mobile-menu-style_1 .burger-wrap,
.mobile-menu-style_3 .burger-wrap {
  z-index: 33; }

.burger-wrap {
  padding-left: 10px;
  font-size: 0;
  z-index: 31;
  top: 0;
  right: 0;
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
  display: inline-block;
  cursor: pointer; }

.burger-wrap-default {
  padding-right: 10px;
  padding-top: 10px; }

.burger-inner {
  position: relative; }

.burger-default {
  border-radius: 0;
  overflow: hidden;
  margin: 0;
  padding: 0;
  width: 25px;
  height: 20px;
  font-size: 0;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  -webkit-box-shadow: none;
          box-shadow: none;
  border-radius: none;
  border: none;
  cursor: pointer;
  background-color: transparent; }

.burger-default:focus {
  outline: none; }

.burger-default span {
  display: block;
  position: absolute;
  left: 0;
  right: 0;
  background-color: #000; }

.default .burger-default span {
  height: 2px;
  top: 9px; }

.default .burger-default span::before,
.default .burger-default span::after {
  height: 2px; }

.default .burger-default span::before {
  top: -8px; }

.default .burger-default span::after {
  bottom: -8px; }

.default_thin .burger-default span {
  height: 1px;
  top: 9px; }

.default_thin .burger-default span::before,
.default_thin .burger-default span::after {
  height: 1px; }

.default_thin .burger-default span::before {
  top: -7px; }

.default_thin .burger-default span::after {
  bottom: -7px; }

.burger-default span::before,
.burger-default span::after {
  position: absolute;
  display: block;
  left: 0;
  width: 100%;
  background-color: #000;
  content: ""; }

/**
 * Style 2
 *
 * Hamburger to "x" (htx). Takes on a hamburger shape, bars slide
 * down to center and transform into an "x".
 */
.burger-has-animation .burger-default {
  -webkit-transition: background 0.2s;
  -o-transition: background 0.2s;
  transition: background 0.2s; }

.burger-has-animation .burger-default span {
  -webkit-transition: background-color 0.2s 0s;
  -o-transition: background-color 0.2s 0s;
  transition: background-color 0.2s 0s; }

.burger-has-animation .burger-default span::before,
.burger-has-animation .burger-default span::after {
  -webkit-transition-timing-function: cubic-bezier(0.04, 0.04, 0.12, 0.96);
       -o-transition-timing-function: cubic-bezier(0.04, 0.04, 0.12, 0.96);
          transition-timing-function: cubic-bezier(0.04, 0.04, 0.12, 0.96);
  -webkit-transition-duration: 0.2s, 0.2s;
       -o-transition-duration: 0.2s, 0.2s;
          transition-duration: 0.2s, 0.2s;
  -webkit-transition-delay: 0.2s, 0s;
       -o-transition-delay: 0.2s, 0s;
          transition-delay: 0.2s, 0s; }

.burger-has-animation .burger-default span::before {
  transition-property: top, -webkit-transform;
  -o-transition-property: top, transform;
  transition-property: top, transform;
  transition-property: top, transform, -webkit-transform;
  -webkit-transition-property: top, -webkit-transform; }

.burger-has-animation .burger-default span::after {
  transition-property: bottom, -webkit-transform;
  -o-transition-property: bottom, transform;
  transition-property: bottom, transform;
  transition-property: bottom, transform, -webkit-transform;
  -webkit-transition-property: bottom, -webkit-transform; }

.burger-has-animation .burger-default.active span::before,
.burger-has-animation .burger-default.active span::after {
  -webkit-transition-delay: 0s, 0.2s;
       -o-transition-delay: 0s, 0.2s;
          transition-delay: 0s, 0.2s; }

/* active state, i.e. menu open */
.burger-default.active span {
  background-color: transparent !important; }

.burger-default.active span::before {
  -webkit-transform: rotate(45deg);
      -ms-transform: rotate(45deg);
          transform: rotate(45deg);
  top: 0; }

.burger-default.active span::after {
  -webkit-transform: rotate(-45deg);
      -ms-transform: rotate(-45deg);
          transform: rotate(-45deg);
  bottom: 0; }

.mobile-menu-icon {
  z-index: 31; }

.mobile-menu-icon {
  cursor: pointer; }

.burger-custom-wrap-close {
  display: none; }

body.mobile-menu-style_2 .mobile-nav .burger-custom-wrap-close {
  display: block; }

body.mobile-menu-style_2 .burger-custom-wrap-open {
  display: block; }

body.mobile-menu-open.mobile-menu-style_3 .burger-custom-wrap-close,
body.mobile-menu-open.mobile-menu-style_1 .burger-custom-wrap-close {
  display: block; }

body.mobile-menu-open.mobile-menu-style_3 .burger-custom-wrap-open,
body.mobile-menu-open.mobile-menu-style_1 .burger-custom-wrap-open {
  display: none; }

/**
 * Toggle Switch Globals
 *
 * All switches should take on the class `c-hamburger` as well as their
 * variant that will give them unique properties. This class is an overview
 * class that acts as a reset for all versions of the icon.
 */
.burger-wrap-new {
  padding-right: 10px;
  padding-top: 10px; }

.burger-new {
  border-radius: 0;
  overflow: hidden;
  margin: 0;
  padding: 0;
  width: 30px;
  height: 30px;
  font-size: 0;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  -webkit-box-shadow: none;
          box-shadow: none;
  border-radius: none;
  border: none;
  cursor: pointer;
  background-color: transparent; }

.burger-new:focus {
  outline: none; }

.burger-new .bread-top,
.burger-new .bread-bottom {
  -webkit-transform: none;
      -ms-transform: none;
          transform: none;
  z-index: 4;
  position: absolute;
  z-index: 3;
  top: 0;
  left: 0;
  width: 30px;
  height: 30px; }

.burger-has-animation .bread-top,
.burger-has-animation .bread-bottom {
  -webkit-transition: -webkit-transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  transition: -webkit-transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  -o-transition: transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  transition: transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  transition: transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96), -webkit-transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96); }

.burger-has-animation .bread-crust-bottom,
.burger-has-animation .bread-crust-top {
  -webkit-transition: -webkit-transform 0.1596s cubic-bezier(0.52, 0.16, 0.52, 0.84) 0.1008s;
  transition: -webkit-transform 0.1596s cubic-bezier(0.52, 0.16, 0.52, 0.84) 0.1008s;
  -o-transition: transform 0.1596s cubic-bezier(0.52, 0.16, 0.52, 0.84) 0.1008s;
  transition: transform 0.1596s cubic-bezier(0.52, 0.16, 0.52, 0.84) 0.1008s;
  transition: transform 0.1596s cubic-bezier(0.52, 0.16, 0.52, 0.84) 0.1008s, -webkit-transform 0.1596s cubic-bezier(0.52, 0.16, 0.52, 0.84) 0.1008s; }

.burger-has-animation .burger-new.active .bread-top, .burger-has-animation .burger-new.active .bread-bottom {
  -webkit-transition: -webkit-transform 0.3192s cubic-bezier(0.04, 0.04, 0.12, 0.96) 0.1008s;
  transition: -webkit-transform 0.3192s cubic-bezier(0.04, 0.04, 0.12, 0.96) 0.1008s;
  -o-transition: transform 0.3192s cubic-bezier(0.04, 0.04, 0.12, 0.96) 0.1008s;
  transition: transform 0.3192s cubic-bezier(0.04, 0.04, 0.12, 0.96) 0.1008s;
  transition: transform 0.3192s cubic-bezier(0.04, 0.04, 0.12, 0.96) 0.1008s, -webkit-transform 0.3192s cubic-bezier(0.04, 0.04, 0.12, 0.96) 0.1008s; }

.burger-has-animation .burger-new.active .bread-crust-bottom, .burger-has-animation .burger-new.active .bread-crust-top {
  -webkit-transition: -webkit-transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  transition: -webkit-transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  -o-transition: transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  transition: transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96);
  transition: transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96), -webkit-transform 0.1806s cubic-bezier(0.04, 0.04, 0.12, 0.96); }

.burger-new .bread-crust-top,
.burger-new .bread-crust-bottom {
  display: block;
  width: 17px;
  height: 1px;
  background: #000;
  position: absolute;
  left: 7px;
  z-index: 1; }

.bread-crust-top {
  top: 14px;
  -webkit-transform: translateY(-3px);
      -ms-transform: translateY(-3px);
          transform: translateY(-3px); }

.bread-crust-bottom {
  bottom: 14px;
  -webkit-transform: translateY(3px);
      -ms-transform: translateY(3px);
          transform: translateY(3px); }

.burger-new.active .bread-top {
  -webkit-transform: rotate(45deg);
      -ms-transform: rotate(45deg);
          transform: rotate(45deg); }

.burger-new.active .bread-crust-bottom {
  -webkit-transform: none;
      -ms-transform: none;
          transform: none; }

.burger-new.active .bread-bottom {
  -webkit-transform: rotate(-45deg);
      -ms-transform: rotate(-45deg);
          transform: rotate(-45deg); }

.burger-new.active .bread-crust-top {
  -webkit-transform: none;
      -ms-transform: none;
          transform: none; }

.cover-disabled-on-phone .cover-region-desktop._100vh._100vh-not-set-by-user {
  min-height: auto !important; }
  .cover-disabled-on-phone .cover-region-desktop._100vh._100vh-not-set-by-user .cover-inner._100vh {
    min-height: auto !important; }
  .cover-disabled-on-phone .cover-region-desktop._100vh._100vh-not-set-by-user .row._100vh {
    min-height: auto !important; }
  .cover-disabled-on-phone .cover-region-desktop._100vh._100vh-not-set-by-user .row-inner._100vh {
    min-height: auto !important; }
  .cover-disabled-on-phone .cover-region-desktop._100vh._100vh-not-set-by-user .column-wrap._100vh {
    min-height: auto !important; }

';