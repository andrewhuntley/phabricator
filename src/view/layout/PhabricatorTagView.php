<?php

/*
 * Copyright 2012 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

final class PhabricatorTagView extends AphrontView {

  const TYPE_PERSON         = 'person';
  const TYPE_OBJECT         = 'object';
  const TYPE_STATE          = 'state';

  const COLOR_RED           = 'red';
  const COLOR_REDORANGE     = 'redorange';
  const COLOR_ORANGE        = 'orange';
  const COLOR_YELLOW        = 'yellow';
  const COLOR_BLUE          = 'blue';
  const COLOR_MAGENTA       = 'magenta';
  const COLOR_GREEN         = 'green';
  const COLOR_BLACK         = 'black';
  const COLOR_GREY          = 'grey';
  const COLOR_WHITE         = 'white';

  const COLOR_OBJECT        = 'object';
  const COLOR_PERSON        = 'person';

  private $type;
  private $href;
  private $name;
  private $phid;
  private $backgroundColor;
  private $dotColor;
  private $barColor;
  private $closed;

  public function setType($type) {
    $this->type = $type;
    switch ($type) {
      case self::TYPE_OBJECT:
        $this->setBackgroundColor(self::COLOR_OBJECT);
        break;
      case self::TYPE_PERSON:
        $this->setBackgroundColor(self::COLOR_PERSON);
        break;
    }
    return $this;
  }

  public function setBarColor($bar_color) {
    $this->barColor = $bar_color;
    return $this;
  }

  public function setDotColor($dot_color) {
    $this->dotColor = $dot_color;
    return $this;
  }

  public function setBackgroundColor($background_color) {
    $this->backgroundColor = $background_color;
    return $this;
  }

  public function setPHID($phid) {
    $this->phid = $phid;
    return $this;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function setHref($href) {
    $this->href = $href;
    return $this;
  }

  public function setClosed($closed) {
    $this->closed = $closed;
    return $this;
  }

  public function render() {
    if (!$this->type) {
      throw new Exception("You must call setType() before render()!");
    }

    require_celerity_resource('phabricator-tag-view-css');
    $classes = array(
      'phabricator-tag-view',
      'phabricator-tag-type-'.$this->type,
    );

    if ($this->closed) {
      $classes[] = 'phabricator-tag-state-closed';
    }

    $color = null;
    if ($this->backgroundColor) {
      $color = 'phabricator-tag-color-'.$this->backgroundColor;
    }

    if ($this->dotColor) {
      $dotcolor = 'phabricator-tag-color-'.$this->dotColor;
      $dot = phutil_render_tag(
        'span',
        array(
          'class' => 'phabricator-tag-dot '.$dotcolor,
        ),
        '');
    } else {
      $dot = null;
    }

    $content = phutil_render_tag(
      'span',
      array(
        'class' => 'phabricator-tag-core '.$color,
      ),
      $dot.phutil_escape_html($this->name));

    if ($this->barColor) {
      $barcolor = 'phabricator-tag-color-'.$this->barColor;
      $bar = phutil_render_tag(
        'span',
        array(
          'class' => 'phabricator-tag-bar '.$barcolor,
        ),
        '');
      $classes[] = 'phabricator-tag-view-has-bar';
    } else {
      $bar = null;
    }

    return phutil_render_tag(
      $this->href ? 'a' : 'span',
      array(
        'href'  => $this->href,
        'class' => implode(' ', $classes),
      ),
      $bar.$content);
  }

  public static function getTagTypes() {
    return array(
      self::TYPE_PERSON,
      self::TYPE_OBJECT,
      self::TYPE_STATE,
    );
  }

  public static function getColors() {
    return array(
      self::COLOR_RED,
      self::COLOR_REDORANGE,
      self::COLOR_ORANGE,
      self::COLOR_YELLOW,
      self::COLOR_BLUE,
      self::COLOR_MAGENTA,
      self::COLOR_GREEN,
      self::COLOR_BLACK,
      self::COLOR_GREY,
      self::COLOR_WHITE,

      self::COLOR_OBJECT,
      self::COLOR_PERSON,
    );
  }

}
