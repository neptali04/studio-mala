<?php
return
'
#custom-phone-grid {
  display: none; }

#footer-custom-phone-grid {
  display: none; }

.cover-region-phone, .cover-region-placeholder-phone {
  display: none; }

.sitetitle.txt .sitetitle-txt-inner {
  margin-top: 0;
  margin-bottom: 0; }

.row._100vh, .row._100vh.empty {
  min-height: 100vh; }
  .row._100vh .row-inner, .row._100vh .column-wrap:not(.stack-wrap), .row._100vh.empty .row-inner, .row._100vh.empty .column-wrap:not(.stack-wrap) {
    min-height: 100vh; }

nav.laynav li {
  display: inline-block; }

nav.laynav {
  white-space: nowrap; }

.burger-wrap {
  display: none; }

.mobile-title {
  display: none; }

.navbar {
  position: fixed;
  z-index: 10;
  width: 100%;
  -webkit-transform: translateZ(0);
          transform: translateZ(0); }

nav.mobile-nav {
  display: none; }

.sitetitle.txt .sitetitle-txt-inner span, nav.laynav span {
  border-bottom-style: solid; }

html.flexbox .column-wrap {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex; }

html.flexbox .column-wrap .stack-wrap {
  display: block; }

html.flexbox .col.align-middle {
  -webkit-align-self: center;
  -ms-flex-item-align: center;
      align-self: center;
  position: relative; }

html.flexbox .col.align-top {
  -ms-flex-item-align: start;
      align-self: flex-start; }

html.flexbox .col.align-bottom {
  -ms-flex-item-align: end;
      align-self: flex-end; }

html.no-flexbox .col.align-middle {
  position: relative;
  vertical-align: top; }

html.no-flexbox .col.align-top {
  vertical-align: top; }

html.no-flexbox .col.align-bottom {
  vertical-align: bottom; }

.cover-region {
  position: fixed;
  z-index: 0;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  will-change: transform; }

._100vh :not(.stack-element) > .col[data-type="text"] {
  position: absolute !important;
  margin-left: 0 !important;
  z-index: 1; }

._100vh :not(.stack-element) > .col[data-type="text"].align-top {
  top: 0; }

._100vh :not(.stack-element) > .col[data-type="text"].align-middle {
  top: 50%;
  -webkit-transform: translateY(-50%);
      -ms-transform: translateY(-50%);
          transform: translateY(-50%); }

._100vh :not(.stack-element) > .col[data-type="text"].align-bottom {
  bottom: 0; }

';